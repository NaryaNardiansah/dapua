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
        Schema::create('customer_segment_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_segment_id')->constrained()->onDelete('cascade');
            $table->json('segment_data')->nullable(); // Store calculated segment data
            $table->decimal('score', 5, 2)->default(0); // Segment matching score
            $table->timestamp('assigned_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'customer_segment_id']);
            $table->index(['customer_segment_id', 'score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_segment_assignments');
    }
};