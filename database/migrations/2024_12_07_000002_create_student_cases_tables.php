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
        // Student cases - grouping reports about a specific student
        Schema::create('student_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // The student being tracked
            $table->foreignId('counselor_id')->nullable()->constrained('users')->onDelete('set null'); // Assigned BK teacher
            $table->string('case_number')->unique(); // e.g., CASE-2024-0001
            $table->string('title');
            $table->text('summary')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->text('resolution_notes')->nullable();
            $table->string('resolution_outcome')->nullable(); // e.g., 'improved', 'referred', 'monitored'
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index(['school_id', 'student_id']);
            $table->index(['school_id', 'status']);
        });

        // Case follow-ups - detailed tracking records
        Schema::create('case_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_case_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Who created the follow-up
            $table->date('follow_up_date');
            $table->enum('type', ['meeting', 'phone_call', 'home_visit', 'counseling', 'observation', 'referral', 'other'])->default('meeting');
            $table->text('notes');
            $table->text('action_taken')->nullable();
            $table->text('next_steps')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->timestamps();
            
            $table->index(['student_case_id', 'follow_up_date']);
        });

        // Link reports to cases
        Schema::create('case_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_case_id')->constrained()->onDelete('cascade');
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['student_case_id', 'report_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_reports');
        Schema::dropIfExists('case_follow_ups');
        Schema::dropIfExists('student_cases');
    }
};
