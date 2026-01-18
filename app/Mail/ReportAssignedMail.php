<?php

namespace App\Mail;

use App\Models\Report;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Report $report;
    public User $assignedUser;

    /**
     * Create a new message instance.
     */
    public function __construct(Report $report, User $assignedUser)
    {
        $this->report = $report;
        $this->assignedUser = $assignedUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Laporan Ditugaskan kepada Anda - ' . $this->report->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.report-assigned',
            with: [
                'report' => $this->report,
                'assignedUser' => $this->assignedUser,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
