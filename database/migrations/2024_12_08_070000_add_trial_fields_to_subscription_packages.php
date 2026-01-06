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
        Schema::table('subscription_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_packages', 'is_trial')) {
                $table->boolean('is_trial')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('subscription_packages', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_trial');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            if (Schema::hasColumn('subscription_packages', 'is_trial')) {
                $table->dropColumn('is_trial');
            }
        });
    }
};
