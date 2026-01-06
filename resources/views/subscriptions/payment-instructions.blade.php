@extends('layouts.app')

@section('title', 'Instruksi Pembayaran')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card text-center mb-6">
        <div class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Menunggu Pembayaran</h1>
        <p class="text-gray-600 dark:text-gray-400">Silakan selesaikan pembayaran sebelum batas waktu</p>
        
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 mt-6 inline-block">
            <p class="text-sm text-gray-500 dark:text-gray-400">Batas waktu pembayaran</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $payment->expires_at->format('d M Y, H:i') }} WIB</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Payment Details -->
        <div class="card">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Detail Pembayaran</h2>
            
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">No. Invoice</span>
                    <span class="font-mono font-medium text-gray-900 dark:text-white">{{ $payment->invoice_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Paket</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $package->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Durasi</span>
                    <span class="text-gray-900 dark:text-white">{{ $package->duration_months }} bulan</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Metode</span>
                    <span class="text-gray-900 dark:text-white">
                        @switch($payment->payment_method)
                            @case('bank_transfer')
                                Transfer Bank
                                @break
                            @case('virtual_account')
                                Virtual Account
                                @break
                            @case('ewallet')
                                E-Wallet
                                @break
                            @default
                                {{ $payment->payment_method }}
                        @endswitch
                    </span>
                </div>
                @if($payment->notes)
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Catatan</span>
                        <span class="text-green-600 text-sm">{{ $payment->notes }}</span>
                    </div>
                @endif
            </div>
            
            <div class="border-t border-gray-200 dark:border-gray-700 mt-4 pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">Total Bayar</span>
                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $payment->formatted_amount }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="card">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Instruksi Pembayaran</h2>
            
            @if($payment->payment_method === 'bank_transfer')
                <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-xl p-4 mb-4">
                    <p class="text-sm text-indigo-700 dark:text-indigo-400 font-medium mb-2">Transfer ke rekening:</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">Bank BCA</p>
                    <p class="text-xl font-mono font-bold text-indigo-600 dark:text-indigo-400">1234 5678 9012</p>
                    <p class="text-gray-600 dark:text-gray-400">a.n. PT E-Report Indonesia</p>
                </div>
            @elseif($payment->payment_method === 'virtual_account')
                <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-xl p-4 mb-4">
                    <p class="text-sm text-indigo-700 dark:text-indigo-400 font-medium mb-2">Nomor Virtual Account:</p>
                    <p class="text-2xl font-mono font-bold text-indigo-600 dark:text-indigo-400">8808 {{ str_pad($payment->id, 12, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-gray-600 dark:text-gray-400">Bank BCA Virtual Account</p>
                </div>
            @else
                <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-xl p-4 mb-4">
                    <p class="text-indigo-700 dark:text-indigo-400">Silakan transfer ke dompet digital kami</p>
                </div>
            @endif

            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span>
                    <p class="text-gray-600 dark:text-gray-400">Transfer sesuai nominal yang tertera</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span>
                    <p class="text-gray-600 dark:text-gray-400">Simpan bukti pembayaran</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span>
                    <p class="text-gray-600 dark:text-gray-400">Langganan akan aktif otomatis (1x24 jam)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-6">
        <a href="{{ route('subscriptions.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">
            ← Kembali ke halaman langganan
        </a>
    </div>
</div>
@endsection
