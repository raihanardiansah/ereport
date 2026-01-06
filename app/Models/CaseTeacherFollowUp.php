<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseTeacherFollowUp extends Model
{
    protected $table = 'case_teacher_follow_ups';

    protected $fillable = [
        'teacher_case_id',
        'conducted_by',
        'type',
        'follow_up_date',
        'notes',
        'outcome',
        'next_action',
        'next_follow_up_date',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
        'next_follow_up_date' => 'date',
    ];

    /**
     * Follow-up types.
     */
    public const TYPES = [
        'meeting' => 'Rapat',
        'call' => 'Telepon',
        'home_visit' => 'Kunjungan Rumah',
        'counseling' => 'Konseling',
        'mediation' => 'Mediasi',
        'other' => 'Lainnya',
    ];

    /**
     * Get the teacher case.
     */
    public function teacherCase(): BelongsTo
    {
        return $this->belongsTo(TeacherCase::class);
    }

    /**
     * Get the user who conducted the follow-up.
     */
    public function conductor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }

    /**
     * Get type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
