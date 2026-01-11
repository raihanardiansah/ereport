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
        Schema::create('school_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('period', 7)->comment('Format: YYYY-MM');
            $table->unsignedInteger('total_reports')->default(0);
            $table->unsignedInteger('resolved_reports')->default(0);
            $table->unsignedInteger('escalated_reports')->default(0);
            $table->decimal('avg_resolution_hours', 8, 2)->default(0);
            $table->unsignedInteger('positive_count')->default(0);
            $table->unsignedInteger('negative_count')->default(0);
            $table->unsignedInteger('neutral_count')->default(0);
            $table->unsignedInteger('anonymous_reports')->default(0);
            $table->timestamps();

            $table->unique(['school_id', 'period']);
        });

        // Add benchmarking opt-in column to schools
        Schema::table('schools', function (Blueprint $table) {
            $table->boolean('allow_benchmarking')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_statistics');

        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('allow_benchmarking');
        });
    }
};
