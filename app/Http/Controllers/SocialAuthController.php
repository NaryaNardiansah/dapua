<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google'], true), 404);
        $callbackUrl = route('social.callback', ['provider' => $provider], absolute: true);
        return Socialite::driver($provider)
            ->redirectUrl($callbackUrl)
            ->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, ['google'], true), 404);

        try {
            $callbackUrl = route('social.callback', ['provider' => $provider], absolute: true);
            $socialUser = Socialite::driver($provider)
                ->redirectUrl($callbackUrl)
                ->stateless()
                ->user();
        } catch (\Throwable $e) {
            Log::warning('Social login failed', ['provider' => $provider, 'error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors(['social' => 'Gagal login dengan ' . $provider]);
        }

        $email = $socialUser->getEmail();
        $providerId = (string) $socialUser->getId();

        // 1) Jika sudah ada user dengan provider_id yang sama, login
        $existingByProvider = User::where('provider', $provider)->where('provider_id', $providerId)->first();
        if ($existingByProvider) {
            Auth::login($existingByProvider, remember: true);

            // Redirect based on user role
            if ($existingByProvider->isAdmin()) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } elseif ($existingByProvider->isDriver()) {
                return redirect()->intended(route('driver.dashboard', absolute: false));
            } else {
                return redirect()->intended(route('home', absolute: false));
            }
        }

        // 2) Jika ada user dengan email sama -> link akun (hindari duplikasi)
        if (!empty($email)) {
            $existingByEmail = User::where('email', $email)->first();
            if ($existingByEmail) {
                // Kebijakan: otomatis link jika email sudah terverifikasi
                $existingByEmail->update([
                    'provider' => $provider,
                    'provider_id' => $providerId,
                ]);

                Auth::login($existingByEmail, remember: true);

                // Redirect based on user role
                if ($existingByEmail->isAdmin()) {
                    return redirect()->intended(route('admin.dashboard', absolute: false));
                } elseif ($existingByEmail->isDriver()) {
                    return redirect()->intended(route('driver.dashboard', absolute: false));
                } else {
                    return redirect()->intended(route('home', absolute: false));
                }
            }
        }

        // 3) Jika email belum terdaftar, buat akun baru (password null), tandai provider
        $name = $socialUser->getName() ?: ($socialUser->getNickname() ?: 'User');
        $user = User::create([
            'name' => $name,
            'email' => $email ?? (strtolower($provider) . '_' . $providerId . '@example.local'),
            // Beri password acak agar tidak NULL (menghindari constraint NOT NULL)
            'password' => Str::password(32),
            'provider' => $provider,
            'provider_id' => $providerId,
        ]);

        // Assign customer role to new social login users
        $user->assignRole('customer');

        // Jika model mengimplementasikan MustVerifyEmail, tandai sebagai verified bila provider mengembalikan email verified
        if ($user instanceof MustVerifyEmail) {
            // Banyak provider menganggap email valid; di sini kita bisa langsung verifikasi jika email tersedia
            if (!empty($email)) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }
        }

        Auth::login($user, remember: true);

        // Redirect based on user role (new users default to customer)
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } elseif ($user->isDriver()) {
            return redirect()->intended(route('driver.dashboard', absolute: false));
        } else {
            return redirect()->intended(route('home', absolute: false));
        }
    }
}










