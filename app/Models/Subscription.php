<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'school_id',
        'package_id',
        'status',
        'starts_at',
        'expires_at',
        'amount_paid',
        'payment_method',
        'payment_reference',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'package_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               $this->expires_at && 
               $this->expires_at->isFuture();
    }

    /**
     * Get remaining days.
     */
    public function getRemainingDaysAttribute(): int
    {
        if (!$this->expires_at) return 0;
        return max(0, Carbon::now()->diffInDays($this->expires_at, false));
    }

    /**
     * Get status badge class.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-700',
            'pending' => 'bg-yellow-100 text-yellow-700',
            'expired' => 'bg-red-100 text-red-700',
            'cancelled' => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Check if subscription is expiring within 1 day (H-1).
     */
    public function isExpiringWithinOneDay(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        $hoursUntilExpiry = now()->diffInHours($this->expires_at, false);
        return $hoursUntilExpiry <= 24 && $hoursUntilExpiry > 0;
    }

    /**
     * Check if can downgrade to a package.
     * Downgrade is allowed only if subscription is expiring within 1 day (H-1).
     */
    public function canDowngradeTo(SubscriptionPackage $newPackage): bool
    {
        // If not active or no current package, allow
        if (!$this->isActive() || !$this->package) {
            return true;
        }

        // If it's an upgrade or same price, allow
        if ($newPackage->price >= $this->package->price) {
            return true;
        }

        // If it's a downgrade, only allow if H-1 (expiring within 1 day)
        return $this->isExpiringWithinOneDay();
    }

    /**
     * Check if selecting a package would be a downgrade.
     */
    public function isDowngrade(SubscriptionPackage $newPackage): bool
    {
        if (!$this->package) {
            return false;
        }

        return $newPackage->price < $this->package->price;
    }

    /**
     * Check if can upgrade to a package.
     */
    public function canUpgradeTo(SubscriptionPackage $newPackage): bool
    {
        // Upgrades are always allowed
        if (!$this->package) {
            return true;
        }

        return $newPackage->price >= $this->package->price;
    }

    /**
     * Get reason why downgrade is not allowed.
     */
    public function getDowngradeBlockReason(): ?string
    {
        if (!$this->isActive()) {
            return null;
        }

        if ($this->isExpiringWithinOneDay()) {
            return null;
        }

        $expiryDate = $this->expires_at->format('d M Y');
        return "Downgrade tidak diperbolehkan sampai masa berlaku habis ({$expiryDate}). Anda dapat downgrade mulai H-1 (1 hari sebelum berakhir).";
    }
}
