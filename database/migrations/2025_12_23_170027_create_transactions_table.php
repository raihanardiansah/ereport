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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained('subscription_packages')->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->string('snap_token')->nullable();
            $table->decimal('gross_amount', 15, 2);
            $table->string('payment_type')->nullable();
            $table->string('payment_method')->nullable(); // e.g., 'bca_va', 'bni_va', etc.
            $table->string('va_number')->nullable(); // Virtual Account number
            $table->string('bank')->nullable(); // Bank name (BCA, BNI, etc.)
            $table->string('transaction_status')->default('pending'); // pending, success, failed, expired
            $table->string('transaction_id')->nullable(); // Midtrans transaction ID
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expiry_time')->nullable(); // VA expiry time
            $table->text('midtrans_response')->nullable(); // Full response from Midtrans
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('transaction_status');
            $table->index(['school_id', 'transaction_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
