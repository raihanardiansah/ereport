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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('npsn', 20)->unique()->nullable(); // Nomor Pokok Sekolah Nasional
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('province', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->json('settings')->nullable(); // Theme customization, etc.
            $table->enum('subscription_status', ['trial', 'active', 'expired', 'suspended'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
