<?php

namespace App\Mail;

use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportCommentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Report $report;
    public ReportComment $comment;

    /**
     * Create a new message instance.
     */
    public function __construct(Report $report, ReportComment $comment)
    {
        $this->report = $report;
        $this->comment = $comment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Komentar Baru pada Laporan - ' . $this->report->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.report-comment',
            with: [
                'report' => $this->report,
                'comment' => $this->comment,
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
