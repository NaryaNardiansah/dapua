<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if tables exist before creating them
        if (!Schema::hasTable('user_sessions')) {
            Schema::create('user_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('session_id')->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('ip_address');
                $table->text('user_agent');
                $table->string('device_type');
                $table->string('browser');
                $table->string('os');
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('ended_at')->nullable();
                $table->integer('duration')->nullable();
                $table->integer('page_views')->default(0);
                $table->json('custom_data')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['session_id', 'started_at']);
            });
        }

        if (!Schema::hasTable('user_journeys')) {
            Schema::create('user_journeys', function (Blueprint $table) {
                $table->id();
                $table->string('session_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('page_url');
                $table->string('page_title');
                $table->integer('step_order');
                $table->integer('time_on_page');
                $table->integer('scroll_depth')->nullable();
                $table->json('interactions')->nullable();
                $table->timestamp('visited_at')->nullable();
                $table->timestamps();

                $table->foreign('session_id')->references('session_id')->on('user_sessions')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['session_id', 'visited_at']);
            });
        }

        if (!Schema::hasTable('click_heatmaps')) {
            Schema::create('click_heatmaps', function (Blueprint $table) {
                $table->id();
                $table->string('session_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('page_url');
                $table->string('element_selector');
                $table->integer('x_coordinate');
                $table->integer('y_coordinate');
                $table->timestamp('clicked_at')->nullable();
                $table->json('custom_data')->nullable();
                $table->timestamps();

                $table->foreign('session_id')->references('session_id')->on('user_sessions')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['page_url', 'clicked_at']);
            });
        }

        if (!Schema::hasTable('funnel_events')) {
            Schema::create('funnel_events', function (Blueprint $table) {
                $table->id();
                $table->string('session_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('funnel_name');
                $table->string('step_name');
                $table->integer('step_order');
                $table->boolean('completed')->default(false);
                $table->timestamp('completed_at')->nullable();
                $table->json('step_data')->nullable();
                $table->timestamps();

                $table->foreign('session_id')->references('session_id')->on('user_sessions')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['funnel_name', 'step_order']);
            });
        }

        if (!Schema::hasTable('user_cohorts')) {
            Schema::create('user_cohorts', function (Blueprint $table) {
                $table->id();
                $table->string('cohort_name');
                $table->json('definition');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('customer_metrics')) {
            Schema::create('customer_metrics', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->decimal('lifetime_value', 10, 2)->default(0);
                $table->decimal('average_order_value', 10, 2)->default(0);
                $table->integer('total_orders')->default(0);
                $table->integer('total_visits')->default(0);
                $table->integer('days_since_first_visit')->nullable();
                $table->integer('days_since_last_visit')->nullable();
                $table->decimal('cart_abandonment_rate', 5, 2)->default(0);
                $table->string('customer_segment')->nullable();
                $table->timestamp('last_calculated_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'last_calculated_at']);
            });
        }

        if (!Schema::hasTable('product_analytics')) {
            Schema::create('product_analytics', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->date('analytics_date');
                $table->integer('views')->default(0);
                $table->integer('unique_views')->default(0);
                $table->integer('add_to_cart')->default(0);
                $table->integer('purchases')->default(0);
                $table->decimal('revenue', 10, 2)->default(0);
                $table->decimal('conversion_rate', 5, 2)->default(0);
                $table->integer('time_on_page')->default(0);
                $table->decimal('bounce_rate', 5, 2)->default(0);
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->index(['product_id', 'analytics_date']);
            });
        }

        if (!Schema::hasTable('campaign_analytics')) {
            Schema::create('campaign_analytics', function (Blueprint $table) {
                $table->id();
                $table->string('campaign_name');
                $table->string('campaign_type');
                $table->string('source');
                $table->string('medium');
                $table->date('campaign_date');
                $table->integer('impressions')->default(0);
                $table->integer('clicks')->default(0);
                $table->integer('visits')->default(0);
                $table->integer('conversions')->default(0);
                $table->decimal('cost', 10, 2)->default(0);
                $table->decimal('revenue', 10, 2)->default(0);
                $table->decimal('roi', 5, 2)->default(0);
                $table->decimal('ctr', 5, 2)->default(0);
                $table->decimal('conversion_rate', 5, 2)->default(0);
                $table->timestamps();

                $table->index(['campaign_name', 'campaign_date']);
            });
        }

        if (!Schema::hasTable('realtime_events')) {
            Schema::create('realtime_events', function (Blueprint $table) {
                $table->id();
                $table->string('session_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('event_type');
                $table->json('event_data')->nullable();
                $table->timestamp('occurred_at')->nullable();
                $table->timestamps();

                $table->foreign('session_id')->references('session_id')->on('user_sessions')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['event_type', 'occurred_at']);
            });
        }

        if (!Schema::hasTable('ab_test_results')) {
            Schema::create('ab_test_results', function (Blueprint $table) {
                $table->id();
                $table->string('test_name');
                $table->string('variant_name');
                $table->integer('impressions')->default(0);
                $table->integer('conversions')->default(0);
                $table->decimal('conversion_rate', 5, 2)->default(0);
                $table->timestamp('tested_at')->nullable();
                $table->timestamps();

                $table->index(['test_name', 'variant_name']);
            });
        }

        if (!Schema::hasTable('performance_metrics')) {
            Schema::create('performance_metrics', function (Blueprint $table) {
                $table->id();
                $table->string('page_url');
                $table->decimal('load_time', 8, 3)->default(0);
                $table->decimal('first_contentful_paint', 8, 3)->default(0);
                $table->decimal('largest_contentful_paint', 8, 3)->default(0);
                $table->decimal('first_input_delay', 8, 3)->default(0);
                $table->decimal('cumulative_layout_shift', 8, 3)->default(0);
                $table->integer('http_requests')->default(0);
                $table->integer('dom_elements')->default(0);
                $table->string('device_type')->nullable();
                $table->string('connection_type')->nullable();
                $table->timestamp('measured_at')->nullable();
                $table->timestamps();

                $table->index(['page_url', 'measured_at']);
            });
        }

        if (!Schema::hasTable('error_logs')) {
            Schema::create('error_logs', function (Blueprint $table) {
                $table->id();
                $table->string('error_type');
                $table->text('error_message');
                $table->string('error_file')->nullable();
                $table->integer('error_line')->nullable();
                $table->text('stack_trace')->nullable();
                $table->text('user_agent')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('page_url')->nullable();
                $table->json('additional_data')->nullable();
                $table->integer('occurrence_count')->default(1);
                $table->timestamp('first_occurred_at')->nullable();
                $table->timestamp('last_occurred_at')->nullable();
                $table->timestamps();

                $table->index(['error_type', 'last_occurred_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
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
    }
};

