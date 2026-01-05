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
        Schema::table('categories', function (Blueprint $table) {
            // Check and add columns that don't exist
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable()->after('color');
            }
            if (!Schema::hasColumn('categories', 'banner')) {
                $table->string('banner')->nullable()->after('image');
            }
            if (!Schema::hasColumn('categories', 'icon')) {
                $table->string('icon')->nullable()->after('banner');
            }
            
            // Content & SEO Management (skip description as it already exists)
            if (!Schema::hasColumn('categories', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('categories', 'keywords')) {
                $table->text('keywords')->nullable()->after('meta_description');
            }
            
            // Hierarchy & Organization
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade')->after('keywords');
            }
            if (!Schema::hasColumn('categories', 'level')) {
                $table->integer('level')->default(0)->after('parent_id');
            }
            if (!Schema::hasColumn('categories', 'path')) {
                $table->string('path')->nullable()->after('level');
            }
            
            // Analytics & Performance
            if (!Schema::hasColumn('categories', 'view_count')) {
                $table->integer('view_count')->default(0)->after('path');
            }
            if (!Schema::hasColumn('categories', 'total_sales')) {
                $table->decimal('total_sales', 15, 2)->default(0)->after('view_count');
            }
            if (!Schema::hasColumn('categories', 'product_count')) {
                $table->integer('product_count')->default(0)->after('total_sales');
            }
            
            // Marketing & Promotional
            if (!Schema::hasColumn('categories', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('product_count');
            }
            if (!Schema::hasColumn('categories', 'is_trending')) {
                $table->boolean('is_trending')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('categories', 'promotional_text')) {
                $table->string('promotional_text')->nullable()->after('is_trending');
            }
            
            // Additional Settings
            if (!Schema::hasColumn('categories', 'settings')) {
                $table->json('settings')->nullable()->after('promotional_text');
            }
            if (!Schema::hasColumn('categories', 'featured_until')) {
                $table->timestamp('featured_until')->nullable()->after('settings');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'image', 'banner', 'icon', 'meta_title', 
                'meta_description', 'keywords', 'parent_id', 'level', 
                'path', 'view_count', 'total_sales', 'product_count',
                'is_featured', 'is_trending', 'promotional_text', 
                'settings', 'featured_until'
            ]);
        });
    }
};