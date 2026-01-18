<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportEscalatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Report $report;
    public int $hoursPending;

    /**
     * Create a new message instance.
     */
    public function __construct(Report $report, int $hoursPending)
    {
        $this->report = $report;
        $this->hoursPending = $hoursPending;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Eskalasi: Laporan Belum Ditangani - ' . $this->report->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.report-escalated',
            with: [
                'report' => $this->report,
                'hoursPending' => $this->hoursPending,
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
