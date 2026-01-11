<x-layouts.app title="Penanganan Siswa">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penanganan Siswa</h1>
            <p class="text-gray-600 text-sm mt-1">Kelola dan pantau kasus siswa</p>
        </div>
        <button onclick="document.getElementById('createCaseModal').classList.remove('hidden')" class="btn-primary">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Buat Kasus Baru
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kasus atau nama siswa..."
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\StudentCase::STATUSES as $key => $status)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="priority" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Prioritas</option>
                    @foreach(\App\Models\StudentCase::PRIORITIES as $key => $priority)
                        <option value="{{ $key }}" {{ request('priority') === $key ? 'selected' : '' }}>{{ $priority['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full btn-secondary py-2">Filter</button>
            </div>
        </form>
    </div>

    <!-- Cases List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($cases->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($cases as $case)
            <a href="{{ route('student-cases.show', $case) }}" class="block p-5 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-mono text-gray-500">{{ $case->case_number }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $case->priority_color }}">
                                {{ $case->priority_label }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $case->status_color }}">
                                {{ $case->status_label }}
                            </span>
                        </div>
                        <h3 class="font-medium text-gray-900">{{ $case->title }}</h3>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $case->student->name ?? 'Unknown' }}
                            </span>
                            <span>{{ $case->created_at->format('d/m/Y') }}</span>
                            <span>{{ $case->followUps()->count() }} tindak lanjut</span>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $cases->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Kasus</h3>
            <p class="text-gray-500">Mulai dengan membuat kasus penanganan siswa baru.</p>
        </div>
        @endif
    </div>

    <!-- Create Case Modal -->
    <div id="createCaseModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Buat Kasus Baru</h3>
                    <button onclick="document.getElementById('createCaseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{ route('student-cases.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kasus <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required maxlength="200" placeholder="Deskripsi singkat kasus..."
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ringkasan</label>
                    <textarea name="summary" rows="3" placeholder="Penjelasan lebih detail tentang kasus ini..."
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="low">🟢 Rendah</option>
                        <option value="medium" selected>🟡 Sedang</option>
                        <option value="high">🟠 Tinggi</option>
                        <option value="critical">🔴 Kritis</option>
                    </select>
                </div>
                
                <!-- Report Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Laporan untuk Digabung</label>
                    @if($availableReports->count() > 0)
                    <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-lg divide-y">
                        @foreach($availableReports as $report)
                        <label class="flex items-start gap-3 p-3 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="report_ids[]" value="{{ $report->id }}" 
                                class="mt-1 h-4 w-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 text-sm truncate">{{ $report->title }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($report->is_anonymous)
                                        @if(auth()->id() === $report->user_id)
                                            Anonim (Anda)
                                        @else
                                            Pengguna Anonim
                                        @endif
                                    @else
                                        {{ $report->user->name }}
                                    @endif
                                     • {{ $report->created_at->format('d/m/Y') }}
                                    @if($report->accusedUsers->count() > 0)
                                        • {{ $report->accusedUsers->count() }} terdakwa
                                    @endif
                                </p>
                            </div>
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $report->status === 'dikirim' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Laporan yang dipilih akan otomatis berubah status menjadi "Diproses"</p>
                    @else
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                        <p class="text-gray-500 text-sm">Tidak ada laporan yang tersedia untuk ditautkan</p>
                    </div>
                    @endif
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('createCaseModal').classList.add('hidden')" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Buat Kasus</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
