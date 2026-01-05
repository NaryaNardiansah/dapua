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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Customer
            $table->string('type'); // email, sms, push, whatsapp, in_app
            $table->string('channel'); // order_update, delivery_update, payment_update, promotion
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data for the notification
            $table->string('status')->default('pending'); // pending, sent, delivered, failed
            $table->timestamp('scheduled_at')->nullable(); // When to send
            $table->timestamp('sent_at')->nullable(); // When it was sent
            $table->timestamp('delivered_at')->nullable(); // When it was delivered
            $table->text('error_message')->nullable(); // Error if failed
            $table->integer('retry_count')->default(0); // Number of retries
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();
            
            $table->index(['order_id', 'type']);
            $table->index(['user_id', 'status']);
            $table->index(['scheduled_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
