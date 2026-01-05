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
        Schema::create('order_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->string('sender_type'); // customer, driver, admin, system
            $table->text('message');
            $table->string('message_type')->default('text'); // text, image, voice, location, file
            $table->json('metadata')->nullable(); // Additional data (file path, location coords, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_system_message')->default(false);
            $table->timestamps();
            
            $table->index(['order_id', 'created_at']);
            $table->index(['sender_id', 'sender_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_chats');
    }
};

