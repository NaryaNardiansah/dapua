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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_driver')->default(false)->after('is_admin');
            $table->string('driver_license')->nullable()->after('is_driver');
            $table->string('vehicle_type')->nullable()->after('driver_license');
            $table->string('vehicle_number')->nullable()->after('vehicle_type');
            $table->decimal('current_latitude', 10, 8)->nullable()->after('vehicle_number');
            $table->decimal('current_longitude', 11, 8)->nullable()->after('current_latitude');
            $table->timestamp('last_location_update')->nullable()->after('current_longitude');
            $table->boolean('is_available')->default(true)->after('last_location_update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_driver',
                'driver_license',
                'vehicle_type',
                'vehicle_number',
                'current_latitude',
                'current_longitude',
                'last_location_update',
                'is_available'
            ]);
        });
    }
};