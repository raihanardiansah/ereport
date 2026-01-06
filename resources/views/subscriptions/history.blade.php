<x-layouts.app title="Riwayat Pembayaran">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Pembayaran</h1>
        <p class="text-gray-600 mt-1">Semua transaksi pembayaran sekolah Anda</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Invoice</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Paket</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Metode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-medium text-gray-900">{{ $transaction->order_id }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-gray-900 text-sm">
                            {{ $transaction->package->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900 text-sm">
                            Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm capitalize">
                            {{ $transaction->payment_method == 'gopay' ? 'QRIS Gopay' : ($transaction->payment_method ? ucwords(str_replace('_', ' ', $transaction->payment_method)) : 'Virtual Account') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = match($transaction->transaction_status) {
                                    'success', 'settlement' => ['text' => 'Paid', 'class' => 'bg-green-100 text-green-700'],
                                    'pending' => ['text' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-700'],
                                    'expire', 'expired' => ['text' => 'Expired', 'class' => 'bg-red-100 text-red-700'],
                                    'cancel', 'deny' => ['text' => 'Failed', 'class' => 'bg-red-100 text-red-700'],
                                    default => ['text' => ucfirst($transaction->transaction_status), 'class' => 'bg-gray-100 text-gray-700'],
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                                {{ $statusConfig['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($transaction->transaction_status === 'pending')
                                <a href="{{ route('subscriptions.waiting', $transaction->order_id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Lanjutkan
                                </a>
                            @elseif(in_array($transaction->transaction_status, ['success', 'settlement']))
                                <button onclick="viewInvoice('{{ $transaction->order_id }}')" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Detail
                                </button>
                            @elseif(in_array($transaction->transaction_status, ['expire', 'expired', 'cancel', 'deny']))
                                <button onclick="cancelTransaction('{{ $transaction->order_id }}')" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
        @else
        <div class="p-12 text-center text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="font-medium text-lg">Belum ada transaksi</p>
            <p class="text-sm mt-1">Riwayat transaksi akan muncul di sini</p>
        </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('subscriptions.index') }}" class="text-gray-600 hover:text-gray-800">
            ← Kembali ke Langganan
        </a>
    </div>

    @push('scripts')
    <script>
        function viewInvoice(orderId) {
            // Navigate to invoice detail page
            window.location.href = '/subscriptions/invoice/' + orderId + '/view';
        }

        async function cancelTransaction(orderId) {
            if (!confirm('Apakah Anda yakin ingin menghapus riwayat transaksi ini?')) {
                return;
            }

            try {
                const url = '/subscriptions/cancel/' + orderId;
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Transaksi berhasil dihapus', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.error || 'Gagal menghapus transaksi', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat menghapus transaksi', 'error');
            }
        }
    </script>
    @endpush
</x-layouts.app>

