<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full access to all system features',
                'permissions' => [
                    'users.view',
                    'users.create',
                    'users.edit',
                    'users.delete',
                    'products.view',
                    'products.create',
                    'products.edit',
                    'products.delete',
                    'orders.view',
                    'orders.edit',
                    'orders.delete',
                    'categories.view',
                    'categories.create',
                    'categories.edit',
                    'categories.delete',
                    'drivers.view',
                    'drivers.create',
                    'drivers.edit',
                    'drivers.delete',
                    'reports.view',
                    'settings.edit',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Driver',
                'slug' => 'driver',
                'description' => 'Driver role for delivery management',
                'permissions' => [
                    'orders.view',
                    'orders.update_status',
                    'delivery.track',
                    'delivery.update_location',
                    'delivery.mark_complete',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'description' => 'Regular customer role',
                'permissions' => [
                    'orders.create',
                    'orders.view_own',
                    'products.view',
                    'profile.edit',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }
    }
}

