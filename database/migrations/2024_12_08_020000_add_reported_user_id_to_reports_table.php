<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Add reported_user_id field to reports table to track which student
     * the report is ABOUT (not who made the report).
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // The student being reported about (nullable for anonymous/general reports)
            $table->foreignId('reported_user_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['reported_user_id']);
            $table->dropColumn('reported_user_id');
        });
    }
};
