<x-layouts.app title="Profil {{ $teacher->name }}">
    <div class="mb-6">
        <a href="{{ route('teacher-cases.index') }}" class="text-primary-600 hover:text-primary-700 text-sm inline-flex items-center mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Kasus
        </a>
    </div>

    <!-- Teacher Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center mr-6">
                    <span class="text-white text-3xl font-bold">{{ strtoupper(substr($teacher->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $teacher->name }}</h1>
                    <p class="text-gray-500 dark:text-gray-400">{{ $teacher->username }} • {{ $teacher->email }}</p>
                    <div class="flex items-center gap-4 mt-2 text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                            {{ $teacher->getRoleDisplayName() }}
                        </span>
                        <span class="text-gray-600 dark:text-gray-400">NIP: {{ $teacher->nip_nisn ?? '-' }}</span>
                        <span class="text-gray-600 dark:text-gray-400">{{ $teacher->phone ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('pdf.teacher', $teacher) }}" class="btn-primary inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download Raport
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_cases'] }}</p>
            <p class="text-xs text-gray-500">Total Kasus</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['open_cases'] }}</p>
            <p class="text-xs text-gray-500">Kasus Aktif</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['resolved_cases'] }}</p>
            <p class="text-xs text-gray-500">Terselesaikan</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_reports'] }}</p>
            <p class="text-xs text-gray-500">Total Laporan</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $stats['negative_reports'] }}</p>
            <p class="text-xs text-gray-500">Laporan Negatif</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Cases History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Riwayat Kasus</h3>
            @if($cases->count() > 0)
            <div class="space-y-3">
                @foreach($cases as $case)
                <a href="{{ route('teacher-cases.show', $case) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-mono text-gray-500">{{ $case->case_number }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $case->status_color }}">
                            {{ $case->status_label }}
                        </span>
                    </div>
                    <p class="font-medium text-gray-900 text-sm">{{ $case->title }}</p>
                    <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                        <span>{{ $case->created_at->format('d/m/Y') }}</span>
                        <span>{{ $case->followUps->count() }} tindak lanjut</span>
                    </div>
                    @if($case->isResolved())
                    <div class="mt-2 text-xs text-green-600">
                        ✓ {{ $case->outcome_label }}
                    </div>
                    @endif
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <p>Belum ada riwayat kasus.</p>
            </div>
            @endif
        </div>

        <!-- Reports Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Laporan Terkait</h3>
            @if($reports->count() > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($reports as $report)
                <a href="{{ route('reports.show', $report) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-500">{{ $report->created_at->format('d/m/Y') }}</span>
                        @php $classification = $report->getClassification(); @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $classification === 'positif' ? 'bg-green-100 text-green-700' : ($classification === 'negatif' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($classification ?? 'Netral') }}
                        </span>
                    </div>
                    <p class="font-medium text-gray-900 text-sm">{{ $report->title }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($report->getCategory()) }} • {{ ucfirst($report->status) }}</p>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <p>Belum ada laporan terkait.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Behavioral Trend (Simple Visual) -->
    @if($reports->count() >= 3)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
        <h3 class="font-semibold text-gray-900 mb-4">Tren Perilaku</h3>
        <div class="flex items-center justify-center gap-2">
            @php
                $recentReports = $reports->take(10)->reverse();
                $trend = [];
                foreach($recentReports as $r) {
                    $c = $r->getClassification();
                    $trend[] = $c;
                }
            @endphp
            @foreach($trend as $t)
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg
                {{ $t === 'positif' ? 'bg-green-100' : ($t === 'negatif' ? 'bg-red-100' : 'bg-gray-100') }}">
                {{ $t === 'positif' ? '😊' : ($t === 'negatif' ? '😟' : '😐') }}
            </div>
            @endforeach
            <span class="text-sm text-gray-500 ml-4">→ Terbaru</span>
        </div>
        @php
            $positiveCount = collect($trend)->filter(fn($t) => $t === 'positif')->count();
            $negativeCount = collect($trend)->filter(fn($t) => $t === 'negatif')->count();
        @endphp
        <p class="text-center text-sm text-gray-600 mt-4">
            @if($positiveCount > $negativeCount)
            <span class="text-green-600 font-medium">📈 Tren membaik</span> - Lebih banyak laporan positif akhir-akhir ini.
            @elseif($negativeCount > $positiveCount)
            <span class="text-red-600 font-medium">📉 Perlu perhatian</span> - Beberapa laporan negatif tercatat.
            @else
            <span class="text-gray-600 font-medium">→ Stabil</span> - Tidak ada perubahan signifikan.
            @endif
        </p>
    </div>
    @endif
</x-layouts.app>
