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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('code', 32)->unique(); // Unique QR code identifier
            $table->string('name'); // Display name (e.g., "Kelas 10A", "Kantin", "Lab Komputer")
            $table->string('location')->nullable(); // Physical location description
            $table->string('type')->default('general'); // 'classroom', 'facility', 'general'
            $table->json('metadata')->nullable(); // Additional data (class_id, building, etc)
            $table->integer('scan_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('school_id');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
