<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;

class UpdateCategoryProductCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:update-product-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product count for all categories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating product count for all categories...');
        
        $categories = Category::all();
        $totalProducts = 0;
        
        foreach ($categories as $category) {
            $productCount = Product::where('category_id', $category->id)->count();
            $category->update(['product_count' => $productCount]);
            $totalProducts += $productCount;
            
            $this->line("Category '{$category->name}': {$productCount} products");
        }
        
        $this->info("Total products across all categories: {$totalProducts}");
        $this->info('Product count update completed!');
        
        return 0;
    }
}

