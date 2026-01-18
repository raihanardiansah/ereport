<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $recipientEmail;
    public Mailable $mailable;

    /**
     * Create a new job instance.
     */
    public function __construct(string $recipientEmail, Mailable $mailable, int $delaySeconds = 0)
    {
        $this->recipientEmail = $recipientEmail;
        $this->mailable = $mailable;

        // Set queue delay to respect rate limit (2 seconds between emails)
        if ($delaySeconds > 0) {
            $this->delay($delaySeconds);
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->recipientEmail)->send($this->mailable);
        } catch (\Exception $e) {
            \Log::error('Failed to send email', [
                'recipient' => $this->recipientEmail,
                'mailable' => get_class($this->mailable),
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        \Log::error('Email job failed', [
            'recipient' => $this->recipientEmail,
            'mailable' => get_class($this->mailable),
            'error' => $exception->getMessage()
        ]);
    }
}
