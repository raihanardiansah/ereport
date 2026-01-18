<?php

namespace App\Services;

use App\Mail\ReportSubmittedMail;
use App\Mail\ReportStatusChangedMail;
use App\Mail\SubscriptionExpiringMail;
use App\Mail\WeeklyDigestMail;
use App\Models\Report;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send email notification for new report to admins and BK teachers.
     */
    public static function notifyReportSubmitted(Report $report): void
    {
        try {
            // Get school's admin and BK staff emails
            $recipients = User::where('school_id', $report->school_id)
                ->whereIn('role', ['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])
                ->where('id', '!=', $report->user_id) // Don't notify the reporter
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();

            if (empty($recipients)) {
                return;
            }

            foreach ($recipients as $email) {
                Mail::to($email)->send(new ReportSubmittedMail($report));
            }

            Log::info('Report submitted emails sent', [
                'report_id' => $report->id,
                'recipients_count' => count($recipients)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send report submitted email', [
                'report_id' => $report->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email notification when report status changes.
     */
    public static function notifyReportStatusChanged(Report $report, string $oldStatus, string $newStatus): void
    {
        try {
            // Notify the report creator
            $creator = $report->user;
            
            if ($creator && $creator->email) {
                Mail::to($creator->email)->send(
                    new ReportStatusChangedMail($report, $oldStatus, $newStatus)
                );

                Log::info('Report status changed email sent', [
                    'report_id' => $report->id,
                    'recipient' => $creator->email,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send report status changed email', [
                'report_id' => $report->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send subscription expiring warning emails.
     */
    public static function notifySubscriptionExpiring(Subscription $subscription, int $daysRemaining): void
    {
        try {
            // Get school admin email
            $admin = User::where('school_id', $subscription->school_id)
                ->where('role', 'admin_sekolah')
                ->whereNotNull('email')
                ->first();

            if ($admin && $admin->email) {
                Mail::to($admin->email)->send(
                    new SubscriptionExpiringMail($subscription, $daysRemaining)
                );

                Log::info('Subscription expiring email sent', [
                    'subscription_id' => $subscription->id,
                    'days_remaining' => $daysRemaining,
                    'recipient' => $admin->email
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send subscription expiring email', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send weekly digest email to school admin.
     */
    public static function sendWeeklyDigest(School $school): void
    {
        try {
            $admin = User::where('school_id', $school->id)
                ->where('role', 'admin_sekolah')
                ->whereNotNull('email')
                ->first();

            if (!$admin || !$admin->email) {
                return;
            }

            // Calculate weekly stats
            $weekStart = now()->subWeek();
            
            $stats = [
                'total_reports' => Report::where('school_id', $school->id)
                    ->where('created_at', '>=', $weekStart)
                    ->count(),
                'completed_reports' => Report::where('school_id', $school->id)
                    ->where('created_at', '>=', $weekStart)
                    ->where('status', 'selesai')
                    ->count(),
                'pending_reports' => Report::where('school_id', $school->id)
                    ->whereIn('status', ['dikirim', 'diproses'])
                    ->count(),
                'negative_reports' => Report::where('school_id', $school->id)
                    ->where('created_at', '>=', $weekStart)
                    ->where(function ($q) {
                        $q->where('manual_classification', 'negatif')
                          ->orWhere(function ($q2) {
                              $q2->whereNull('manual_classification')
                                 ->where('ai_classification', 'negatif');
                          });
                    })
                    ->count(),
            ];

            $recentReports = Report::where('school_id', $school->id)
                ->where('created_at', '>=', $weekStart)
                ->with('user')
                ->latest()
                ->take(5)
                ->get();

            Mail::to($admin->email)->send(
                new WeeklyDigestMail($school, $stats, $recentReports)
            );

            Log::info('Weekly digest sent', [
                'school_id' => $school->id,
                'recipient' => $admin->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send weekly digest', [
                'school_id' => $school->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email notification when report is assigned to a user.
     */
    public static function notifyReportAssigned(Report $report, User $assignedUser): void
    {
        try {
            if (!$assignedUser->email) {
                return;
            }

            Mail::to($assignedUser->email)->send(
                new \App\Mail\ReportAssignedMail($report, $assignedUser)
            );

            Log::info('Report assigned email sent', [
                'report_id' => $report->id,
                'recipient' => $assignedUser->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send report assigned email', [
                'report_id' => $report->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email notification when report is escalated due to no response.
     */
    public static function notifyReportEscalated(Report $report, int $hoursPending): void
    {
        try {
            // Send to manajemen_sekolah and admin_sekolah
            $recipients = User::where('school_id', $report->school_id)
                ->whereIn('role', ['admin_sekolah', 'manajemen_sekolah'])
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();

            if (empty($recipients)) {
                return;
            }

            foreach ($recipients as $email) {
                Mail::to($email)->send(
                    new \App\Mail\ReportEscalatedMail($report, $hoursPending)
                );
            }

            Log::info('Report escalated emails sent', [
                'report_id' => $report->id,
                'recipients_count' => count($recipients),
                'hours_pending' => $hoursPending
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send report escalated email', [
                'report_id' => $report->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send email notification when someone comments on a report.
     */
    public static function notifyReportComment(Report $report, \App\Models\ReportComment $comment): void
    {
        try {
            // Notify the report creator (if not the commenter and comment is not private)
            if (!$comment->is_private && $report->user_id !== $comment->user_id && $report->user->email) {
                Mail::to($report->user->email)->send(
                    new \App\Mail\ReportCommentMail($report, $comment)
                );

                Log::info('Report comment email sent to creator', [
                    'report_id' => $report->id,
                    'comment_id' => $comment->id,
                    'recipient' => $report->user->email
                ]);
            }

            // Also notify assigned user if exists and is not the commenter
            if ($report->assigned_to && 
                $report->assigned_to !== $comment->user_id && 
                $report->assignedTo->email) {
                
                Mail::to($report->assignedTo->email)->send(
                    new \App\Mail\ReportCommentMail($report, $comment)
                );

                Log::info('Report comment email sent to assigned user', [
                    'report_id' => $report->id,
                    'comment_id' => $comment->id,
                    'recipient' => $report->assignedTo->email
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send report comment email', [
                'report_id' => $report->id,
                'comment_id' => $comment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
