<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'user_id',
        'school_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the report that this audit log belongs to.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Log an action on a report.
     */
    public static function logAction(
        Report $report,
        User $user,
        string $action,
        ?string $description = null,
        ?array $metadata = null
    ): self {
        return self::create([
            'report_id' => $report->id,
            'user_id' => $user->id,
            'school_id' => $report->school_id,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Check if report should be audited (critical or high urgency).
     */
    public static function shouldAudit(Report $report): bool
    {
        return in_array($report->urgency, ['critical', 'high']);
    }
}
