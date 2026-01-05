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
        Schema::create('order_timeline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status')->index(); // pending, confirmed, prepared, ready_for_pickup, out_for_delivery, arrived, delivered, cancelled
            $table->string('title'); // Human readable title
            $table->text('description')->nullable(); // Detailed description
            $table->string('icon')->nullable(); // Font Awesome icon class
            $table->string('color')->default('#6B7280'); // Color for the timeline item
            $table->timestamp('timestamp');
            $table->json('metadata')->nullable(); // Additional data (driver info, location, etc.)
            $table->string('triggered_by')->nullable(); // admin, driver, system, customer
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Who triggered this
            $table->boolean('is_automatic')->default(false); // Was this triggered automatically?
            $table->boolean('is_visible_to_customer')->default(true); // Should this be shown to customer?
            $table->timestamps();
            
            $table->index(['order_id', 'timestamp']);
            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_timeline');
    }
};
