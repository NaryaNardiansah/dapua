<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingSetting;

class ShippingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'store_lat',
                'value' => '-0.947100',
                'description' => 'Latitude toko (Padang, Sumatra Barat)'
            ],
            [
                'key' => 'store_lng',
                'value' => '100.417200',
                'description' => 'Longitude toko (Padang, Sumatra Barat)'
            ],
            [
                'key' => 'shipping_base',
                'value' => '10000',
                'description' => 'Tarif dasar ongkir (Rp)'
            ],
            [
                'key' => 'shipping_per_km',
                'value' => '2000',
                'description' => 'Tarif per kilometer (Rp)'
            ],
            [
                'key' => 'shipping_radius',
                'value' => '50',
                'description' => 'Radius layanan (km)'
            ],
            [
                'key' => 'free_shipping_min',
                'value' => '0',
                'description' => 'Minimum belanja gratis ongkir (Rp)'
            ],
            [
                'key' => 'max_shipping_distance',
                'value' => '100',
                'description' => 'Maksimum jarak pengiriman (km)'
            ],
        ];

        foreach ($settings as $setting) {
            ShippingSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
