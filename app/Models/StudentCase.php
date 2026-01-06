<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudentCase extends Model
{
    protected $fillable = [
        'school_id',
        'student_id',
        'counselor_id',
        'case_number',
        'title',
        'summary',
        'priority',
        'status',
        'resolution_notes',
        'resolution_outcome',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Priority options with labels and colors.
     */
    public const PRIORITIES = [
        'low' => ['label' => 'Rendah', 'color' => 'bg-gray-100 text-gray-700'],
        'medium' => ['label' => 'Sedang', 'color' => 'bg-yellow-100 text-yellow-700'],
        'high' => ['label' => 'Tinggi', 'color' => 'bg-orange-100 text-orange-700'],
        'critical' => ['label' => 'Kritis', 'color' => 'bg-red-100 text-red-700'],
    ];

    /**
     * Status options with labels and colors.
     */
    public const STATUSES = [
        'open' => ['label' => 'Terbuka', 'color' => 'bg-blue-100 text-blue-700'],
        'in_progress' => ['label' => 'Dalam Penanganan', 'color' => 'bg-yellow-100 text-yellow-700'],
        'resolved' => ['label' => 'Terselesaikan', 'color' => 'bg-green-100 text-green-700'],
        'closed' => ['label' => 'Ditutup', 'color' => 'bg-gray-100 text-gray-700'],
    ];

    /**
     * Resolution outcomes.
     */
    public const OUTCOMES = [
        'improved' => 'Perilaku Membaik',
        'referred' => 'Dirujuk ke Spesialis',
        'monitored' => 'Masih Dipantau',
        'transferred' => 'Pindah Sekolah',
        'graduated' => 'Lulus/Naik Kelas',
        'other' => 'Lainnya',
    ];

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($case) {
            if (empty($case->case_number)) {
                $case->case_number = static::generateCaseNumber($case->school_id);
            }
        });
    }

    /**
     * Generate unique case number.
     * Format: CASE-{school_id}-{year}-{sequential_number}
     */
    public static function generateCaseNumber($schoolId): string
    {
        $year = now()->year;
        $count = static::where('school_id', $schoolId)
            ->whereYear('created_at', $year)
            ->count() + 1;
        
        return sprintf('CASE-%d-%d-%04d', $schoolId, $year, $count);
    }

    /**
     * Get the school.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the assigned counselor.
     */
    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    /**
     * Get follow-ups.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(CaseFollowUp::class);
    }

    /**
     * Get linked reports.
     */
    public function reports(): BelongsToMany
    {
        return $this->belongsToMany(Report::class, 'case_reports')
            ->withTimestamps();
    }

    /**
     * Get priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority]['label'] ?? $this->priority;
    }

    /**
     * Get priority color class.
     */
    public function getPriorityColorAttribute(): string
    {
        return self::PRIORITIES[$this->priority]['color'] ?? 'bg-gray-100 text-gray-700';
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? $this->status;
    }

    /**
     * Get status color class.
     */
    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'bg-gray-100 text-gray-700';
    }

    /**
     * Get outcome label.
     */
    public function getOutcomeLabelAttribute(): ?string
    {
        return self::OUTCOMES[$this->resolution_outcome] ?? $this->resolution_outcome;
    }

    /**
     * Check if case is resolved or closed.
     */
    public function isResolved(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    /**
     * Get timeline events (reports + follow-ups combined).
     */
    public function getTimelineAttribute()
    {
        $events = collect();

        // Add reports
        foreach ($this->reports as $report) {
            $events->push([
                'type' => 'report',
                'date' => $report->created_at,
                'title' => $report->title,
                'data' => $report,
            ]);
        }

        // Add follow-ups
        foreach ($this->followUps as $followUp) {
            $events->push([
                'type' => 'follow_up',
                'date' => $followUp->follow_up_date,
                'title' => $followUp->type_label,
                'data' => $followUp,
            ]);
        }

        return $events->sortByDesc('date')->values();
    }
}
