<?php

namespace App\Jobs;

use App\Mail\CriticalReportCreated;
use App\Models\Report;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCriticalReportEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Report $report;

    /**
     * Create a new job instance.
     */
    public function __construct(Report $report, int $initialDelay = 0)
    {
        $this->report = $report;
        
        // Set queue delay to respect rate limit (500ms between emails)
        if ($initialDelay > 0) {
            $this->delay($initialDelay);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all admin users from the same school
        $adminRoles = ['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'];
        
        $admins = User::where('school_id', $this->report->school_id)
            ->whereIn('role', $adminRoles)
            ->whereNotNull('email')
            ->get();

        // Send email to each admin
        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new CriticalReportCreated($this->report));
            } catch (\Exception $e) {
                \Log::error('Failed to send critical report email', [
                    'report_id' => $this->report->id,
                    'admin_email' => $admin->email,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        \Log::error('Critical report email job failed', [
            'report_id' => $this->report->id,
            'error' => $exception->getMessage()
        ]);
    }
}
