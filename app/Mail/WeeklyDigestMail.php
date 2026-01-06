<?php

namespace App\Mail;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public School $school;
    public array $stats;
    public $recentReports;

    /**
     * Create a new message instance.
     */
    public function __construct(School $school, array $stats, $recentReports)
    {
        $this->school = $school;
        $this->stats = $stats;
        $this->recentReports = $recentReports;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[e-Report] Ringkasan Mingguan - ' . $this->school->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-digest',
        );
    }
}
