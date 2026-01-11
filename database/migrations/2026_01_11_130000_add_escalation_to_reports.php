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
        Schema::table('reports', function (Blueprint $table) {
            $table->timestamp('escalated_at')->nullable()->after('status');
            $table->tinyInteger('escalation_level')->default(0)->after('escalated_at');
            // Level 0 = Not escalated
            // Level 1 = Escalated to Staf Kesiswaan (after 5 hours)
            // Level 2 = Escalated to Kepala Sekolah (after 12 hours)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['escalated_at', 'escalation_level']);
        });
    }
};
