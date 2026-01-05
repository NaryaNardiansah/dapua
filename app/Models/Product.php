<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
	protected $fillable = [
		'category_id',
		'name',
		'slug',
		'description',
		'short_description',
		'image_path',
		'price',
		'stock',
		'min_stock',
		'track_stock',
		'is_best_seller',
		'is_featured',
		'is_new',
		'is_on_sale',
		'variants',
		'variant_options',
		'variant_prices',
		'variant_stock',
		'is_active',
		'sort_order',
		'sku',
		'barcode',
		'weight',
		'dimensions',
		'specifications',
		'tags',
		'meta_title',
		'meta_description',
		'meta_keywords',
		'view_count',
		'cart_count',
		'purchase_count',
		'total_sales',
		'sale_price',
		'sale_start',
		'sale_end',
		'gallery',
		'video_url',
		'settings',
		'featured_until'
	];

	protected $casts = [
		'variants' => 'array',
		'variant_options' => 'array',
		'variant_prices' => 'array',
		'variant_stock' => 'array',
		'specifications' => 'array',
		'tags' => 'array',
		'dimensions' => 'array',
		'gallery' => 'array',
		'settings' => 'array',
		'is_best_seller' => 'boolean',
		'is_featured' => 'boolean',
		'is_new' => 'boolean',
		'is_on_sale' => 'boolean',
		'is_active' => 'boolean',
		'track_stock' => 'boolean',
		'total_sales' => 'decimal:2',
		'sale_price' => 'decimal:2',
		'sale_start' => 'datetime',
		'sale_end' => 'datetime',
		'featured_until' => 'datetime',
	];

	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	public function orderItems(): HasMany
	{
		return $this->hasMany(OrderItem::class);
	}

	public function wishlistedBy(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
	}

	public function reviews(): HasMany
	{
		return $this->hasMany(Review::class);
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

	public function scopeBestSeller($query)
	{
		return $query->where('is_best_seller', true);
	}

	public function scopeOnSale($query)
	{
		return $query->where('is_on_sale', true)
			->where(function ($q) {
				$q->whereNull('sale_start')
					->orWhere('sale_start', '<=', now());
			})
			->where(function ($q) {
				$q->whereNull('sale_end')
					->orWhere('sale_end', '>=', now());
			});
	}

	public function scopeLowStock($query)
	{
		return $query->where('track_stock', true)
			->whereColumn('stock', '<=', 'min_stock');
	}

	public function scopeInStock($query)
	{
		return $query->where(function ($q) {
			$q->where('track_stock', false)
				->orWhere('stock', '>', 0);
		});
	}

	// Accessors
	public function getImageUrlAttribute()
	{
		return $this->image_path ? asset('storage/' . $this->image_path) : null;
	}

	public function getGalleryUrlsAttribute()
	{
		if (!$this->gallery)
			return collect();

		return collect($this->gallery)->map(function ($image) {
			return asset('storage/' . $image);
		});
	}

	public function getHasActiveDiscountAttribute(): bool
	{
		if (!$this->is_on_sale || !$this->sale_price || $this->sale_price >= $this->price) {
			return false;
		}

		$now = now();
		if ($this->sale_start && $this->sale_start > $now)
			return false;
		if ($this->sale_end && $this->sale_end < $now)
			return false;

		return true;
	}

	public function getCurrentPriceAttribute()
	{
		return $this->has_active_discount ? (float) $this->sale_price : (float) $this->price;
	}

	public function getDiscountPercentageAttribute()
	{
		if ($this->has_active_discount && $this->price > 0) {
			return round((($this->price - $this->sale_price) / $this->price) * 100);
		}
		return 0;
	}

	public function getIsLowStockAttribute()
	{
		return $this->track_stock && $this->stock <= $this->min_stock;
	}

	public function getIsOutOfStockAttribute()
	{
		return $this->track_stock && $this->stock <= 0;
	}

	public function getAverageRatingAttribute()
	{
		return $this->reviews()->avg('rating') ?? 0;
	}

	public function getTotalReviewsAttribute()
	{
		return $this->reviews()->count();
	}

	// Methods
	public function incrementViewCount()
	{
		$this->increment('view_count');
	}

	public function incrementCartCount()
	{
		$this->increment('cart_count');
	}

	public function incrementPurchaseCount($quantity = 1)
	{
		$this->increment('purchase_count', $quantity);
	}

	public function updateSalesTotal()
	{
		$total = $this->orderItems()->sum('line_total');
		$this->update(['total_sales' => $total]);
	}

	public function reduceStock($quantity)
	{
		if ($this->track_stock) {
			$this->decrement('stock', $quantity);
		}
	}

	public function addStock($quantity)
	{
		if ($this->track_stock) {
			$this->increment('stock', $quantity);
		}
	}

	public function canBePurchased($quantity = 1)
	{
		if (!$this->is_active)
			return false;
		if (!$this->track_stock)
			return true;
		return $this->stock >= $quantity;
	}

	public function getStockStatusAttribute()
	{
		if (!$this->track_stock)
			return 'unlimited';
		if ($this->stock <= 0)
			return 'out_of_stock';
		if ($this->stock <= $this->min_stock)
			return 'low_stock';
		return 'in_stock';
	}

	public function isWishlistedBy($user): bool
	{
		if (!$user)
			return false;
		return $this->wishlistedBy()->where('user_id', $user->id)->exists();
	}
}
