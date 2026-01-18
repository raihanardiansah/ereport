<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\ContactReply;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    // Contact information constants
    const SUPPORT_EMAIL = 'support@ereport.systems';
    const SUPPORT_WHATSAPP = '+628990772526';
    const SUPPORT_WHATSAPP_FORMATTED = '628990772526';

    /**
     * Handle landing page contact form submission.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'school_name' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        try {
            ContactMessage::create([
                'source' => ContactMessage::SOURCE_LANDING,
                'channel' => ContactMessage::CHANNEL_WEB,
                'type' => 'inquiry',
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['whatsapp'] ?? null,
                'school_name' => $validated['school_name'] ?? null,
                'message' => $validated['message'],
                'status' => ContactMessage::STATUS_UNREAD,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Pesan Anda telah terkirim. Tim kami akan segera menghubungi Anda.',
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan. Silakan coba lagi atau hubungi kami langsung.',
            ], 500);
        }
    }

    /**
     * Display the support contact page for School Admin.
     */
    public function supportPage()
    {
        $user = Auth::user();

        // Get user's previous messages
        $myMessages = ContactMessage::where('user_id', $user->id)
            ->with(['replies', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('contact.support', [
            'user' => $user,
            'myMessages' => $myMessages,
            'supportEmail' => self::SUPPORT_EMAIL,
            'supportWhatsApp' => self::SUPPORT_WHATSAPP,
            'whatsAppLink' => 'https://wa.me/' . self::SUPPORT_WHATSAPP_FORMATTED,
        ]);
    }

    /**
     * Handle in-app contact form submission from authenticated users.
     * All user messages come through the web interface only.
     */
    public function submitSupport(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:inquiry,support,feedback,complaint,other',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        try {
            ContactMessage::create([
                'user_id' => $user->id,
                'school_id' => $user->school_id,
                'source' => ContactMessage::SOURCE_APP,
                'channel' => ContactMessage::CHANNEL_WEB, // Always web for user messages
                'type' => $validated['type'],
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? null,
                'school_name' => $user->school?->name,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'status' => ContactMessage::STATUS_UNREAD,
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Pesan Anda telah dikirim! Kami akan merespons secepatnya.',
            ]);

        } catch (\Exception $e) {
            Log::error('Support form error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Maaf, terjadi kesalahan. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Get user messages for widget (JSON).
     */
    public function getMessages()
    {
        $user = Auth::user();
        $messages = ContactMessage::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'subject' => $msg->subject ?: $msg->type_label,
                    'message_preview' => Str::limit($msg->message, 50),
                    'status' => $msg->status,
                    'status_label' => $msg->status_label,
                    'status_color' => $msg->status_color,
                    'created_at' => $msg->created_at->format('d M Y, H:i'),
                    'time_ago' => $msg->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Get specific message thread for widget (JSON).
     */
    public function getMessageThread(ContactMessage $message)
    {
        // specific message retrieval logic
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $message->load(['replies', 'replies.user', 'repliedByUser']);

        // Format replies
        $replies = $message->replies->map(function ($reply) {
            return [
                'id' => $reply->id,
                'message' => $reply->message,
                'is_admin' => $reply->is_admin_reply,
                'sender_name' => $reply->is_admin_reply ? 'Admin Support' : 'Anda',
                'created_at' => $reply->created_at->format('d M H:i'),
            ];
        });

        // Add legacy reply if exists and no threaded replies
        if ($message->reply_message && $message->replies->count() === 0) {
            $replies->prepend([
                'id' => 'legacy',
                'message' => $message->reply_message,
                'is_admin' => true,
                'sender_name' => 'Admin Support',
                'created_at' => $message->replied_at ? $message->replied_at->format('d M H:i') : '',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'subject' => $message->subject ?: $message->type_label,
                'content' => $message->message,
                'created_at' => $message->created_at->format('d M H:i'),
                'status' => $message->status,
                'status_label' => $message->status_label,
            ],
            'replies' => $replies
        ]);
    }

    /**
     * Store reply from user/school admin
     */
    public function storeUserReply(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        // Ensure user owns the message
        if ($message->user_id !== $user->id) {
            abort(403);
        }

        try {
            ContactReply::create([
                'contact_message_id' => $message->id,
                'user_id' => $user->id,
                'message' => $validated['message'],
                'is_admin_reply' => false,
            ]);

            // Update status to unread/in_progress so admin sees it
            $message->update([
                'status' => ContactMessage::STATUS_IN_PROGRESS,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Balasan terkirim!',
                'reply' => [
                    'message' => $validated['message'],
                    'created_at' => now()->format('d M Y, H:i'),
                    'user_name' => $user->name,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('User reply error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim balasan.',
            ], 500);
        }
    }

    // ==================== SUPER ADMIN METHODS ====================

    /**
     * Display list of all messages for Super Admin.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::with(['user', 'school', 'repliedByUser']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Filter by channel
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(20);
        $unreadCount = ContactMessage::unread()->count();

        return view('admin.messages.index', [
            'messages' => $messages,
            'unreadCount' => $unreadCount,
            'statusLabels' => ContactMessage::STATUS_LABELS,
            'channelLabels' => ContactMessage::CHANNEL_LABELS,
        ]);
    }

    /**
     * Display a specific message for Super Admin.
     */
    public function show(ContactMessage $message)
    {
        // Mark as read when viewing
        $message->markAsRead();

        $message->load(['user', 'school', 'repliedByUser', 'replies.user']);

        return view('admin.messages.show', [
            'message' => $message,
            'supportEmail' => self::SUPPORT_EMAIL,
            'supportWhatsApp' => self::SUPPORT_WHATSAPP,
        ]);
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Reply to a message.
     */
    public function reply(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'reply_message' => 'required|string|max:5000',
            'send_via' => 'required|in:email,whatsapp,in_app,note_only',
        ]);

        $user = Auth::user();

        try {
            // Handle note_only differently - store in admin_notes (not visible to users)
            if ($validated['send_via'] === 'note_only') {
                $notePrefix = "[" . now()->format('d/m/Y H:i') . " - {$user->name}]\n";
                $message->update([
                    'status' => ContactMessage::STATUS_IN_PROGRESS, // Don't mark as replied for notes
                    'admin_notes' => ($message->admin_notes ? $message->admin_notes . "\n\n" : '') .
                        $notePrefix . $validated['reply_message'],
                ]);
            } else {
                // Create reply record
                ContactReply::create([
                    'contact_message_id' => $message->id,
                    'user_id' => $user->id,
                    'message' => $validated['reply_message'],
                    'is_admin_reply' => true,
                ]);

                // Update message status
                $message->update([
                    'status' => ContactMessage::STATUS_REPLIED,
                    'replied_by' => $user->id,
                    'reply_message' => $validated['reply_message'], // Keep for legacy/list view if needed
                    'replied_at' => now(),
                ]);
            }

            // Send reply via selected channel and create notification
            $channelLabel = match ($validated['send_via']) {
                'email' => 'Email',
                'whatsapp' => 'WhatsApp',
                'in_app' => 'Aplikasi',
                default => 'Web',
            };

            if ($validated['send_via'] === 'email') {
                // Send email reply
                Mail::raw($validated['reply_message'], function ($mail) use ($message) {
                    $mail->to($message->email)
                        ->from(self::SUPPORT_EMAIL, 'e-Report Support')
                        ->subject('Re: ' . ($message->subject ?: 'Pesan Anda ke e-Report'));
                });
            } elseif ($validated['send_via'] === 'whatsapp') {
                // For WhatsApp, we'll generate a link that admin can click
                $waText = urlencode($validated['reply_message']);
                $waNumber = preg_replace('/[^0-9]/', '', $message->phone ?: '');

                if ($waNumber) {
                    // Store the WhatsApp link for admin to click
                    $message->update([
                        'admin_notes' => ($message->admin_notes ? $message->admin_notes . "\n\n" : '') .
                            "[WhatsApp Reply Generated] wa.me/{$waNumber}?text={$waText}",
                    ]);
                }
            }

            // Create in-app notification for registered users (for all channels except note_only)
            if ($message->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $message->user_id,
                    'school_id' => $message->school_id,
                    'type' => 'support_reply',
                    'title' => 'Balasan dari Support',
                    'message' => "Pesan Anda telah dibalas via {$channelLabel}. " .
                        ($validated['send_via'] === 'in_app'
                            ? 'Lihat balasan di halaman Hubungi Support.'
                            : "Silakan cek {$channelLabel} Anda."),
                    'data' => [
                        'message_id' => $message->id,
                        'subject' => $message->subject,
                        'reply_channel' => $validated['send_via'],
                    ],
                ]);
            }

            // Log the action
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'reply_message',
                'model_type' => ContactMessage::class,
                'model_id' => $message->id,
                'description' => "Membalas pesan dari {$message->name} via {$validated['send_via']}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.messages.show', $message)
                ->with('success', 'Balasan berhasil dikirim!');

        } catch (\Exception $e) {
            Log::error('Reply error: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengirim balasan: ' . $e->getMessage());
        }
    }

    /**
     * Update message status.
     */
    public function updateStatus(Request $request, ContactMessage $message)
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,in_progress,replied,closed',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $message->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $message->admin_notes,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    /**
     * Get unread message count (for navbar badge).
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => ContactMessage::unread()->count(),
        ]);
    }

    /**
     * Get message replies for super admin real-time polling (JSON).
     */
    public function getAdminReplies(ContactMessage $message)
    {
        $message->load(['replies.user']);

        $replies = $message->replies->map(function ($reply) {
            return [
                'id' => $reply->id,
                'message' => $reply->message,
                'is_admin' => $reply->is_admin_reply,
                'sender_name' => $reply->is_admin_reply
                    ? 'Admin Support'
                    : ($reply->user->name ?? 'User'),
                'created_at' => $reply->created_at->format('d M Y, H:i'),
            ];
        });

        // Include legacy reply if exists
        $legacyReply = null;
        if ($message->reply_message && $message->replies->count() == 0) {
            $legacyReply = [
                'id' => 'legacy',
                'message' => $message->reply_message,
                'is_admin' => true,
                'sender_name' => 'Admin',
                'created_at' => $message->replied_at ? $message->replied_at->format('d M Y, H:i') : '',
            ];
        }

        return response()->json([
            'success' => true,
            'replies' => $replies,
            'legacy_reply' => $legacyReply,
            'replies_count' => $message->replies->count(),
            'status' => $message->status,
            'status_label' => $message->status_label,
        ]);
    }
}
