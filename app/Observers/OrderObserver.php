<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Product;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Update product sales when order status changes
        if ($order->isDirty('status')) {
            $this->updateProductSales($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // Update product sales when order is deleted
        $this->updateProductSales($order);
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        // Update product sales when order is restored
        $this->updateProductSales($order);
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        // Update product sales when order is force deleted
        $this->updateProductSales($order);
    }

    /**
     * Update product sales based on order status
     */
    private function updateProductSales(Order $order): void
    {
        // Get all products in this order
        $productIds = $order->orderItems()->pluck('product_id')->unique();
        
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $this->updateProductSalesData($product);
                // Also update category sales
                $this->updateCategorySalesData($product->category);
            }
        }
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

        // Count number of completed orders for this product
        $completedOrdersCount = $product->orderItems()
            ->whereHas('order', function($query) {
                $query->whereIn('status', ['selesai', 'dikirim']);
            })
            ->distinct('order_id')
            ->count('order_id');

        // Update product with calculated sales data
        $product->update([
            'purchase_count' => $totalQuantitySold,
            'total_sales' => $totalSalesAmount,
        ]);
    }

    /**
     * Update sales data for a specific category
     */
    private function updateCategorySalesData($category): void
    {
        if (!$category) return;

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
