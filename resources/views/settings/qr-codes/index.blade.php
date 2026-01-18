<x-layouts.app title="QR Code Settings">
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengaturan QR Code</h1>
            <p class="text-gray-600 mt-1">Kelola QR Code untuk pelaporan cepat berdasarkan lokasi.</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Create Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Buat QR Code Baru</h2>
                    
                    <form action="{{ route('settings.qr-codes.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <x-forms.label>Nama Lokasi</x-forms.label>
                            <x-forms.input name="location_name" placeholder="Contoh: Kantin, Toilet Lt 1" required />
                            <p class="text-xs text-gray-500 mt-1">Nama lokasi yang akan muncul otomatis di laporan.</p>
                        </div>

                        <div>
                            <x-forms.label>Kategori Default (Opsional)</x-forms.label>
                            <select name="default_category" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors">
                                <option value="">- Pilih Kategori -</option>
                                @foreach(['perilaku', 'akademik', 'kehadiran', 'bullying', 'konseling', 'kesehatan', 'fasilitas', 'prestasi', 'keamanan', 'ekstrakurikuler', 'sosial', 'keuangan', 'kebersihan', 'kantin', 'transportasi', 'teknologi', 'guru', 'kurikulum', 'perpustakaan', 'laboratorium', 'olahraga', 'keagamaan', 'saran', 'lainnya'] as $cat)
                                    <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Kategori akan otomatis terisi jika dipilih.</p>
                        </div>

                        <div>
                            <x-forms.label>Deskripsi (Opsional)</x-forms.label>
                            <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors" placeholder="Catatan tambahan..."></textarea>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            Generate QR Code
                        </button>
                    </form>
                </div>
            </div>

            <!-- List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Daftar QR Code Aktif</h2>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi / Kode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statistik</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($qrCodes as $qr)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded" src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $qr->url }}" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $qr->location_name }}</div>
                                                <div class="text-sm text-gray-500 font-mono">{{ $qr->code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($qr->default_category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($qr->default_category) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex flex-col">
                                            <span>Scanning: <strong>{{ $qr->scan_count }}</strong></span>
                                            <span class="text-xs">
                                                Last: {{ $qr->last_scanned_at ? $qr->last_scanned_at->diffForHumans() : '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('settings.qr-codes.show', $qr) }}" target="_blank" class="text-primary-600 hover:text-primary-900 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                                Print
                                            </a>
                                            <form action="{{ route('settings.qr-codes.destroy', $qr) }}" method="POST" onsubmit="return confirm('Hapus QR Code ini? Link lama tidak akan berfungsi lagi.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        Belum ada QR Code dibuat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($qrCodes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $qrCodes->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
