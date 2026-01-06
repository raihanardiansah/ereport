<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'school_id',
        'package_id',
        'subscription_id',
        'order_id',
        'snap_token',
        'gross_amount',
        'payment_type',
        'payment_method',
        'va_number',
        'bank',
        'transaction_status',
        'transaction_id',
        'transaction_time',
        'settlement_time',
        'expiry_time',
        'midtrans_response',
        'qr_code_url',
        'qr_string',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expiry_time' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'package_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->transaction_status === 'pending';
    }

    /**
     * Check if transaction is successful.
     */
    public function isSuccess(): bool
    {
        return $this->transaction_status === 'success';
    }

    /**
     * Check if transaction has expired.
     */
    public function isExpired(): bool
    {
        return $this->transaction_status === 'expired' || 
               ($this->expiry_time && $this->expiry_time->isPast());
    }

    /**
     * Get formatted VA number for display.
     */
    public function getFormattedVaNumberAttribute(): string
    {
        if (!$this->va_number) return '';
        
        // Format VA number with spaces every 4 digits
        return chunk_split($this->va_number, 4, ' ');
    }

    /**
     * Get bank name in uppercase.
     */
    public function getBankNameAttribute(): string
    {
        return strtoupper($this->bank ?? '');
    }
}
