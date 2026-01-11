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
        // Badges table
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable(); // Emoji or icon path
            $table->string('color')->default('gray'); // Badge color theme
            $table->enum('criteria_type', ['report_count', 'consecutive_days', 'first_action', 'points_threshold', 'custom']);
            $table->integer('criteria_value')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User badges (earned)
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'badge_id']);
        });

        // Points history
        Schema::create('user_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->string('action'); // 'report_submitted', 'report_resolved', 'streak_bonus', etc.
            $table->text('description')->nullable();
            $table->morphs('pointable'); // Polymorphic relation to report, comment, etc.
            $table->timestamps();
        });

        // Add total_points to users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('total_points')->default(0)->after('remember_token');
            $table->unsignedInteger('current_streak')->default(0)->after('total_points');
            $table->date('last_activity_date')->nullable()->after('current_streak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_points', 'current_streak', 'last_activity_date']);
        });
        
        Schema::dropIfExists('user_points');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('badges');
    }
};
