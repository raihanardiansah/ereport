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
    public User $recipient;

    /**
     * Create a new job instance.
     */
    public function __construct(Report $report, User $recipient, int $initialDelay = 0)
    {
        $this->report = $report;
        $this->recipient = $recipient;
        
        // Set queue delay to respect rate limit
        if ($initialDelay > 0) {
            $this->delay($initialDelay);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->recipient->email)->send(new CriticalReportCreated($this->report));
        } catch (\Exception $e) {
            \Log::error('Failed to send critical report email', [
                'report_id' => $this->report->id,
                'recipient_email' => $this->recipient->email,
                'error' => $e->getMessage()
            ]);
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
