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
            // Check if columns exist before adding them
            if (!Schema::hasColumn('orders', 'order_confirmed_at')) {
                $table->timestamp('order_confirmed_at')->nullable()->after('last_status_update');
            }
            if (!Schema::hasColumn('orders', 'preparation_started_at')) {
                $table->timestamp('preparation_started_at')->nullable()->after('order_confirmed_at');
            }
            if (!Schema::hasColumn('orders', 'preparation_completed_at')) {
                $table->timestamp('preparation_completed_at')->nullable()->after('preparation_started_at');
            }
            if (!Schema::hasColumn('orders', 'out_for_delivery_at')) {
                $table->timestamp('out_for_delivery_at')->nullable()->after('preparation_completed_at');
            }
            if (!Schema::hasColumn('orders', 'driver_arrived_at')) {
                $table->timestamp('driver_arrived_at')->nullable()->after('out_for_delivery_at');
            }
            
            // ETA and prediction fields
            if (!Schema::hasColumn('orders', 'estimated_preparation_time')) {
                $table->timestamp('estimated_preparation_time')->nullable()->after('driver_arrived_at');
            }
            if (!Schema::hasColumn('orders', 'estimated_delivery_time')) {
                $table->timestamp('estimated_delivery_time')->nullable()->after('estimated_preparation_time');
            }
            if (!Schema::hasColumn('orders', 'estimated_delivery_minutes')) {
                $table->integer('estimated_delivery_minutes')->nullable()->after('estimated_delivery_time');
            }
            
            // Communication and interaction
            if (!Schema::hasColumn('orders', 'communication_log')) {
                $table->json('communication_log')->nullable()->after('estimated_delivery_minutes');
            }
            if (!Schema::hasColumn('orders', 'special_requests')) {
                $table->text('special_requests')->nullable()->after('communication_log');
            }
            if (!Schema::hasColumn('orders', 'customer_confirmed')) {
                $table->boolean('customer_confirmed')->default(false)->after('special_requests');
            }
            if (!Schema::hasColumn('orders', 'customer_confirmed_at')) {
                $table->timestamp('customer_confirmed_at')->nullable()->after('customer_confirmed');
            }
            if (!Schema::hasColumn('orders', 'customer_feedback')) {
                $table->text('customer_feedback')->nullable()->after('customer_confirmed_at');
            }
            if (!Schema::hasColumn('orders', 'customer_rating')) {
                $table->integer('customer_rating')->nullable()->after('customer_feedback');
            }
            
            // Weather and external factors
            if (!Schema::hasColumn('orders', 'weather_data')) {
                $table->json('weather_data')->nullable()->after('customer_rating');
            }
            if (!Schema::hasColumn('orders', 'delay_reason')) {
                $table->text('delay_reason')->nullable()->after('weather_data');
            }
            if (!Schema::hasColumn('orders', 'delay_minutes')) {
                $table->integer('delay_minutes')->default(0)->after('delay_reason');
            }
            
            // Payment tracking
            if (!Schema::hasColumn('orders', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('delay_minutes');
            }
            if (!Schema::hasColumn('orders', 'payment_confirmed_at')) {
                $table->timestamp('payment_confirmed_at')->nullable()->after('payment_reference');
            }
            if (!Schema::hasColumn('orders', 'refund_data')) {
                $table->json('refund_data')->nullable()->after('payment_confirmed_at');
            }
            
            // Analytics and performance
            if (!Schema::hasColumn('orders', 'performance_metrics')) {
                $table->json('performance_metrics')->nullable()->after('refund_data');
            }
            if (!Schema::hasColumn('orders', 'total_preparation_time_minutes')) {
                $table->integer('total_preparation_time_minutes')->nullable()->after('performance_metrics');
            }
            if (!Schema::hasColumn('orders', 'total_delivery_time_minutes')) {
                $table->integer('total_delivery_time_minutes')->nullable()->after('total_preparation_time_minutes');
            }
            
            // QR Code and sharing
            if (!Schema::hasColumn('orders', 'qr_code')) {
                $table->string('qr_code')->nullable()->after('total_delivery_time_minutes');
            }
            if (!Schema::hasColumn('orders', 'share_token')) {
                $table->string('share_token')->nullable()->unique()->after('qr_code');
            }
            if (!Schema::hasColumn('orders', 'social_shares')) {
                $table->json('social_shares')->nullable()->after('share_token');
            }
            
            // Mobile app integration
            if (!Schema::hasColumn('orders', 'device_token')) {
                $table->string('device_token')->nullable()->after('social_shares');
            }
            if (!Schema::hasColumn('orders', 'platform')) {
                $table->string('platform')->nullable()->after('device_token'); // web, mobile, api
            }
            if (!Schema::hasColumn('orders', 'app_metadata')) {
                $table->json('app_metadata')->nullable()->after('platform');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_confirmed_at', 'preparation_started_at', 'preparation_completed_at', 
                'out_for_delivery_at', 'driver_arrived_at',
                'estimated_preparation_time', 'estimated_delivery_time', 'estimated_delivery_minutes',
                'communication_log', 'special_requests', 'customer_confirmed', 'customer_confirmed_at', 
                'customer_feedback', 'customer_rating',
                'weather_data', 'delay_reason', 'delay_minutes',
                'payment_reference', 'payment_confirmed_at', 'refund_data',
                'performance_metrics', 'total_preparation_time_minutes', 'total_delivery_time_minutes',
                'qr_code', 'share_token', 'social_shares',
                'device_token', 'platform', 'app_metadata'
            ]);
        });
    }
};

