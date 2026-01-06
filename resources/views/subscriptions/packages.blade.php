@extends('layouts.app')

@section('title', 'Pilih Paket')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('subscriptions.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white text-center">Pilih Paket yang Tepat</h1>
        <p class="text-gray-600 dark:text-gray-400 text-center mt-2">Pilih paket sesuai kebutuhan sekolah Anda</p>
    </div>

    @if($currentSubscription)
        <div class="bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 rounded-xl p-4 mb-8 text-center">
            <p class="text-indigo-700 dark:text-indigo-400">
                <span class="font-medium">Paket saat ini:</span> {{ $currentSubscription->package->name ?? '-' }} 
                (berakhir {{ $currentSubscription->expires_at->format('d M Y') }})
            </p>
        </div>
    @endif

    <!-- Packages Grid -->
    <div class="grid md:grid-cols-3 gap-6">
        @foreach($packages as $package)
            <div class="relative card {{ $package->is_featured ? 'ring-2 ring-indigo-500 border-indigo-500' : '' }}">
                @if($package->badge_text)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-xs font-semibold px-4 py-1 rounded-full">
                            {{ $package->badge_text }}
                        </span>
                    </div>
                @endif

                <div class="text-center pt-4 pb-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $package->name }}</h2>
                    <div class="mt-4">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ $package->formatted_price }}</span>
                        <span class="text-gray-500 dark:text-gray-400">/{{ $package->duration_months }} bulan</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $package->description ?? 'Paket langganan' }}</p>
                </div>

                <div class="py-6">
                    <ul class="space-y-4">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Hingga <strong>{{ $package->max_users }}</strong> pengguna</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400"><strong>{{ $package->max_reports_per_month }}</strong> laporan/bulan</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Masa aktif <strong>{{ $package->duration_days }}</strong> hari</span>
                        </li>
                        @if($package->features && is_array($package->features))
                            @foreach($package->features as $feature)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $feature }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Dashboard analytics</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Export PDF</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-600 dark:text-gray-400">Support email</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    @if($currentSubscription && $currentSubscription->package_id === $package->id)
                        <button disabled class="w-full py-3 px-6 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-semibold cursor-not-allowed">
                            Paket Saat Ini
                        </button>
                    @else
                        <a href="{{ route('subscriptions.checkout', $package) }}" class="block w-full text-center py-3 px-6 rounded-xl {{ $package->is_featured ? 'btn-primary' : 'btn-outline' }} font-semibold">
                            {{ $currentSubscription ? 'Upgrade ke Paket Ini' : 'Pilih Paket Ini' }}
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- FAQ Section -->
    <div class="mt-16 text-center">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Ada Pertanyaan?</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Hubungi tim kami untuk bantuan memilih paket yang tepat</p>
        <a href="{{ route('contact.support') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
            Hubungi Support →
        </a>
    </div>
</div>
@endsection
