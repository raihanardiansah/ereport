<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'source',
        'channel',
        'type',
        'name',
        'email',
        'phone',
        'school_name',
        'subject',
        'message',
        'status',
        'admin_notes',
        'replied_by',
        'reply_message',
        'read_at',
        'replied_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    // Status constants
    const STATUS_UNREAD = 'unread';
    const STATUS_READ = 'read';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_REPLIED = 'replied';
    const STATUS_CLOSED = 'closed';

    // Channel constants
    const CHANNEL_WEB = 'web';
    const CHANNEL_EMAIL = 'email';
    const CHANNEL_WHATSAPP = 'whatsapp';

    // Source constants
    const SOURCE_LANDING = 'landing_page';
    const SOURCE_APP = 'in_app';

    // Type labels in Indonesian
    const TYPE_LABELS = [
        'inquiry' => 'Pertanyaan',
        'support' => 'Dukungan Teknis',
        'feedback' => 'Masukan',
        'complaint' => 'Keluhan',
        'other' => 'Lainnya',
    ];

    // Status labels in Indonesian
    const STATUS_LABELS = [
        'unread' => 'Belum Dibaca',
        'read' => 'Sudah Dibaca',
        'in_progress' => 'Sedang Diproses',
        'replied' => 'Sudah Dibalas',
        'closed' => 'Ditutup',
    ];

    // Channel labels
    const CHANNEL_LABELS = [
        'web' => 'Web Form',
        'email' => 'Email',
        'whatsapp' => 'WhatsApp',
    ];

    /**
     * Get the user who sent the message (if logged in).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the school associated with the message.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the replies for the message.
     */
    public function replies()
    {
        return $this->hasMany(ContactReply::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the admin who replied to the message.
     */
    public function repliedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    /**
     * Get type label in Indonesian.
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    /**
     * Get status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    /**
     * Get channel label.
     */
    public function getChannelLabelAttribute(): string
    {
        return self::CHANNEL_LABELS[$this->channel] ?? $this->channel;
    }

    /**
     * Get status color for badge.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'unread' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            'read' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'replied' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            'closed' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Get channel color for badge.
     */
    public function getChannelColorAttribute(): string
    {
        return match($this->channel) {
            'web' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            'email' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'whatsapp' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Check if message is unread.
     */
    public function isUnread(): bool
    {
        return $this->status === self::STATUS_UNREAD;
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(): void
    {
        if ($this->status === self::STATUS_UNREAD) {
            $this->update([
                'status' => self::STATUS_READ,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Scope for unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    /**
     * Scope for messages from landing page.
     */
    public function scopeFromLandingPage($query)
    {
        return $query->where('source', self::SOURCE_LANDING);
    }

    /**
     * Scope for in-app messages.
     */
    public function scopeFromApp($query)
    {
        return $query->where('source', self::SOURCE_APP);
    }
}
