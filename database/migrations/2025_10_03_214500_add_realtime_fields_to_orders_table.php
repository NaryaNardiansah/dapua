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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('estimated_delivery_at')->nullable()->after('delivered_at');
            $table->text('order_notes')->nullable()->after('delivery_notes');
            $table->text('delivery_instructions')->nullable()->after('order_notes');
            $table->boolean('is_cancellable')->default(true)->after('delivery_instructions');
            $table->timestamp('cancelled_at')->nullable()->after('is_cancellable');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            $table->boolean('customer_notified')->default(false)->after('cancellation_reason');
            $table->timestamp('last_status_update')->nullable()->after('customer_notified');
            $table->json('status_history')->nullable()->after('last_status_update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_delivery_at',
                'order_notes',
                'delivery_instructions',
                'is_cancellable',
                'cancelled_at',
                'cancellation_reason',
                'customer_notified',
                'last_status_update',
                'status_history'
            ]);
        });
    }
};

