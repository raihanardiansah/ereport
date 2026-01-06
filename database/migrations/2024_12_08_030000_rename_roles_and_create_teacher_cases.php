<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * 1. Change role column from ENUM to VARCHAR first
     * 2. Rename roles: guru_bk -> staf_kesiswaan, kepala_sekolah -> manajemen_sekolah
     * 3. Create teacher_cases table
     * 4. Create case_teacher_reports pivot table
     * 5. Create case_teacher_follow_ups table
     */
    public function up(): void
    {
        // Step 1: Change role column from ENUM to VARCHAR to allow new values
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'siswa'");

        // Step 2: Update existing user roles
        DB::statement("UPDATE users SET role = 'staf_kesiswaan' WHERE role = 'guru_bk'");
        DB::statement("UPDATE users SET role = 'manajemen_sekolah' WHERE role = 'kepala_sekolah'");

        // Step 3: Create teacher_cases table
        Schema::create('teacher_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade'); // Teacher being handled
            $table->foreignId('handler_id')->nullable()->constrained('users')->onDelete('set null'); // Who is handling
            $table->string('case_number', 50)->unique();
            $table->string('title', 200);
            $table->text('summary')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->text('resolution_notes')->nullable();
            $table->string('resolution_outcome', 50)->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index(['teacher_id', 'status']);
        });

        // Step 4: Create pivot table for linking reports to teacher cases
        Schema::create('case_teacher_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_case_id')->constrained('teacher_cases')->onDelete('cascade');
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['teacher_case_id', 'report_id']);
        });

        // Step 5: Create teacher case follow-ups table
        Schema::create('case_teacher_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_case_id')->constrained('teacher_cases')->onDelete('cascade');
            $table->foreignId('conducted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['meeting', 'call', 'home_visit', 'counseling', 'mediation', 'other'])->default('meeting');
            $table->date('follow_up_date');
            $table->text('notes');
            $table->text('outcome')->nullable();
            $table->string('next_action')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->timestamps();

            $table->index('teacher_case_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables
        Schema::dropIfExists('case_teacher_follow_ups');
        Schema::dropIfExists('case_teacher_reports');
        Schema::dropIfExists('teacher_cases');

        // Revert role names
        DB::statement("UPDATE users SET role = 'guru_bk' WHERE role = 'staf_kesiswaan'");
        DB::statement("UPDATE users SET role = 'kepala_sekolah' WHERE role = 'manajemen_sekolah'");
    }
};
