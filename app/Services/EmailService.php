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
                ->whereIn('role', ['admin_sekolah', 'staf_kesiswaan'])
                ->where('id', '!=', $report->user_id) // Don't notify the reporter
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();

            if (empty($recipients)) {
                return;
            }

            foreach ($recipients as $email) {
                Mail::to($email)->queue(new ReportSubmittedMail($report));
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
                Mail::to($creator->email)->queue(
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
                Mail::to($admin->email)->queue(
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

            Mail::to($admin->email)->queue(
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
}
