<x-guest-layout>
    <style>
        .form-header {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .form-title {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            font-size: 0.8125rem;
            color: var(--gray-600);
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 0.875rem;
        }

        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.375rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.9375rem;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 0.6875rem 0.75rem 0.6875rem 2.25rem;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 0.8125rem;
            transition: all 0.3s ease;
            background: var(--gray-50);
            color: var(--gray-900);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-pink);
            background: white;
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
        }

        .form-input:focus+.input-icon {
            color: var(--primary-pink);
        }

        .form-input::placeholder {
            color: var(--gray-400);
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-input {
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 6px;
            border: 2px solid var(--gray-300);
            cursor: pointer;
            transition: all 0.2s ease;
            accent-color: var(--primary-pink);
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: var(--gray-700);
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            font-size: 0.875rem;
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--dark-pink);
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 0.75rem 1.125rem;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary-pink));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.25rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--gray-200);
        }

        .divider span {
            padding: 0 1rem;
            color: var(--gray-500);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .social-login {
            display: flex;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 0.875rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            background: white;
            color: var(--gray-700);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            width: 100%;
            max-width: 300px;
        }

        .social-btn:hover {
            border-color: var(--primary-pink);
            background: var(--light-pink);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15);
        }

        .social-btn svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .register-link {
            text-align: center;
            font-size: 0.9375rem;
            color: var(--gray-600);
        }

        .register-link a {
            color: var(--primary-pink);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .register-link a:hover {
            color: var(--dark-pink);
            text-decoration: underline;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.8125rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .success-message {
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 12px;
            color: #047857;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        /* Responsive Design */
        @media (max-width: 640px) {
            .social-login {
                grid-template-columns: 1fr;
            }

            .form-input {
                font-size: 16px;
                /* Prevent zoom on iOS */
            }

            .form-title {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="form-container">
        <!-- Form Header -->
        <div class="form-header">
            <h1 class="form-title">Selamat Datang Kembali!</h1>
            <p class="form-subtitle">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-wrapper">
                    <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required
                        autofocus autocomplete="username" placeholder="nama@email.com">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                @error('email')
                    <p class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-wrapper">
                    <input id="password" class="form-input" type="password" name="password" required
                        autocomplete="current-password" placeholder="Masukkan password Anda">
                    <i class="fas fa-lock input-icon"></i>
                </div>
                @error('password')
                    <p class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="form-row">
                <div class="checkbox-wrapper">
                    <input id="remember_me" type="checkbox" class="checkbox-input" name="remember">
                    <label for="remember_me" class="checkbox-label">Ingat Saya</label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i>
                Masuk
            </button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span>Atau masuk dengan</span>
        </div>

        <!-- Social Login -->
        <div class="social-login">
            <a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="social-btn">
                <svg viewBox="0 0 24 24">
                    <path fill="#EA4335"
                        d="M12 10.2v3.6h5.1c-.2 1.2-1.5 3.6-5.1 3.6-3.1 0-5.7-2.6-5.7-5.7S8.9 6 12 6c1.8 0 3 .8 3.6 1.4l2.5-2.5C16.8 3.7 14.6 2.7 12 2.7 6.9 2.7 2.7 6.9 2.7 12S6.9 21.3 12 21.3c6.9 0 9.6-4.9 9.6-7.4 0-.5 0-.8-.1-1.2H12z" />
                </svg>
                Google
            </a>
        </div>

        <!-- Register Link -->
        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
    </div>
</x-guest-layout>