<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Check if notification is read.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Mark as read.
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Get icon based on type.
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'report_submitted' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'report_updated' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'subscription_expiring' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'payment_received' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            default => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        };
    }

    /**
     * Create notification for report submission.
     */
    public static function notifyReportSubmitted(Report $report): void
    {
        // Notify school admins and BK teachers
        $users = User::where('school_id', $report->school_id)
            ->whereIn('role', ['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])
            ->where('id', '!=', $report->user_id)
            ->get();

        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'school_id' => $report->school_id,
                'type' => 'report_submitted',
                'title' => 'Laporan Baru',
                'message' => "Laporan '{$report->title}' telah dikirim oleh {$report->user->name}",
                'data' => ['report_id' => $report->id],
            ]);
        }
    }
}
