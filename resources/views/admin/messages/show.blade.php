@extends('layouts.app')

@section('title', 'Detail Pesan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.messages') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pesan</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $message->subject ?: $message->type_label }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $message->status_color }}">
                {{ $message->status_label }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $message->channel_color }}">
                {{ $message->channel_label }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Message Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Original Message -->
            <div class="card">
                <div class="flex items-start space-x-4 mb-6">
                    <div class="flex-shrink-0 h-12 w-12 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                        <span class="text-primary-600 dark:text-primary-400 font-bold text-lg">{{ strtoupper(substr($message->name, 0, 1)) }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $message->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $message->email }}</p>
                        @if($message->phone)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $message->phone }}</p>
                        @endif
                        @if($message->school_name)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $message->school_name }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $message->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                @if($message->subject)
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-3">{{ $message->subject }}</h4>
                @endif

                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->message }}</p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        Tipe: {{ $message->type_label }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        Sumber: {{ $message->source === 'landing_page' ? 'Landing Page' : 'Dalam Aplikasi' }}
                    </span>
                    @if($message->user)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                            Pengguna Terdaftar
                        </span>
                    @endif
                </div>
            </div>

            <!-- Previous Reply (if any) -->
            <!-- Conversation Thread -->
            @if($message->replies->count() > 0 || $message->reply_message)
                <div class="card bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Riwayat Percakapan</h3>
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                        
                        <!-- Legacy Single Reply (if exists and no threaded replies) -->
                        @if($message->reply_message && $message->replies->count() == 0)
                            <div class="flex justify-start">
                                <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800 rounded-xl p-4 max-w-[90%]">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs font-bold text-primary-700 dark:text-primary-300">Admin</span>
                                        <span class="text-xs text-primary-500 dark:text-primary-400">{{ $message->replied_at?->format('d M Y, H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $message->reply_message }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Threaded Replies -->
                        @foreach($message->replies as $reply)
                            <div class="flex {{ $reply->is_admin_reply ? 'justify-start' : 'justify-end' }}">
                                <div class="{{ $reply->is_admin_reply 
                                    ? 'bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800' 
                                    : 'bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600' }} rounded-xl p-4 max-w-[90%] shadow-sm">
                                    
                                    <div class="flex items-center gap-2 mb-2 {{ $reply->is_admin_reply ? 'justify-start' : 'justify-end' }}">
                                        @if($reply->is_admin_reply)
                                            <span class="text-xs font-bold text-primary-700 dark:text-primary-300">Admin Support</span>
                                        @else
                                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $reply->user->name ?? 'User' }}</span>
                                        @endif
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $reply->message }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reply Form -->
            @if($message->status !== 'closed')
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        {{ $message->reply_message ? 'Kirim Balasan Baru' : 'Balas Pesan' }}
                    </h3>
                    <form action="{{ route('admin.messages.reply', $message) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pesan Balasan</label>
                            <textarea name="reply_message" rows="5" required 
                                      class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:text-white resize-none"
                                      placeholder="Tulis balasan Anda...">{{ old('reply_message') }}</textarea>
                            @error('reply_message')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kirim Via</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <!-- Email Option - Always available -->
                                <label class="relative flex flex-col items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-blue-400 dark:hover:border-blue-500 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                                    <input type="radio" name="send_via" value="email" class="sr-only" checked>
                                    <svg class="w-6 h-6 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tersedia</span>
                                </label>

                                <!-- WhatsApp Option - Conditional -->
                                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl transition-colors {{ $message->phone ? 'border-gray-200 dark:border-gray-700 cursor-pointer hover:border-green-400 dark:hover:border-green-500 has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20' : 'border-gray-100 dark:border-gray-800 opacity-50 cursor-not-allowed' }}">
                                    <input type="radio" name="send_via" value="whatsapp" class="sr-only" {{ $message->phone ? '' : 'disabled' }}>
                                    <svg class="w-6 h-6 {{ $message->phone ? 'text-green-500' : 'text-gray-400' }} mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                    </svg>
                                    <span class="text-sm font-medium {{ $message->phone ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400' }}">WhatsApp</span>
                                    <span class="text-xs {{ $message->phone ? 'text-gray-500 dark:text-gray-400' : 'text-red-400' }} mt-1">{{ $message->phone ? 'Tersedia' : 'Tidak ada' }}</span>
                                </label>

                                <!-- In-App Option - Only for registered users -->
                                <label class="relative flex flex-col items-center p-4 border-2 rounded-xl transition-colors {{ $message->user_id ? 'border-gray-200 dark:border-gray-700 cursor-pointer hover:border-purple-400 dark:hover:border-purple-500 has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50 dark:has-[:checked]:bg-purple-900/20' : 'border-gray-100 dark:border-gray-800 opacity-50 cursor-not-allowed' }}">
                                    <input type="radio" name="send_via" value="in_app" class="sr-only" {{ $message->user_id ? '' : 'disabled' }}>
                                    <svg class="w-6 h-6 {{ $message->user_id ? 'text-purple-500' : 'text-gray-400' }} mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span class="text-sm font-medium {{ $message->user_id ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400' }}">In-App</span>
                                    <span class="text-xs {{ $message->user_id ? 'text-gray-500 dark:text-gray-400' : 'text-red-400' }} mt-1">{{ $message->user_id ? 'Tersedia' : 'Bukan user' }}</span>
                                </label>

                                <!-- Note Only Option - Always available -->
                                <label class="relative flex flex-col items-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl cursor-pointer hover:border-gray-400 dark:hover:border-gray-500 transition-colors has-[:checked]:border-gray-500 has-[:checked]:bg-gray-50 dark:has-[:checked]:bg-gray-800">
                                    <input type="radio" name="send_via" value="note_only" class="sr-only">
                                    <svg class="w-6 h-6 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Internal</span>
                                </label>
                            </div>
                            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                <span class="font-medium">Keterangan:</span> Email selalu tersedia. WhatsApp memerlukan nomor telepon. In-App hanya untuk pengguna terdaftar.
                            </p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Kirim Balasan
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject ?: 'Pesan Anda ke e-Report') }}" 
                       class="flex items-center w-full px-4 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Balas via Email Client
                    </a>
                    @if($message->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank"
                           class="flex items-center w-full px-4 py-3 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                            </svg>
                            Buka WhatsApp
                        </a>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ubah Status</h3>
                <form action="{{ route('admin.messages.status', $message) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <select name="status" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white">
                        <option value="unread" {{ $message->status === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ $message->status === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                        <option value="in_progress" {{ $message->status === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>Sudah Dibalas</option>
                        <option value="closed" {{ $message->status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan Admin</label>
                        <textarea name="admin_notes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white text-sm resize-none"
                                  placeholder="Catatan internal...">{{ $message->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="w-full btn-outline">Perbarui Status</button>
                </form>
            </div>

            <!-- User Info -->
            @if($message->user)
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Info Pengguna</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Role</span>
                            <span class="text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $message->user->role) }}</span>
                        </div>
                        @if($message->school)
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Sekolah</span>
                                <a href="{{ route('admin.school.detail', $message->school) }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                    {{ $message->school->name }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const messageId = {{ $message->id }};
    let lastReplyCount = {{ $message->replies->count() }};
    let pollInterval = null;
    
    // Container for conversation thread
    const threadContainer = document.querySelector('.custom-scrollbar');
    
    function renderReplyBubble(reply) {
        const isAdmin = reply.is_admin;
        const alignClass = isAdmin ? 'justify-start' : 'justify-end';
        const bubbleClass = isAdmin 
            ? 'bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800' 
            : 'bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600';
        const nameClass = isAdmin ? 'text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300';
        
        return `
            <div class="flex ${alignClass} animate-fadeIn">
                <div class="${bubbleClass} rounded-xl p-4 max-w-[90%] shadow-sm">
                    <div class="flex items-center gap-2 mb-2 ${isAdmin ? 'justify-start' : 'justify-end'}">
                        <span class="text-xs font-bold ${nameClass}">${reply.sender_name}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">${reply.created_at}</span>
                    </div>
                    <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">${reply.message}</p>
                </div>
            </div>
        `;
    }
    
    async function checkForNewReplies() {
        try {
            const response = await fetch(`/admin/messages/${messageId}/replies`);
            const data = await response.json();
            
            if (data.success && data.replies_count > lastReplyCount) {
                // New replies detected - refresh the thread container
                const newReplies = data.replies.slice(lastReplyCount);
                
                if (threadContainer) {
                    newReplies.forEach(reply => {
                        threadContainer.insertAdjacentHTML('beforeend', renderReplyBubble(reply));
                    });
                    
                    // Scroll to bottom
                    threadContainer.scrollTop = threadContainer.scrollHeight;
                    
                    // Show notification
                    showNewReplyNotification(newReplies.length);
                }
                
                lastReplyCount = data.replies_count;
            }
        } catch (e) {
            console.error('Failed to check for new replies', e);
        }
    }
    
    function showNewReplyNotification(count) {
        // Create a toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-bounce';
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>${count} balasan baru!</span>
            </div>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    // Start polling every 5 seconds
    pollInterval = setInterval(checkForNewReplies, 5000);
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (pollInterval) {
            clearInterval(pollInterval);
        }
    });
    
    // Add fadeIn animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }
    `;
    document.head.appendChild(style);
})();
</script>
@endpush
@endsection
