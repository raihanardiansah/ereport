<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'npsn',
        'join_code',
        'phone',
        'address',
        'province',
        'city',
        'logo',
        'website',
        'settings',
        'subscription_status',
        'trial_ends_at',
        'has_used_trial',
        'trial_used_at',
        'allow_benchmarking',
    ];

    protected $casts = [
        'settings' => 'array',
        'trial_ends_at' => 'datetime',
        'trial_used_at' => 'datetime',
        'has_used_trial' => 'boolean',
        'allow_benchmarking' => 'boolean',
    ];

    /**
     * Bootstrap the model and its traits.
     */
    protected static function booted(): void
    {
        static::creating(function (School $school) {
            if (empty($school->join_code)) {
                $school->join_code = static::generateUniqueJoinCode();
            }
        });
    }

    /**
     * Generate a unique join code.
     */
    public static function generateUniqueJoinCode(): string
    {
        do {
            // Generate 6 character random string (uppercase letters and numbers)
            $code = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
        } while (static::where('join_code', $code)->exists());

        return $code;
    }

    /**
     * Get all users belonging to this school.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get teachers of this school.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(User::class)->whereIn('role', ['guru', 'staf_kesiswaan']);
    }

    /**
     * Get students of this school.
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'siswa');
    }

    /**
     * Get all subscriptions for this school.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if subscription is active.
     */
    public function isSubscriptionActive(): bool
    {
        return in_array($this->subscription_status, ['trial', 'active']);
    }

    /**
     * Get the theme settings.
     */
    public function getTheme(): array
    {
        return $this->settings['theme'] ?? [
            'primary_color' => '#22c55e',
            'secondary_color' => '#3b82f6',
        ];
    }

    /**
     * Check if school is eligible for trial package.
     * Eligible if: within 7 days of registration AND hasn't used trial before.
     */
    public function isEligibleForTrial(): bool
    {
        // Already used trial flag
        if ($this->has_used_trial) {
            return false;
        }

        // Check if any trial subscription exists in history (fallback for existing data)
        $hasTrialHistory = $this->subscriptions()
            ->where('payment_method', 'trial')
            ->exists();

        if ($hasTrialHistory) {
            // Self-heal: mark as used
            $this->markTrialAsUsed();
            return false;
        }

        // Check if within 7 days of registration
        $daysSinceRegistration = $this->getDaysSinceRegistration();
        return $daysSinceRegistration <= 7;
    }

    /**
     * Check if school has used trial before.
     */
    public function hasUsedTrial(): bool
    {
        return $this->has_used_trial;
    }

    /**
     * Get days since school registration.
     */
    public function getDaysSinceRegistration(): int
    {
        return (int) $this->created_at->diffInDays(now());
    }

    /**
     * Mark trial as used.
     */
    public function markTrialAsUsed(): void
    {
        $this->update([
            'has_used_trial' => true,
            'trial_used_at' => now(),
        ]);
    }

    /**
     * Get days remaining for trial eligibility.
     * Returns 0 if already used trial or past 7 days.
     */
    public function getTrialEligibilityDaysRemaining(): int
    {
        if ($this->has_used_trial) {
            return 0;
        }

        $daysSinceRegistration = $this->getDaysSinceRegistration();
        return max(0, 7 - $daysSinceRegistration);
    }
}
