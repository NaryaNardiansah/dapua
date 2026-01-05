<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Ayam Krispi', 'color' => '#f472b6'],
            ['name' => 'Ayam Katsu', 'color' => '#fb7185'],
            ['name' => 'Aneka Mie Olahan', 'color' => '#f9a8d4'],
            ['name' => 'Menu Lokal', 'color' => '#fda4af'],
            ['name' => 'Aneka Minuman', 'color' => '#fce7f3'],
            ['name' => 'Paket Hemat', 'color' => '#fda4d4'],
            ['name' => 'Paket Combo', 'color' => '#fbcfe8'],
            ['name' => 'Menu Istimewa', 'color' => '#f43f5e'],
        ];

        foreach ($categories as $index => $cat) {
            DB::table('categories')->updateOrInsert(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'slug' => Str::slug($cat['name']),
                    'color' => $cat['color'] ?? null,
                    'sort_order' => $index,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
