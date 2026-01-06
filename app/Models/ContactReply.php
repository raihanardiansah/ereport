<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReply extends Model
{
    protected $fillable = [
        'contact_message_id',
        'user_id',
        'message',
        'is_admin_reply',
    ];

    public function contactMessage()
    {
        return $this->belongsTo(ContactMessage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
