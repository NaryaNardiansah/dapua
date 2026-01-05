<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;

class UpdateProductSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:update {--force : Force update all data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update sales data for all products and categories based on completed orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating sales data for products and categories...');
        
        // Update products
        $this->info('Updating products...');
        $products = Product::all();
        $productBar = $this->output->createProgressBar($products->count());
        $productBar->start();

        $updatedProductCount = 0;
        
        foreach ($products as $product) {
            $this->updateProductSalesData($product);
            $updatedProductCount++;
            $productBar->advance();
        }

        $productBar->finish();
        $this->newLine();
        
        // Update categories
        $this->info('Updating categories...');
        $categories = Category::all();
        $categoryBar = $this->output->createProgressBar($categories->count());
        $categoryBar->start();

        $updatedCategoryCount = 0;
        
        foreach ($categories as $category) {
            $this->updateCategorySalesData($category);
            $updatedCategoryCount++;
            $categoryBar->advance();
        }

        $categoryBar->finish();
        $this->newLine();
        
        $this->info("Successfully updated sales data for {$updatedProductCount} products and {$updatedCategoryCount} categories.");
        
        return Command::SUCCESS;
    }

    /**
     * Update sales data for a specific product
     */
    private function updateProductSalesData(Product $product): void
    {
        // Calculate total quantity sold (sum of quantities from completed orders)
        $totalQuantitySold = $product->orderItems()
            ->whereHas('order', function($query) {
                $query->whereIn('status', ['selesai', 'dikirim']);
            })
            ->sum('quantity');

        // Calculate total sales amount (sum of line_total from completed orders)
        $totalSalesAmount = $product->orderItems()
            ->whereHas('order', function($query) {
                $query->whereIn('status', ['selesai', 'dikirim']);
            })
            ->sum('line_total');

        // Update product with calculated sales data
        $product->update([
            'purchase_count' => $totalQuantitySold,
            'total_sales' => $totalSalesAmount,
        ]);
    }

    /**
     * Update sales data for a specific category
     */
    private function updateCategorySalesData(Category $category): void
    {
        // Calculate total quantity sold for all products in this category
        $totalQuantitySold = $category->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['selesai', 'dikirim'])
            ->sum('order_items.quantity');

        // Calculate total sales amount for all products in this category
        $totalSalesAmount = $category->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['selesai', 'dikirim'])
            ->sum('order_items.line_total');

        // Update category with calculated sales data
        $category->update([
            'total_sales' => $totalSalesAmount,
            'total_quantity_sold' => $totalQuantitySold,
        ]);
    }
}
