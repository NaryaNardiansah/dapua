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
        // Drop all analytics tables
        Schema::dropIfExists('error_logs');
        Schema::dropIfExists('performance_metrics');
        Schema::dropIfExists('ab_test_results');
        Schema::dropIfExists('realtime_events');
        Schema::dropIfExists('campaign_analytics');
        Schema::dropIfExists('product_analytics');
        Schema::dropIfExists('customer_metrics');
        Schema::dropIfExists('user_cohorts');
        Schema::dropIfExists('funnel_events');
        Schema::dropIfExists('click_heatmaps');
        Schema::dropIfExists('user_journeys');
        Schema::dropIfExists('user_sessions');
        Schema::dropIfExists('analytics');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is irreversible
        // If you need to restore analytics, recreate the tables manually
    }
};

