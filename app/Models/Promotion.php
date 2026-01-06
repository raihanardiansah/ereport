<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'max_discount',
        'min_purchase',
        'usage_limit',
        'usage_per_user',
        'used_count',
        'applicable_packages',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'applicable_packages' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Type constants
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    /**
     * Get promotion usages.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }

    /**
     * Check if promotion is currently valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        
        $now = Carbon::now();
        
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->expires_at && $now->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        return true;
    }

    /**
     * Check if promotion can be used by a school.
     */
    public function canBeUsedBy($schoolId): bool
    {
        if (!$this->isValid()) return false;
        
        // Check per-user limit
        $schoolUsage = $this->usages()->where('school_id', $schoolId)->count();
        if ($schoolUsage >= $this->usage_per_user) return false;
        
        return true;
    }

    /**
     * Check if promotion applies to a package.
     */
    public function appliesToPackage($packageId): bool
    {
        if (empty($this->applicable_packages)) return true;
        return in_array($packageId, $this->applicable_packages);
    }

    /**
     * Calculate discount for an amount.
     */
    public function calculateDiscount($amount): float
    {
        if ($amount < $this->min_purchase) return 0;
        
        if ($this->type === self::TYPE_PERCENTAGE) {
            $discount = $amount * ($this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }
        
        return min($this->value, $amount);
    }

    /**
     * Record usage of this promotion.
     */
    public function recordUsage($schoolId, $subscriptionId, $discountAmount): void
    {
        $this->usages()->create([
            'school_id' => $schoolId,
            'subscription_id' => $subscriptionId,
            'discount_amount' => $discountAmount,
        ]);
        
        $this->increment('used_count');
    }

    /**
     * Get formatted discount value.
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->type === self::TYPE_PERCENTAGE) {
            return $this->value . '%';
        }
        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }

    /**
     * Get status label.
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) return 'Nonaktif';
        if (!$this->isValid()) return 'Kedaluwarsa';
        return 'Aktif';
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        if (!$this->is_active) return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400';
        if (!$this->isValid()) return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
        return 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
    }

    /**
     * Scope for active promotions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }

    /**
     * Scope for valid promotions (not exceeded usage).
     */
    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('used_count', '<', 'usage_limit');
            });
    }
}
