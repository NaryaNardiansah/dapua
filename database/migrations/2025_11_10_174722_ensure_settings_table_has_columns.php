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
        if (Schema::hasTable('settings')) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('settings', 'key')) {
                Schema::table('settings', function (Blueprint $table) {
                    $table->string('key')->unique()->after('id');
                });
            }
            if (!Schema::hasColumn('settings', 'value')) {
                Schema::table('settings', function (Blueprint $table) {
                    $table->text('value')->nullable()->after('key');
                });
            }
            if (!Schema::hasColumn('settings', 'description')) {
                Schema::table('settings', function (Blueprint $table) {
                    $table->string('description')->nullable()->after('value');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (Schema::hasColumn('settings', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('settings', 'value')) {
                    $table->dropColumn('value');
                }
                if (Schema::hasColumn('settings', 'key')) {
                    $table->dropColumn('key');
                }
            });
        }
    }
};
