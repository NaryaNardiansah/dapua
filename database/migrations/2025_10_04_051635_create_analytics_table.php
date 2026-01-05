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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // page_view, product_view, add_to_cart, purchase, search, etc.
            $table->string('event_category'); // user_behavior, sales, marketing, performance
            $table->string('event_action'); // view, click, add, purchase, search
            $table->string('event_label')->nullable(); // specific item or page
            $table->decimal('event_value', 10, 2)->nullable(); // monetary value or count
            $table->string('session_id')->nullable(); // user session
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // authenticated user
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null'); // if product related
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null'); // if order related
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // if category related
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('utm_source')->nullable(); // marketing source
            $table->string('utm_medium')->nullable(); // marketing medium
            $table->string('utm_campaign')->nullable(); // marketing campaign
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->json('custom_data')->nullable(); // additional custom data
            $table->timestamp('event_timestamp');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['event_type', 'event_timestamp']);
            $table->index(['event_category', 'event_timestamp']);
            $table->index(['user_id', 'event_timestamp']);
            $table->index(['session_id', 'event_timestamp']);
            $table->index(['product_id', 'event_timestamp']);
            $table->index(['order_id', 'event_timestamp']);
            $table->index(['utm_source', 'utm_campaign', 'event_timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};

