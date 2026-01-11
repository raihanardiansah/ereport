<x-layouts.app title="Laporan">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan</h1>
            <p class="text-gray-600 mt-1">
                @if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) || auth()->user()->isSuperAdmin())
                    Semua laporan sekolah
                @else
                    Laporan yang telah Anda kirim
                @endif
            </p>
        </div>
        @if(auth()->user()->hasAnyRole(['guru', 'siswa', 'staf_kesiswaan']))
        <a href="{{ route('reports.create') }}" class="btn-primary inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Laporan
        </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500"
                    placeholder="Cari judul atau isi laporan...">
            </div>
            <div class="sm:w-40">
                <select name="category" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Kategori</option>
                    <option value="perilaku" {{ request('category') == 'perilaku' ? 'selected' : '' }}>Perilaku</option>
                    <option value="akademik" {{ request('category') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                    <option value="kehadiran" {{ request('category') == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                    <option value="bullying" {{ request('category') == 'bullying' ? 'selected' : '' }}>Bullying</option>
                    <option value="konseling" {{ request('category') == 'konseling' ? 'selected' : '' }}>Konseling</option>
                    <option value="kesehatan" {{ request('category') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                    <option value="fasilitas" {{ request('category') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                    <option value="prestasi" {{ request('category') == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                    <option value="keamanan" {{ request('category') == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                    <option value="ekstrakurikuler" {{ request('category') == 'ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                    <option value="sosial" {{ request('category') == 'sosial' ? 'selected' : '' }}>Sosial</option>
                    <option value="keuangan" {{ request('category') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                    <option value="kebersihan" {{ request('category') == 'kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                    <option value="kantin" {{ request('category') == 'kantin' ? 'selected' : '' }}>Kantin</option>
                    <option value="transportasi" {{ request('category') == 'transportasi' ? 'selected' : '' }}>Transportasi</option>
                    <option value="teknologi" {{ request('category') == 'teknologi' ? 'selected' : '' }}>Teknologi</option>
                    <option value="guru" {{ request('category') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="kurikulum" {{ request('category') == 'kurikulum' ? 'selected' : '' }}>Kurikulum</option>
                    <option value="perpustakaan" {{ request('category') == 'perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                    <option value="laboratorium" {{ request('category') == 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                    <option value="olahraga" {{ request('category') == 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                    <option value="keagamaan" {{ request('category') == 'keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                    <option value="saran" {{ request('category') == 'saran' ? 'selected' : '' }}>Saran</option>
                    <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>
            <div class="sm:w-40">
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="ditindaklanjuti" {{ request('status') == 'ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary py-2">Filter</button>
        </form>
    </div>

    <!-- Reports List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($reports as $report)
            <a href="{{ route('reports.show', $report) }}" class="block p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <h3 class="font-semibold text-gray-900 truncate">{{ $report->title }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                @if($report->status === 'selesai') bg-green-100 text-green-700
                                @elseif($report->status === 'ditindaklanjuti') bg-blue-100 text-blue-700
                                @elseif($report->status === 'diproses') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($report->status) }}
                            </span>
                            @if($report->isEscalated())
                                {!! $report->escalation_badge !!}
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ Str::limit($report->content, 150) }}</p>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ ucfirst($report->category) }}
                            </span>
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $report->user->name }}
                            </span>
                            <span>{{ $report->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="ml-4 flex flex-col items-end">
                        @php $classification = $report->manual_classification ?? $report->ai_classification; @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @if($classification === 'positif') bg-green-100 text-green-700
                            @elseif($classification === 'negatif') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($classification ?? 'Netral') }}
                            @if($report->manual_classification)
                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" title="Dikoreksi manual">
                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                            @endif
                        </span>
                        @if($report->attachment_path)
                            <span class="mt-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium text-lg">Belum ada laporan</p>
                <p class="text-sm mt-1">Laporan yang dikirim akan muncul di sini</p>
            </div>
            @endforelse
        </div>

        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $reports->withQueryString()->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
