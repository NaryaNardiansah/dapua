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
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('accuracy', 8, 2)->nullable(); // GPS accuracy in meters
            $table->decimal('speed', 8, 2)->nullable(); // Speed in km/h
            $table->decimal('heading', 5, 2)->nullable(); // Direction in degrees
            $table->string('status')->default('online'); // online, offline, busy, delivering
            $table->timestamp('last_seen_at');
            $table->json('metadata')->nullable(); // Additional GPS data
            $table->timestamps();
            
            $table->index(['driver_id', 'last_seen_at']);
            $table->index(['order_id', 'last_seen_at']);
            $table->index(['status', 'last_seen_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};

