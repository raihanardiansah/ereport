<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'action',
        'description',
        'pointable_type',
        'pointable_id',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    /**
     * User who earned these points.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic relation to the source (report, comment, etc.).
     */
    public function pointable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get human-readable action label.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'report_submitted' => 'Mengirim Laporan',
            'report_resolved' => 'Laporan Selesai Ditangani',
            'streak_bonus' => 'Bonus Aktivitas Harian',
            'first_report' => 'Laporan Pertama',
            'badge_earned' => 'Mendapat Badge',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    /**
     * Get points display with sign.
     */
    public function getPointsDisplayAttribute(): string
    {
        $sign = $this->points >= 0 ? '+' : '';
        return $sign . $this->points;
    }
}
