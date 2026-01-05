<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryZone;
use App\Models\User;

class DeliveryZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample delivery zones
        $zones = [
            [
                'name' => 'Zona Pusat Kota',
                'slug' => 'pusat-kota',
                'description' => 'Area pusat kota dengan tarif standar',
                'polygon_coordinates' => [
                    [-0.95, 100.40],
                    [-0.94, 100.40],
                    [-0.94, 100.42],
                    [-0.95, 100.42],
                    [-0.95, 100.40]
                ],
                'base_rate' => 5000,
                'per_km_rate' => 2000,
                'multiplier' => 1.00,
                'max_distance_km' => 15,
                'color' => '#EC4899',
                'sort_order' => 1,
            ],
            [
                'name' => 'Zona Pinggiran',
                'slug' => 'pinggiran',
                'description' => 'Area pinggiran kota dengan tarif lebih tinggi',
                'polygon_coordinates' => [
                    [-0.96, 100.38],
                    [-0.93, 100.38],
                    [-0.93, 100.44],
                    [-0.96, 100.44],
                    [-0.96, 100.38]
                ],
                'base_rate' => 8000,
                'per_km_rate' => 3000,
                'multiplier' => 1.50,
                'max_distance_km' => 25,
                'color' => '#F59E0B',
                'sort_order' => 2,
            ],
            [
                'name' => 'Zona Luar Kota',
                'slug' => 'luar-kota',
                'description' => 'Area luar kota dengan tarif premium',
                'polygon_coordinates' => [
                    [-0.98, 100.35],
                    [-0.92, 100.35],
                    [-0.92, 100.45],
                    [-0.98, 100.45],
                    [-0.98, 100.35]
                ],
                'base_rate' => 12000,
                'per_km_rate' => 4000,
                'multiplier' => 2.00,
                'max_distance_km' => 50,
                'color' => '#EF4444',
                'sort_order' => 3,
            ],
        ];

        foreach ($zones as $zone) {
            DeliveryZone::updateOrCreate(
                ['slug' => $zone['slug']],
                $zone
            );
        }

        // Create sample drivers
        $drivers = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@dapursakura.com',
                'password' => bcrypt('password'),
                'is_driver' => true,
                'driver_license' => 'SIM-A-123456789',
                'vehicle_type' => 'Motor',
                'vehicle_number' => 'B-1234-ABC',
                'current_latitude' => -0.947100,
                'current_longitude' => 100.417200,
                'last_location_update' => now(),
                'is_available' => true,
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@dapursakura.com',
                'password' => bcrypt('password'),
                'is_driver' => true,
                'driver_license' => 'SIM-A-987654321',
                'vehicle_type' => 'Motor',
                'vehicle_number' => 'B-5678-DEF',
                'current_latitude' => -0.945000,
                'current_longitude' => 100.420000,
                'last_location_update' => now(),
                'is_available' => true,
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad@dapursakura.com',
                'password' => bcrypt('password'),
                'is_driver' => true,
                'driver_license' => 'SIM-A-456789123',
                'vehicle_type' => 'Motor',
                'vehicle_number' => 'B-9012-GHI',
                'current_latitude' => -0.949000,
                'current_longitude' => 100.415000,
                'last_location_update' => now(),
                'is_available' => false,
            ],
        ];

        foreach ($drivers as $driverData) {
            $email = $driverData['email'];
            unset($driverData['email']);
            
            User::updateOrCreate(
                ['email' => $email],
                $driverData
            );
        }

        $this->command->info('Delivery zones and sample drivers created successfully!');
    }
}

