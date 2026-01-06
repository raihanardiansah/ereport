@extends('layouts.app')

@section('title', 'Hubungi Support')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="text-center max-w-2xl mx-auto">
        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hubungi Support</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            Kirim pesan kepada tim support kami. Kami akan merespons secepat mungkin.
        </p>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Contact Form (Main) -->
            <div class="lg:col-span-2">
                <div class="card">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Kirim Pesan
                    </h2>
                    
                    <div id="form-message" class="hidden p-4 rounded-xl text-sm mb-6"></div>

                    <form id="support-form" class="space-y-5">
                        @csrf
                        <input type="hidden" name="channel" value="web">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Pesan <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
                                <option value="inquiry">💬 Pertanyaan Umum</option>
                                <option value="support">🔧 Dukungan Teknis</option>
                                <option value="feedback">💡 Masukan & Saran</option>
                                <option value="complaint">⚠️ Keluhan</option>
                                <option value="other">📝 Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subjek <span class="text-red-500">*</span></label>
                            <input type="text" name="subject" required 
                                   class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                                   placeholder="Ringkasan masalah atau pertanyaan Anda">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pesan <span class="text-red-500">*</span></label>
                            <textarea name="message" rows="5" required 
                                      class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-800 dark:text-white resize-none"
                                      placeholder="Jelaskan masalah atau pertanyaan Anda secara detail..."></textarea>
                        </div>

                        <button type="submit" id="submit-btn" class="w-full btn-primary">
                            <span id="btn-text">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Kirim Pesan
                            </span>
                            <span id="btn-loading" class="hidden">
                                <svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengirim...
                            </span>
                        </button>
                    </form>

                    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400 text-center">
                        Pesan Anda akan diproses oleh tim support dan dijawab melalui notifikasi dalam aplikasi, email, atau WhatsApp.
                    </p>
                </div>
            </div>

            <!-- Sidebar - Contact Info -->
            <div class="space-y-6">
                <!-- Alternative Contact Methods -->
                <div class="card">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Kontak Alternatif</h3>
                    
                    <div class="space-y-4">
                        <a href="{{ $whatsAppLink }}" target="_blank" class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors group">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">WhatsApp</p>
                                <p class="text-xs text-green-600 dark:text-green-400">{{ $supportWhatsApp }}</p>
                            </div>
                            <svg class="w-4 h-4 ml-auto text-gray-400 group-hover:text-green-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>

                        <a href="mailto:{{ $supportEmail }}" class="flex items-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors group">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Email</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">{{ $supportEmail }}</p>
                            </div>
                            <svg class="w-4 h-4 ml-auto text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Response Time Info -->
                <div class="card bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Waktu Respons</h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Hari kerja: 1-2 jam
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Akhir pekan: 24 jam
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- My Previous Messages -->
    @if($myMessages->count() > 0)
        <div class="max-w-4xl mx-auto">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Riwayat Pesan Anda
            </h2>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach($myMessages as $msg)
                    <div class="card {{ $msg->status === 'replied' ? 'border-l-4 border-green-500' : ($msg->status === 'unread' ? 'border-l-4 border-yellow-500' : '') }}">
                        <div class="flex items-start justify-between mb-2">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $msg->subject ?: $msg->type_label }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $msg->status_color }}">
                                {{ $msg->status_label }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-2">{{ Str::limit($msg->message, 80) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">{{ $msg->created_at->format('d M Y, H:i') }}</p>
                        
                        <button onclick="openChatWidgetThread({{ $msg->id }})" class="w-full py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Lihat Diskusi & Balas
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>



<script>
document.getElementById('support-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const formMessage = document.getElementById('form-message');
    const btnText = document.getElementById('btn-text');
    const btnLoading = document.getElementById('btn-loading');
    const submitBtn = document.getElementById('submit-btn');
    
    // Show loading state
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    submitBtn.disabled = true;
    formMessage.classList.add('hidden');
    
    try {
        const formData = new FormData(form);
        
        const response = await fetch('/contact-support', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            formMessage.textContent = data.message;
            formMessage.className = 'p-4 rounded-xl text-sm bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
            formMessage.classList.remove('hidden');
            form.reset();
        } else {
            formMessage.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
            formMessage.className = 'p-4 rounded-xl text-sm bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
            formMessage.classList.remove('hidden');
        }
    } catch (error) {
        formMessage.textContent = 'Tidak dapat mengirim pesan. Silakan coba lagi.';
        formMessage.className = 'p-4 rounded-xl text-sm bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
        formMessage.classList.remove('hidden');
    } finally {
        // Reset button state
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
        submitBtn.disabled = false;
    }
});
</script>
@endsection
