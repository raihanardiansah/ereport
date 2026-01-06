<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add additional fields to subscription_packages
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('is_active');
            $table->boolean('is_featured')->default(false)->after('sort_order');
            $table->string('badge_text')->nullable()->after('is_featured');
            $table->string('badge_color')->nullable()->after('badge_text');
        });

        // Create promotions table
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // Percentage or fixed amount
            $table->decimal('max_discount', 12, 2)->nullable(); // Cap for percentage discounts
            $table->decimal('min_purchase', 12, 2)->default(0); // Minimum purchase amount
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_per_user')->default(1); // Per user limit
            $table->integer('used_count')->default(0); // Track usage
            $table->json('applicable_packages')->nullable(); // Specific packages or null for all
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create promotion_usages table to track usage
        Schema::create('promotion_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('discount_amount', 12, 2);
            $table->timestamps();

            $table->index(['promotion_id', 'school_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
        Schema::dropIfExists('promotions');
        
        Schema::table('subscription_packages', function (Blueprint $table) {
            $table->dropColumn(['sort_order', 'is_featured', 'badge_text', 'badge_color']);
        });
    }
};
