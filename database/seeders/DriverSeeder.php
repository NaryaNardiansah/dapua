<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@dapursakura.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'is_driver' => true,
                'driver_license' => 'SIM123456789',
                'vehicle_type' => 'Motor',
                'vehicle_number' => 'BA 1234 ABC',
                'current_latitude' => -0.947100,
                'current_longitude' => 100.417200,
                'is_available' => true,
                'last_location_update' => now()
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@dapursakura.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'is_driver' => true,
                'driver_license' => 'SIM123456790',
                'vehicle_type' => 'Motor',
                'vehicle_number' => 'BA 1235 DEF',
                'current_latitude' => -0.947200,
                'current_longitude' => 100.417300,
                'is_available' => true,
                'last_location_update' => now()
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citra.dewi@dapursakura.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'is_driver' => true,
                'driver_license' => 'SIM123456791',
                'vehicle_type' => 'Mobil',
                'vehicle_number' => 'BA 1236 GHI',
                'current_latitude' => -0.947300,
                'current_longitude' => 100.417400,
                'is_available' => false,
                'last_location_update' => now()->subHours(2)
            ],
            [
                'name' => 'Dedi Kurniawan',
                'email' => 'dedi.kurniawan@dapursakura.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'is_driver' => true,
                'driver_license' => 'SIM123456792',
                'vehicle_type' => 'Motor',
                'vehicle_number' => 'BA 1237 JKL',
                'current_latitude' => -0.947400,
                'current_longitude' => 100.417500,
                'is_available' => true,
                'last_location_update' => now()->subMinutes(30)
            ],
            [
                'name' => 'Eka Putri',
                'email' => 'eka.putri@dapursakura.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567894',
                'is_driver' => true,
                'driver_license' => 'SIM123456793',
                'vehicle_type' => 'Sepeda',
                'vehicle_number' => 'BA 1238 MNO',
                'current_latitude' => -0.947500,
                'current_longitude' => 100.417600,
                'is_available' => true,
                'last_location_update' => now()->subMinutes(15)
            ]
        ];

        foreach ($drivers as $driverData) {
            User::updateOrCreate(
                ['email' => $driverData['email']],
                $driverData
            );
        }
    }
}
