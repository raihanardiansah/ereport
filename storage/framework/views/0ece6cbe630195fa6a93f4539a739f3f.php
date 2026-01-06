<?php if(auth()->check() && (auth()->user()->isAdminSekolah() || auth()->user()->hasRole('manajemen_sekolah'))): ?>
<div id="chat-widget-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col items-end space-y-4 font-sans print:hidden">
    
    <!-- Chat Window -->
    <div id="chat-window" class="hidden w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 transform origin-bottom-right">
        <!-- Header -->
        <div class="bg-primary-600 px-4 py-3 flex justify-between items-center text-white">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <h3 class="font-medium text-sm" id="chat-title">Bantuan & Support</h3>
            </div>
            <button onclick="toggleChatWidget()" class="hover:bg-primary-700 rounded-full p-1 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Content Area -->
        <div id="chat-content" class="h-96 overflow-y-auto bg-gray-50 dark:bg-gray-900 custom-scrollbar relative">
            <!-- Loading State -->
            <div id="chat-loading" class="hidden absolute inset-0 bg-white/80 dark:bg-gray-800/80 flex items-center justify-center z-10">
                <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- View: List -->
            <div id="view-list" class="p-4 space-y-3">
                <div class="text-center mb-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Butuh bantuan? Tim kami siap membantu Anda.</p>
                    <a href="<?php echo e(route('contact.support')); ?>" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Buat Tiket Baru</a>
                </div>
                <div id="message-list-container" class="space-y-2">
                    <!-- Messages will be injected here -->
                </div>
            </div>

            <!-- View: Thread -->
            <div id="view-thread" class="hidden flex flex-col h-full">
                <div class="flex-shrink-0 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 px-4 py-2 flex items-center gap-2">
                    <button onclick="showChatList()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <span class="text-xs font-medium text-gray-900 dark:text-white truncate flex-1" id="thread-subject">Subject</span>
                </div>
                <div id="thread-messages" class="flex-1 p-4 space-y-3 overflow-y-auto">
                    <!-- Thread messages injected here -->
                </div>
                <div class="p-3 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                    <form onsubmit="submitWidgetReply(event)" class="flex gap-2">
                        <input type="hidden" id="current-thread-id">
                        <input type="text" id="widget-reply-input" class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-xs py-2" placeholder="Tulis balasan..." required autocomplete="off">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white p-2 rounded-lg">
                            <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button onclick="toggleChatWidget()" id="chat-fab" class="bg-primary-600 hover:bg-primary-700 text-white p-4 rounded-full shadow-lg transition-transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        
        <span id="chat-badge" class="hidden absolute top-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white bg-red-500"></span>
    </button>
</div>

<script>
    const chatWidget = {
        isOpen: false,
        isLoading: false,
        
        toggle() {
            const window = document.getElementById('chat-window');
            if (window.classList.contains('hidden')) {
                window.classList.remove('hidden');
                this.isOpen = true;
                this.loadMessages();
            } else {
                window.classList.add('hidden');
                this.isOpen = false;
            }
        },

        async loadMessages() {
            this.setLoading(true);
            try {
                const response = await fetch("<?php echo e(route('contact.support.api.messages')); ?>");
                const data = await response.json();
                
                if (data.success) {
                    this.renderMessageList(data.messages);
                    document.getElementById('view-list').classList.remove('hidden');
                    document.getElementById('view-thread').classList.add('hidden');
                }
            } catch (e) {
                console.error('Failed to load messages', e);
            } finally {
                this.setLoading(false);
            }
        },

        renderMessageList(messages) {
            const container = document.getElementById('message-list-container');
            if (messages.length === 0) {
                container.innerHTML = '<p class="text-xs text-center text-gray-400 py-4">Belum ada pesan.</p>';
                return;
            }
            
            container.innerHTML = messages.map(msg => `
                <div onclick="openThread(${msg.id})" class="cursor-pointer bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow relative group">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate pr-2">${msg.subject}</span>
                        <span class="text-[10px] text-gray-400 whitespace-nowrap">${msg.time_ago}</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">${msg.message_preview}</p>
                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-[10px] px-1.5 py-0.5 rounded ${msg.status_color}">${msg.status_label}</span>
                        ${msg.status === 'replied' ? '<span class="flex h-2 w-2 rounded-full bg-green-500"></span>' : ''}
                    </div>
                </div>
            `).join('');
        },

        setLoading(loading) {
            const el = document.getElementById('chat-loading');
            if (loading) el.classList.remove('hidden');
            else el.classList.add('hidden');
        }
    };

    // Expose globally
    window.toggleChatWidget = function() {
        chatWidget.toggle();
    }

    window.openChatWidgetThread = function(id) {
        // Ensure widget is open
        const windowEl = document.getElementById('chat-window');
        if (windowEl.classList.contains('hidden')) {
            windowEl.classList.remove('hidden');
            chatWidget.isOpen = true;
        }
        openThread(id);
    }

    function showChatList() {
        document.getElementById('view-list').classList.remove('hidden');
        document.getElementById('view-thread').classList.add('hidden');
        // Reload list to refresh latest status
        chatWidget.loadMessages();
    }

    async function openThread(id) {
        chatWidget.setLoading(true);
        try {
            const response = await fetch(`/contact-support/api/thread/${id}`);
            const data = await response.json();
            
            if (data.success) {
                // Set Header
                document.getElementById('thread-subject').textContent = data.message.subject;
                document.getElementById('current-thread-id').value = data.message.id;
                
                // Render Thread
                const container = document.getElementById('thread-messages');
                
                // Header (Original Message)
                let html = `
                    <div class="flex justify-end mb-3">
                        <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-900 dark:text-blue-100 rounded-lg rounded-tr-none p-3 max-w-[85%] text-xs">
                             <p class="whitespace-pre-wrap">${data.message.content}</p>
                             <p class="text-[10px] opacity-70 mt-1 text-right">${data.message.created_at}</p>
                        </div>
                    </div>
                `;

                // Replies
                data.replies.forEach(reply => {
                    if (reply.is_admin) {
                        html += `
                            <div class="flex justify-start mb-3">
                                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg rounded-tl-none p-3 max-w-[85%] text-xs shadow-sm">
                                    <p class="font-bold text-[10px] text-primary-600 mb-0.5">Admin Support</p>
                                    <p class="whitespace-pre-wrap">${reply.message}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">${reply.created_at}</p>
                                </div>
                            </div>
                        `;
                    } else {
                        html += `
                             <div class="flex justify-end mb-3">
                                <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-900 dark:text-blue-100 rounded-lg rounded-tr-none p-3 max-w-[85%] text-xs">
                                     <p class="whitespace-pre-wrap">${reply.message}</p>
                                     <p class="text-[10px] opacity-70 mt-1 text-right">${reply.created_at}</p>
                                </div>
                            </div>
                        `;
                    }
                });

                container.innerHTML = html;
                
                // Scroll to bottom
                container.scrollTop = container.scrollHeight;

                // Switch View
                document.getElementById('view-list').classList.add('hidden');
                document.getElementById('view-thread').classList.remove('hidden');
            }
        } catch (e) {
            console.error('Error fetching thread', e);
        } finally {
            chatWidget.setLoading(false);
        }
    }

    async function submitWidgetReply(e) {
        e.preventDefault();
        const input = document.getElementById('widget-reply-input');
        const id = document.getElementById('current-thread-id').value;
        const message = input.value.trim();
        
        if (!message) return;

        // UI Optimistic Update
        const container = document.getElementById('thread-messages');
        const optimisticHtml = `
             <div class="flex justify-end mb-3 opacity-50">
                <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-900 dark:text-blue-100 rounded-lg rounded-tr-none p-3 max-w-[85%] text-xs">
                     <p class="whitespace-pre-wrap">${message}</p>
                     <p class="text-[10px] opacity-70 mt-1 text-right">Sending...</p>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', optimisticHtml);
        container.scrollTop = container.scrollHeight;
        input.value = '';

        try {
            const response = await fetch(`/contact-support/reply/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: message })
            });
            
            const data = await response.json();
            if (data.success) {
                 // Refresh thread to get proper timestamp and remove optimistic
                 openThread(id);
            }
        } catch (e) {
            showToast('Gagal mengirim pesan', 'error');
        }
    }
</script>
</div>
<?php endif; ?>
<?php /**PATH D:\laragon\www\E-Report\resources\views/partials/chat-widget.blade.php ENDPATH**/ ?>