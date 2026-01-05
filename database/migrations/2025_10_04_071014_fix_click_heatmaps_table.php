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
        // Drop and recreate click_heatmaps table with correct structure
        Schema::dropIfExists('click_heatmaps');
        
        Schema::create('click_heatmaps', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('page_url');
            $table->string('element_selector');
            $table->integer('x_coordinate');
            $table->integer('y_coordinate');
            $table->timestamp('clicked_at');
            $table->json('custom_data')->nullable();
            $table->timestamps();

            $table->foreign('session_id')->references('session_id')->on('user_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['page_url', 'clicked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('click_heatmaps');
    }
};

