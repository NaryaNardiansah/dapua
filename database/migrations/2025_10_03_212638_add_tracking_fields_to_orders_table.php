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
            $table->string('tracking_code')->nullable()->after('midtrans_transaction_id');
            $table->string('tracking_url')->nullable()->after('tracking_code');
            $table->timestamp('assigned_at')->nullable()->after('tracking_url');
            $table->foreignId('driver_id')->nullable()->after('assigned_at')->constrained('users')->onDelete('set null');
            $table->timestamp('picked_up_at')->nullable()->after('driver_id');
            $table->timestamp('delivered_at')->nullable()->after('picked_up_at');
            $table->text('delivery_photo')->nullable()->after('delivered_at');
            $table->text('delivery_notes')->nullable()->after('delivery_photo');
            $table->integer('delivery_rating')->nullable()->after('delivery_notes');
            $table->text('delivery_feedback')->nullable()->after('delivery_rating');
            $table->string('delivery_zone')->nullable()->after('delivery_feedback');
            $table->decimal('zone_multiplier', 3, 2)->default(1.00)->after('delivery_zone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn([
                'tracking_code',
                'tracking_url', 
                'assigned_at',
                'driver_id',
                'picked_up_at',
                'delivered_at',
                'delivery_photo',
                'delivery_notes',
                'delivery_rating',
                'delivery_feedback',
                'delivery_zone',
                'zone_multiplier'
            ]);
        });
    }
};

