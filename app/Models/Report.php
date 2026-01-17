<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'reported_user_id',
        'assigned_to',
        'title',
        'content',
        'category',
        'attachment_path',
        'ai_classification',
        'manual_classification',
        'ai_category',
        'manual_category',
        'status',
        'escalated_at',
        'escalation_level',
        'is_anonymous',
        'device_fingerprint',
        'urgency',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'escalated_at' => 'datetime',
        'escalation_level' => 'integer',
        'is_anonymous' => 'boolean',
    ];

    /**
     * Get the school this report belongs to.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the user who created this report (the reporter).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user() - the person who made the report.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user being reported about (the subject of the report).
     */
    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    /**
     * Get the user assigned to handle this report.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Check if report is assigned to someone.
     */
    public function isAssigned(): bool
    {
        return $this->assigned_to !== null;
    }

    /**
     * Get linked student cases.
     */
    public function studentCases(): BelongsToMany
    {
        return $this->belongsToMany(StudentCase::class, 'case_reports')
            ->withTimestamps();
    }

    /**
     * Alias for studentCases (backward compatibility).
     */
    public function cases(): BelongsToMany
    {
        return $this->studentCases();
    }

    /**
     * Get linked teacher cases.
     */
    public function teacherCases(): BelongsToMany
    {
        return $this->belongsToMany(TeacherCase::class, 'case_teacher_reports')
            ->withTimestamps();
    }

    /**
     * Get comments on this report.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ReportComment::class);
    }

    /**
     * Get current classification (manual takes precedence over AI).
     */
    public function getClassification(): ?string
    {
        return $this->manual_classification ?? $this->ai_classification;
    }

    /**
     * Get current category (manual takes precedence over AI, then fallback to original).
     */
    public function getCategory(): ?string
    {
        return $this->manual_category ?? $this->ai_category ?? $this->category;
    }

    /**
     * Check if classification was manually corrected.
     */
    public function isClassificationCorrected(): bool
    {
        return $this->manual_classification !== null;
    }

    /**
     * Check if category was manually corrected.
     */
    public function isCategoryCorrected(): bool
    {
        return $this->manual_category !== null;
    }

    /**
     * Check if report is editable.
     */
    public function isEditable(): bool
    {
        return $this->status === 'dikirim';
    }

    /**
     * Get status badge color.
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'dikirim' => 'gray',
            'diproses' => 'yellow',
            'ditindaklanjuti' => 'blue',
            'selesai' => 'green',
            default => 'gray',
        };
    }

    /**
     * Get all accused users (many-to-many relationship).
     * This allows multiple accused per report.
     */
    public function accusedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'report_accused', 'report_id', 'accused_user_id')
            ->withTimestamps();
    }

    /**
     * Check if report has any accused users.
     */
    public function hasAccused(): bool
    {
        return $this->accusedUsers()->exists();
    }

    /**
     * Check if a specific user is accused in this report.
     */
    public function isAccused(int $userId): bool
    {
        return $this->accusedUsers()->where('users.id', $userId)->exists();
    }

    /**
     * Update indexes for all accused users when report is completed.
     * Only call this when status changes to 'selesai'.
     */
    public function updateAccusedIndexes(): void
    {
        // Only process if report has accused users
        if (!$this->hasAccused()) {
            return;
        }

        // Get classification (manual takes precedence over AI)
        $classification = $this->manual_classification ?? $this->ai_classification ?? 'netral';

        // Update each accused user's index
        foreach ($this->accusedUsers as $accusedUser) {
            match($classification) {
                'positif' => $accusedUser->increment('positive_index'),
                'negatif' => $accusedUser->increment('negative_index'),
                default => $accusedUser->increment('neutral_index'),
            };
        }
    }

    /**
     * Check if any accused is a teacher (for visibility filtering).
     */
    public function hasTeacherAccused(): bool
    {
        return $this->accusedUsers()
            ->whereIn('role', ['guru', 'staf_kesiswaan'])
            ->exists();
    }

    /**
     * Check if any accused is a student (for visibility filtering).
     */
    public function hasStudentAccused(): bool
    {
        return $this->accusedUsers()
            ->where('role', 'siswa')
            ->exists();
    }

    /**
     * Check if report is escalated.
     */
    public function isEscalated(): bool
    {
        return $this->escalation_level > 0;
    }

    /**
     * Get escalation badge HTML for UI.
     */
    public function getEscalationBadgeAttribute(): ?string
    {
        if ($this->escalation_level === 0) {
            return null;
        }

        return match ($this->escalation_level) {
            1 => '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">⚠️ Eskalasi Lv.1</span>',
            2 => '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">🚨 Eskalasi Lv.2</span>',
            default => null,
        };
    }

    /**
     * Get escalation level label.
     */
    public function getEscalationLabelAttribute(): ?string
    {
        return match ($this->escalation_level) {
            0 => null,
            1 => 'Eskalasi ke Staf Kesiswaan',
            2 => 'Eskalasi ke Kepala Sekolah',
            default => null,
        };
    }

    /**
     * Get hours since report was created.
     */
    public function getHoursPendingAttribute(): int
    {
        return (int) now()->diffInHours($this->created_at);
    }
}
