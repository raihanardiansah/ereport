@extends('layouts.app')

@section('title', 'Langganan Berakhir')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="max-w-lg w-full mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center">
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto mb-6 {{ $isAdmin ?? false ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-red-100 dark:bg-red-900/30' }} rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 {{ $isAdmin ?? false ? 'text-amber-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                @if($reason === 'trial_expired')
                    Masa Trial Telah Berakhir
                @else
                    Paket Langganan Telah Berakhir
                @endif
            </h1>

            <!-- Message - Different for admin vs non-admin -->
            @if($isAdmin ?? false)
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Paket langganan sekolah Anda telah berakhir. Silakan beli paket baru untuk melanjutkan menggunakan semua fitur e-Report.
            </p>

            <!-- Info Box for Admin -->
            <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-primary-800 dark:text-primary-300 text-sm font-medium">Semua fitur masih dapat diakses</p>
                        <p class="text-primary-700 dark:text-primary-400 text-sm mt-1">
                            Sebagai admin, Anda masih memiliki akses penuh selama proses pembelian paket baru.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions for Admin -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('subscriptions.packages') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Beli Paket
                </a>
                <a href="{{ route('subscriptions.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Lihat Riwayat
                </a>
            </div>

            @else
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Layanan e-Report untuk sekolah Anda telah berakhir. Silakan hubungi admin sekolah Anda untuk membeli paket agar dapat melanjutkan menggunakan layanan ini.
            </p>

            <!-- Info Box for Non-Admin -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-amber-800 dark:text-amber-300 text-sm font-medium">Informasi</p>
                        <p class="text-amber-700 dark:text-amber-400 text-sm mt-1">
                            Hanya admin sekolah yang dapat membeli atau memperpanjang paket langganan. Jika Anda membutuhkan akses segera, hubungi admin sekolah Anda.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions for Non-Admin -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('profile.show') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
            @endif

            <!-- School Info -->
            @if(auth()->user()->school)
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <p>Sekolah: <span class="font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->school->name }}</span></p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
