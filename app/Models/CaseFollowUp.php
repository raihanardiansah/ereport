<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseFollowUp extends Model
{
    protected $fillable = [
        'student_case_id',
        'user_id',
        'follow_up_date',
        'type',
        'notes',
        'action_taken',
        'next_steps',
        'next_follow_up_date',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
        'next_follow_up_date' => 'date',
    ];

    /**
     * Follow-up types with labels and icons.
     */
    public const TYPES = [
        'meeting' => ['label' => 'Pertemuan', 'icon' => '👥'],
        'phone_call' => ['label' => 'Telepon', 'icon' => '📞'],
        'home_visit' => ['label' => 'Kunjungan Rumah', 'icon' => '🏠'],
        'counseling' => ['label' => 'Konseling', 'icon' => '💬'],
        'observation' => ['label' => 'Observasi', 'icon' => '👁️'],
        'referral' => ['label' => 'Rujukan', 'icon' => '📋'],
        'other' => ['label' => 'Lainnya', 'icon' => '📝'],
    ];

    /**
     * Get the student case.
     */
    public function studentCase(): BelongsTo
    {
        return $this->belongsTo(StudentCase::class);
    }

    /**
     * Get the user who created this follow-up.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type]['label'] ?? $this->type;
    }

    /**
     * Get type icon.
     */
    public function getTypeIconAttribute(): string
    {
        return self::TYPES[$this->type]['icon'] ?? '📝';
    }

    /**
     * Check if this follow-up is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->next_follow_up_date && $this->next_follow_up_date->isPast();
    }
}
