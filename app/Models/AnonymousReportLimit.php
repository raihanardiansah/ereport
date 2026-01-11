<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonymousReportLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_fingerprint',
        'ip_address',
        'daily_count',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'daily_count' => 'integer',
    ];

    /**
     * Check if device/IP can submit anonymous report today.
     * Max 3 anonymous reports per device per day.
     */
    public static function canSubmitAnonymous(string $fingerprint, string $ip): bool
    {
        $limit = self::where('device_fingerprint', $fingerprint)
            ->where('date', now()->toDateString())
            ->first();

        if (!$limit) {
            return true;
        }

        return $limit->daily_count < 3;
    }

    /**
     * Increment the daily count for a device.
     */
    public static function incrementCount(string $fingerprint, string $ip): void
    {
        self::updateOrCreate(
            [
                'device_fingerprint' => $fingerprint,
                'date' => now()->toDateString(),
            ],
            [
                'ip_address' => $ip,
            ]
        )->increment('daily_count');
    }

    /**
     * Get remaining anonymous reports for today.
     */
    public static function getRemainingCount(string $fingerprint): int
    {
        $limit = self::where('device_fingerprint', $fingerprint)
            ->where('date', now()->toDateString())
            ->first();

        return 3 - ($limit?->daily_count ?? 0);
    }
}
