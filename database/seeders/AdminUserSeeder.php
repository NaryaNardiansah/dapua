<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@dapur-sakura.test');
        $password = env('ADMIN_PASSWORD', 'password');
        $now = now();
        
        // Create or update admin user
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => true,
                'email_verified_at' => $now,
            ]
        );
        
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full access to all system features',
                'is_active' => true,
            ]
        );
        
        // Assign admin role to user if not already assigned
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}
