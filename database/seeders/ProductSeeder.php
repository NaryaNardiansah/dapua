<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Ayam Krispi
            ['category' => 'Ayam Krispi', 'name' => 'Ayam Krispi Saos Korea', 'price' => 18000, 'best' => false],
            ['category' => 'Ayam Krispi', 'name' => 'Ayam Krispi Saos Teriyaki', 'price' => 18000, 'best' => false],
            ['category' => 'Ayam Krispi', 'name' => 'Ayam Krispi Lada Hitam', 'price' => 18000, 'best' => false],
            // Ayam Katsu
            ['category' => 'Ayam Katsu', 'name' => 'Katsu Original', 'price' => 20000, 'best' => false],
            ['category' => 'Ayam Katsu', 'name' => 'Katsu Teriyaki', 'price' => 22000, 'best' => false],
            ['category' => 'Ayam Katsu', 'name' => 'Katsu Lada Hitam', 'price' => 22000, 'best' => false],
            // Aneka Mie Olahan
            ['category' => 'Aneka Mie Olahan', 'name' => 'Kwetyau Paket Komplit', 'price' => 25000, 'best' => false],
            ['category' => 'Aneka Mie Olahan', 'name' => 'Mie Becek', 'price' => 20000, 'best' => false],
            ['category' => 'Aneka Mie Olahan', 'name' => 'Mie Goreng Extra Hot', 'price' => 20000, 'best' => false],
            // Menu Lokal
            ['category' => 'Menu Lokal', 'name' => 'Ayam Geprek Sambal Mantah', 'price' => 18000, 'best' => false],
            ['category' => 'Menu Lokal', 'name' => 'Ayam Geprek Sambal Terasi', 'price' => 18000, 'best' => false],
            // Aneka Minuman
            ['category' => 'Aneka Minuman', 'name' => 'Teh Es', 'price' => 5000, 'best' => false],
            ['category' => 'Aneka Minuman', 'name' => 'Milk Ice', 'price' => 10000, 'best' => false, 'variants' => ['strawberry','matcha','chocolate','vanilla']],
            ['category' => 'Aneka Minuman', 'name' => 'Ice Lemontea', 'price' => 8000, 'best' => false],
            // Paket Hemat
            ['category' => 'Paket Hemat', 'name' => 'Ayam Korea + Free Es Teh', 'price' => 20000, 'best' => false],
            ['category' => 'Paket Hemat', 'name' => 'Ayam Katsu + Free Es Teh', 'price' => 22000, 'best' => false],
            ['category' => 'Paket Hemat', 'name' => 'Ayam Lada Hitam + Free Es Teh', 'price' => 20000, 'best' => false],
            // Paket Combo
            ['category' => 'Paket Combo', 'name' => '2 Ayam Krispi + 1 Kwetyau + 2 Nasi Gila + 2 Es Teh + 2 Milk Ice', 'price' => 95000, 'best' => false],
            // Menu Istimewa (BEST SELLER)
            ['category' => 'Menu Istimewa', 'name' => 'Nasi Gila Dapur Sakura', 'price' => 25000, 'best' => true],
        ];

        $categorySlugToId = DB::table('categories')->pluck('id','slug');

        foreach ($products as $index => $p) {
            $categorySlug = Str::slug($p['category']);
            $categoryId = $categorySlugToId[$categorySlug] ?? null;
            if ($categoryId === null) {
                continue;
            }
            DB::table('products')->updateOrInsert(
                ['slug' => Str::slug($p['name'])],
                [
                    'category_id' => $categoryId,
                    'name' => $p['name'],
                    'slug' => Str::slug($p['name']),
                    'description' => null,
                    'image_path' => null,
                    'price' => $p['price'],
                    'is_best_seller' => $p['best'] ?? false,
                    'variants' => isset($p['variants']) ? json_encode($p['variants']) : null,
                    'is_active' => true,
                    'sort_order' => $index,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
