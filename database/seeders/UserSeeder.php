<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'slug' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer', 'slug' => 'customer']);
        $driverRole = Role::firstOrCreate(['name' => 'driver', 'slug' => 'driver']);

        $users = [
            // Admin Users
            [
                'name' => 'Admin Dapur Sakura',
                'email' => 'admin@dapur-sakura.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'role' => 'admin',
            ],
            [
                'name' => 'Manager Toko',
                'email' => 'manager@dapur-sakura.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
                'role' => 'admin',
            ],
            
            // Customer Users
            [
                'name' => 'Sintia',
                'email' => 'sintia1234@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'role' => 'customer',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'role' => 'customer',
            ],
            [
                'name' => 'Sari Indah',
                'email' => 'sari.indah@yahoo.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'role' => 'customer',
            ],
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'role' => 'customer',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'role' => 'customer',
            ],
            [
                'name' => 'Rizki Pratama',
                'email' => 'rizki.pratama@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'customer',
            ],
            
            // Driver Users
            [
                'name' => 'Driver Agus',
                'email' => 'driver.agus@dapur-sakura.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'role' => 'driver',
            ],
            [
                'name' => 'Driver Budi',
                'email' => 'driver.budi@dapur-sakura.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'role' => 'driver',
            ],
            [
                'name' => 'Driver Candra',
                'email' => 'driver.candra@dapur-sakura.test',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'role' => 'driver',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel && !$user->hasRole($role)) {
                $user->roles()->attach($roleModel);
            }
        }
    }
}

