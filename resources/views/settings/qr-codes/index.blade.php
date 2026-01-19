<x-layouts.app title="QR Code Settings">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4m0 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1z M14 4m0 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1z M4 14m0 1a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1z M14 17h6 M17 14v6" />
                        </svg>
                    </div>
                    Pengaturan QR Code
                </h1>
                <p class="text-gray-600 mt-2">Kelola QR Code untuk pelaporan cepat berdasarkan lokasi</p>
            </div>
            <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-lg border border-indigo-200">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-indigo-700 font-medium">{{ $qrCodes->total() }} QR Code Aktif</span>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Create Form -->
            <div class="lg:col-span-1">
                <div
                    class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-lg border border-gray-200 p-6 sticky top-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Buat QR Code Baru</h2>
                    </div>

                    <form action="{{ route('settings.qr-codes.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <x-forms.label>Nama Lokasi</x-forms.label>
                            <x-forms.input name="location_name" placeholder="Contoh: Kantin, Toilet Lt 1" required />
                            <p class="text-xs text-gray-500 mt-1">Nama lokasi yang akan muncul otomatis di laporan.</p>
                        </div>

                        <div>
                            <x-forms.label>Kategori Default (Opsional)</x-forms.label>
                            <select name="default_category"
                                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors">
                                <option value="">- Pilih Kategori -</option>
                                @foreach(['perilaku', 'akademik', 'kehadiran', 'bullying', 'konseling', 'kesehatan', 'fasilitas', 'prestasi', 'keamanan', 'ekstrakurikuler', 'sosial', 'keuangan', 'kebersihan', 'kantin', 'transportasi', 'teknologi', 'guru', 'kurikulum', 'perpustakaan', 'laboratorium', 'olahraga', 'keagamaan', 'saran', 'lainnya'] as $cat)
                                    <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Kategori akan otomatis terisi jika dipilih.</p>
                        </div>

                        <div>
                            <x-forms.label>Deskripsi (Opsional)</x-forms.label>
                            <textarea name="description" rows="2"
                                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors"
                                placeholder="Catatan tambahan..."></textarea>
                        </div>

                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.02]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Generate QR Code
                        </button>
                    </form>
                </div>
            </div>

            <!-- List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Daftar QR Code Aktif
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lokasi / Kode</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kategori</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statistik</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($qrCodes as $qr)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded"
                                                        src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $qr->url }}"
                                                        alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $qr->location_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 font-mono">{{ $qr->code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($qr->default_category)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                                                    Last:
                                                    {{ $qr->last_scanned_at ? $qr->last_scanned_at->diffForHumans() : '-' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('settings.qr-codes.show', $qr) }}" target="_blank"
                                                    class="text-primary-600 hover:text-primary-900 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                    Print
                                                </a>
                                                <form action="{{ route('settings.qr-codes.destroy', $qr) }}" method="POST"
                                                    onsubmit="return confirm('Hapus QR Code ini? Link lama tidak akan berfungsi lagi.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16">
                                            <div class="text-center">
                                                <div
                                                    class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada QR Code</h3>
                                                <p class="text-gray-500 mb-4">Buat QR Code pertama Anda untuk memudahkan
                                                    pelaporan berdasarkan lokasi</p>
                                                <div
                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Gunakan form di sebelah kiri untuk membuat QR Code
                                                </div>
                                            </div>
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