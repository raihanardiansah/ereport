@extends('layouts.guest')

@section('title', 'Halaman Kedaluwarsa')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <div>
            <div class="mx-auto h-24 w-24 text-red-500 mb-4">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">419</h1>
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Halaman Kedaluwarsa</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Sesi Anda telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.
            </p>
        </div>

        <div class="space-y-3">
            <button onclick="window.history.back()" class="w-full btn-primary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </button>
            <a href="{{ route('login') }}" class="block w-full btn-outline">
                Ke Halaman Login
            </a>
        </div>

        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <p class="text-sm text-blue-800 dark:text-blue-300">
                <strong>Tips:</strong> Halaman ini muncul karena Anda terlalu lama tidak aktif. 
                Untuk menghindari masalah ini, pastikan untuk menyelesaikan form dengan cepat atau 
                centang "Ingat Saya" saat login.
            </p>
        </div>
    </div>
</div>
@endsection
