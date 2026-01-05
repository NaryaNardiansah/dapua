<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Stock Management
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'min_stock')) {
                $table->integer('min_stock')->default(5)->after('stock');
            }
            if (!Schema::hasColumn('products', 'track_stock')) {
                $table->boolean('track_stock')->default(true)->after('min_stock');
            }
            
            // Enhanced Content
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->text('short_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'specifications')) {
                $table->json('specifications')->nullable()->after('short_description');
            }
            if (!Schema::hasColumn('products', 'tags')) {
                $table->json('tags')->nullable()->after('specifications');
            }
            
            // SEO & Marketing
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('tags');
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }
            
            // Analytics & Performance
            if (!Schema::hasColumn('products', 'view_count')) {
                $table->unsignedBigInteger('view_count')->default(0)->after('meta_keywords');
            }
            if (!Schema::hasColumn('products', 'cart_count')) {
                $table->unsignedBigInteger('cart_count')->default(0)->after('view_count');
            }
            if (!Schema::hasColumn('products', 'purchase_count')) {
                $table->unsignedBigInteger('purchase_count')->default(0)->after('cart_count');
            }
            if (!Schema::hasColumn('products', 'total_sales')) {
                $table->decimal('total_sales', 10, 2)->default(0.00)->after('purchase_count');
            }
            
            // Product Management
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('slug');
            }
            if (!Schema::hasColumn('products', 'barcode')) {
                $table->string('barcode')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable()->after('barcode');
            }
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->json('dimensions')->nullable()->after('weight');
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_best_seller');
            }
            if (!Schema::hasColumn('products', 'is_new')) {
                $table->boolean('is_new')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('products', 'is_on_sale')) {
                $table->boolean('is_on_sale')->default(false)->after('is_new');
            }
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('is_on_sale');
            }
            if (!Schema::hasColumn('products', 'sale_start')) {
                $table->timestamp('sale_start')->nullable()->after('sale_price');
            }
            if (!Schema::hasColumn('products', 'sale_end')) {
                $table->timestamp('sale_end')->nullable()->after('sale_start');
            }
            
            // Media
            if (!Schema::hasColumn('products', 'gallery')) {
                $table->json('gallery')->nullable()->after('image_path');
            }
            if (!Schema::hasColumn('products', 'video_url')) {
                $table->string('video_url')->nullable()->after('gallery');
            }
            
            // Variants Enhancement
            if (!Schema::hasColumn('products', 'variant_options')) {
                $table->json('variant_options')->nullable()->after('variants');
            }
            if (!Schema::hasColumn('products', 'variant_prices')) {
                $table->json('variant_prices')->nullable()->after('variant_options');
            }
            if (!Schema::hasColumn('products', 'variant_stock')) {
                $table->json('variant_stock')->nullable()->after('variant_prices');
            }
            
            // Settings
            if (!Schema::hasColumn('products', 'settings')) {
                $table->json('settings')->nullable()->after('variant_stock');
            }
            if (!Schema::hasColumn('products', 'featured_until')) {
                $table->timestamp('featured_until')->nullable()->after('is_featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'stock', 'min_stock', 'track_stock',
                'description', 'short_description', 'specifications', 'tags',
                'meta_title', 'meta_description', 'meta_keywords',
                'view_count', 'cart_count', 'purchase_count', 'total_sales',
                'sku', 'barcode', 'weight', 'dimensions',
                'is_featured', 'is_new', 'is_on_sale', 'sale_price', 'sale_start', 'sale_end',
                'gallery', 'video_url',
                'variant_options', 'variant_prices', 'variant_stock',
                'settings', 'featured_until'
            ]);
        });
    }
};

