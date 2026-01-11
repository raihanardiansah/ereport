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
        // Add anonymous reporting columns to reports table
        Schema::table('reports', function (Blueprint $table) {
            $table->boolean('is_anonymous')->default(false)->after('status');
            $table->string('device_fingerprint', 64)->nullable()->after('is_anonymous');
        });

        // Create rate limiting table for anonymous reports
        Schema::create('anonymous_report_limits', function (Blueprint $table) {
            $table->id();
            $table->string('device_fingerprint', 64)->index();
            $table->string('ip_address', 45)->index();
            $table->unsignedInteger('daily_count')->default(0);
            $table->date('date')->index();
            $table->timestamps();

            $table->unique(['device_fingerprint', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['is_anonymous', 'device_fingerprint']);
        });

        Schema::dropIfExists('anonymous_report_limits');
    }
};
