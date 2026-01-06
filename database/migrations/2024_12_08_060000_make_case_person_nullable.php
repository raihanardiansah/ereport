<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Make student_id nullable in student_cases and teacher_id nullable in teacher_cases
     * Cases are now created with reports, not with a specific person
     */
    public function up(): void
    {
        // Make student_id nullable in student_cases
        Schema::table('student_cases', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->change();
        });

        // Make teacher_id nullable in teacher_cases
        Schema::table('teacher_cases', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reversing may fail if there are null values
        Schema::table('student_cases', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable(false)->change();
        });

        Schema::table('teacher_cases', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable(false)->change();
        });
    }
};
