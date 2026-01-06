<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * 1. Create report_accused pivot table for multiple accused per report
     * 2. Add index tracking columns to users table
     */
    public function up(): void
    {
        // Create pivot table for multiple accused per report
        Schema::create('report_accused', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('accused_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Ensure unique combination
            $table->unique(['report_id', 'accused_user_id']);
            
            // Index for faster lookups
            $table->index('accused_user_id');
        });

        // Add index tracking columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->integer('positive_index')->default(0);
            $table->integer('neutral_index')->default(0);
            $table->integer('negative_index')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_accused');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['positive_index', 'neutral_index', 'negative_index']);
        });
    }
};
