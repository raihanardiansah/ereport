@extends('layouts.app')

@section('title', 'Langganan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Langganan</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola paket dan pembayaran langganan sekolah Anda</p>
        </div>
        <a href="{{ route('subscriptions.packages') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            {{ $currentSubscription ? 'Upgrade Paket' : 'Pilih Paket' }}
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Current Subscription Status -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Status Card -->
        <div class="lg:col-span-2 card">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Langganan</h2>
            
            @if($currentSubscription)
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $currentSubscription->package->name ?? 'Paket Tidak Tersedia' }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $currentSubscription->status_color }}">
                                {{ ucfirst($currentSubscription->status) }}
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $currentSubscription->package->description ?? 'Paket langganan aktif' }}</p>
                        
                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ $currentSubscription->payment_method === 'trial' ? 'Sisa Masa Percobaan' : 'Masa aktif' }}
                                </span>
                                <span class="font-medium {{ $subscriptionInfo['is_expiring_soon'] ? 'text-amber-600' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $subscriptionInfo['days_remaining'] }} hari tersisa
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full {{ $currentSubscription->payment_method === 'trial' ? 'bg-orange-500' : ($subscriptionInfo['is_expiring_soon'] ? 'bg-amber-500' : 'bg-indigo-600') }}" 
                                     style="width: {{ $subscriptionInfo['progress_percent'] }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400 mt-1">
                                <span>{{ $currentSubscription->starts_at->format('d M Y') }}</span>
                                <span>{{ $currentSubscription->expires_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        @if($subscriptionInfo['is_expiring_soon'])
                            <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-xl p-3 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="text-amber-700 dark:text-amber-400 text-sm font-medium">Langganan akan berakhir dalam {{ $subscriptionInfo['days_remaining'] }} hari!</span>
                                </div>
                            </div>
                        @endif

                        @if(!$currentSubscription->package)
                            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-3 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="text-red-700 dark:text-red-400 text-sm font-medium">Paket sudah tidak tersedia. Silakan pilih paket baru.</span>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('subscriptions.packages') }}" class="btn-primary text-sm">Perpanjang / Upgrade</a>
                            <button onclick="showUnsubscribeModal()" class="bg-white border border-red-300 text-red-600 hover:bg-red-50 font-semibold py-2 px-4 rounded-xl transition-colors text-sm">
                                Berhenti Langganan
                            </button>
                        </div>
                    </div>
                </div>
            @elseif($trialEligibility && $trialEligibility['is_eligible'])
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Trial Tersedia</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                Gratis
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Anda berhak mencoba layanan penuh selama 7 hari gratis.</p>
                        
                        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 mb-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-10 h-10 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-emerald-700 dark:text-emerald-400 font-semibold">Tersedia 7 Hari Gratis</p>
                                    <p class="text-emerald-600 dark:text-emerald-500 text-sm">Tanpa biaya, akses penuh.</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('subscriptions.packages') }}" class="btn-primary text-sm">Ambil Trial Sekarang</a>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum ada langganan aktif</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Pilih paket untuk mengaktifkan semua fitur</p>
                    <a href="{{ route('subscriptions.packages') }}" class="btn-primary">Pilih Paket</a>
                </div>
            @endif
        </div>

        <!-- Package Benefits / Quick Stats -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                @if($currentSubscription)
                    Benefit Paket Anda
                @else
                    Keuntungan Berlangganan
                @endif
            </h3>
            
            @if($currentSubscription && $currentSubscription->package)
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Hingga {{ $currentSubscription->package->max_users }} pengguna
                    </li>
                    <li class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $currentSubscription->package->max_reports_per_month }} laporan/bulan
                    </li>
                    @if($currentSubscription->package->features)
                        @foreach($currentSubscription->package->features as $feature)
                            <li class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    @endif
                </ul>
            @else
                <ul class="space-y-3">
                    <li class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Akses semua fitur
                    </li>
                    <li class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Multi-user access
                    </li>
                    <li class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V17a2 2 0 01-2 2z"/>
                        </svg>
                        Laporan analytics
                    </li>
                    <li class="flex items-center text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Support prioritas
                    </li>
                </ul>
            @endif
        </div>
    </div>

    <!-- Available Packages Preview -->
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Paket Tersedia</h2>
            <a href="{{ route('subscriptions.packages') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat Semua →</a>
        </div>
        
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($packages->take(3) as $pkg)
                <div class="border rounded-xl p-5 {{ $pkg->is_featured ? 'border-indigo-500 ring-2 ring-indigo-500' : 'border-gray-200 dark:border-gray-700' }} relative">
                    @if($pkg->badge_text)
                        <span class="absolute -top-3 left-4 px-3 py-1 text-xs font-semibold rounded-full bg-indigo-500 text-white">
                            {{ $pkg->badge_text }}
                        </span>
                    @endif
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $pkg->name }}</h3>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">{{ $pkg->formatted_price }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $pkg->duration_months }} bulan</p>
                    <a href="{{ route('subscriptions.checkout', $pkg) }}" class="block w-full text-center mt-4 {{ $pkg->is_featured ? 'btn-primary' : 'btn-outline' }} text-sm py-2">
                        Pilih
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Payment History -->
    @if($paymentHistory->count() > 0)
        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pembayaran</h2>
                <a href="{{ route('subscriptions.history') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat Semua →</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-gray-500 dark:text-gray-400">
                            <th class="pb-3 font-medium">Invoice</th>
                            <th class="pb-3 font-medium">Tanggal</th>
                            <th class="pb-3 font-medium">Jumlah</th>
                            <th class="pb-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($paymentHistory as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                <td class="py-3 font-mono text-sm text-gray-900 dark:text-white">
                                    @if($transaction->transaction_status === 'pending')
                                        <a href="{{ route('subscriptions.waiting', $transaction->order_id) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                            {{ $transaction->order_id }}
                                        </a>
                                    @else
                                        {{ $transaction->order_id }}
                                    @endif
                                </td>
                                <td class="py-3 text-gray-600 dark:text-gray-400">{{ $transaction->created_at->format('d M Y') }}</td>
                                <td class="py-3 font-medium text-gray-900 dark:text-white">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    @php
                                        $statusConfig = match($transaction->transaction_status) {
                                            'success', 'settlement' => ['text' => 'Paid', 'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
                                            'pending' => ['text' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'],
                                            'expire', 'expired' => ['text' => 'Expired', 'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
                                            'cancel', 'deny' => ['text' => 'Failed', 'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
                                            default => ['text' => ucfirst($transaction->transaction_status), 'class' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['text'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Unsubscribe Confirmation Modal -->
<div id="unsubscribe-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Berhenti Langganan</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Apakah Anda yakin ingin berhenti langganan?</p>
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3">
                <p class="text-red-700 dark:text-red-400 text-sm font-medium">Dengan mengonfirmasi aksi ini, akses ke semua fitur berlangganan akan terhenti tanpa pengembalian dana. Pastikan ini adalah keputusan yang tepat.</p>
            </div>
        </div>

        <!-- CAPTCHA Section -->
        <div class="mb-4 flex justify-center">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        </div>

        <!-- Password Confirmation -->
        <div class="mb-4">
            <label for="unsubscribe-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Konfirmasi Password Anda
            </label>
            <div class="relative">
                <input 
                    type="password" 
                    id="unsubscribe-password" 
                    placeholder="Masukkan password Anda"
                    class="w-full px-4 py-2.5 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    required
                >
                <button 
                    type="button"
                    onclick="togglePasswordVisibility()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none"
                    aria-label="Toggle password visibility"
                >
                    <!-- Eye Icon (show password) -->
                    <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <!-- Eye Slash Icon (hide password) -->
                    <svg id="eye-slash-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masukkan password akun Anda untuk konfirmasi</p>
        </div>

        <div class="flex gap-3">
            <button onclick="hideUnsubscribeModal()" class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-3 px-6 rounded-xl transition-colors">
                Batal
            </button>
            <button onclick="unsubscribe()" id="unsubscribe-confirm-btn" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="unsubscribe-btn-text">Ya, Berhenti</span>
                <span id="unsubscribe-btn-loading" class="hidden">
                    <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    // Show/Hide Unsubscribe Modal
    let unsubscribeTimer = null;

    function showUnsubscribeModal() {
        document.getElementById('unsubscribe-modal').classList.remove('hidden');
        
        // Countdown logic
        const btn = document.getElementById('unsubscribe-confirm-btn');
        const btnText = document.getElementById('unsubscribe-btn-text');
        let timeLeft = 30;
        
        btn.disabled = true;
        btnText.innerText = `Ya, Berhenti (${timeLeft})`;
        
        if (unsubscribeTimer) clearInterval(unsubscribeTimer);
        
        unsubscribeTimer = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(unsubscribeTimer);
                btn.disabled = false;
                btnText.innerText = 'Ya, Berhenti';
            } else {
                btnText.innerText = `Ya, Berhenti (${timeLeft})`;
            }
        }, 1000);
    }

    function hideUnsubscribeModal() {
        document.getElementById('unsubscribe-modal').classList.add('hidden');
        if (unsubscribeTimer) {
            clearInterval(unsubscribeTimer);
        }
        // Reset button
        const btn = document.getElementById('unsubscribe-confirm-btn');
        const btnText = document.getElementById('unsubscribe-btn-text');
        btnText.innerText = 'Ya, Berhenti';
        btn.disabled = false;
        
        // Reset password field
        document.getElementById('unsubscribe-password').value = '';
        document.getElementById('unsubscribe-password').type = 'password';
        document.getElementById('eye-icon').classList.remove('hidden');
        document.getElementById('eye-slash-icon').classList.add('hidden');
        
        // Reset reCAPTCHA
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.reset();
        }
    }

    // Toggle password visibility
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('unsubscribe-password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeSlashIcon = document.getElementById('eye-slash-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        }
    }

    // Unsubscribe
    async function unsubscribe() {
        const btn = document.getElementById('unsubscribe-confirm-btn');
        const btnText = document.getElementById('unsubscribe-btn-text');
        const btnLoading = document.getElementById('unsubscribe-btn-loading');
        const passwordInput = document.getElementById('unsubscribe-password');

        // Get CAPTCHA token
        const recaptchaToken = grecaptcha.getResponse();
        if (!recaptchaToken) {
            showToast('Silakan centang reCAPTCHA terlebih dahulu', 'error');
            return;
        }

        // Validate password
        const password = passwordInput.value.trim();
        if (!password) {
            showToast('Silakan masukkan password Anda', 'error');
            passwordInput.focus();
            return;
        }

        btn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');

        try {
            const response = await fetch('/subscriptions/unsubscribe', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    'g-recaptcha-response': recaptchaToken,
                    'password': password
                })
            });

            const data = await response.json();

            if (data.success) {
                showToast('Langganan berhasil diberhentikan', 'success');
                setTimeout(() => {
                    // Force hard refresh by adding timestamp to bypass cache
                    window.location.href = window.location.pathname + '?t=' + Date.now();
                }, 1500);
            } else {
                throw new Error(data.error || data.message || 'Gagal memberhentikan langganan');
            }
        } catch (error) {
            console.error('Error unsubscribing:', error);
            showToast('Gagal memberhentikan langganan: ' + error.message, 'error');
            btn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
            
            // Reset reCAPTCHA on error
            if (typeof grecaptcha !== 'undefined') {
                grecaptcha.reset();
            }
        }
    }
</script>
@endpush


@endsection

