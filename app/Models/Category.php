<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'color', 'description', 'is_active', 'sort_order',
        'image', 'banner', 'icon', 'meta_title', 'meta_description', 'keywords',
        'parent_id', 'level', 'path', 'view_count', 'total_sales', 'total_quantity_sold', 'product_count',
        'is_featured', 'is_trending', 'promotional_text', 'settings', 'featured_until'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'settings' => 'array',
        'featured_until' => 'datetime',
        'total_sales' => 'decimal:2',
    ];

    // Relationships
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithProducts($query)
    {
        return $query->where('product_count', '>', 0);
    }

    public function scopeEmpty($query)
    {
        return $query->where('product_count', 0);
    }

    // Accessors & Mutators
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? asset('storage/' . $this->banner) : null;
    }

    public function getIconUrlAttribute()
    {
        return $this->icon ? asset('storage/' . $this->icon) : null;
    }

    public function getFullPathAttribute()
    {
        $path = collect();
        $category = $this;
        
        while ($category) {
            $path->prepend($category->name);
            $category = $category->parent;
        }
        
        return $path->implode(' > ');
    }

    public function getIsEmptyAttribute()
    {
        return $this->product_count === 0;
    }

    public function getHasChildrenAttribute()
    {
        return $this->children()->count() > 0;
    }

    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function updateProductCount()
    {
        $this->update(['product_count' => $this->products()->count()]);
    }

    public function updateSalesTotal()
    {
        // Calculate total sales amount from completed orders only
        $totalSalesAmount = $this->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['selesai', 'dikirim'])
            ->sum('order_items.line_total');

        // Calculate total quantity sold from completed orders only
        $totalQuantitySold = $this->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['selesai', 'dikirim'])
            ->sum('order_items.quantity');

        $this->update([
            'total_sales' => $totalSalesAmount,
            'total_quantity_sold' => $totalQuantitySold
        ]);
    }

    /**
     * Get total sales amount for this category
     */
    public function getTotalSalesAmount()
    {
        return $this->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['selesai', 'dikirim'])
            ->sum('order_items.line_total');
    }

    /**
     * Get total quantity sold for this category
     */
    public function getTotalQuantitySold()
    {
        return $this->products()
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['selesai', 'dikirim'])
            ->sum('order_items.quantity');
    }

    public function generatePath()
    {
        $path = collect();
        $category = $this;
        
        while ($category) {
            $path->prepend($category->id);
            $category = $category->parent;
        }
        
        $this->update(['path' => $path->implode('/')]);
    }

    public function canBeDeleted()
    {
        return $this->product_count === 0 && $this->children()->count() === 0;
    }

    public function getBreadcrumb()
    {
        $breadcrumb = collect();
        $category = $this;
        
        while ($category) {
            $breadcrumb->prepend([
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug
            ]);
            $category = $category->parent;
        }
        
        return $breadcrumb;
    }
}
