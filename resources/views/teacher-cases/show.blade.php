<x-layouts.app title="{{ $teacherCase->case_number }}">
    <div class="mb-6">
        <a href="{{ route('teacher-cases.index') }}" class="text-primary-600 hover:text-primary-700 text-sm inline-flex items-center mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Kasus
        </a>
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-sm font-mono text-gray-500">{{ $teacherCase->case_number }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $teacherCase->priority_color }}">
                        {{ $teacherCase->priority_label }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $teacherCase->status_color }}">
                        {{ $teacherCase->status_label }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $teacherCase->title }}</h1>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Case Summary -->
            @if($teacherCase->summary)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-3">Ringkasan Kasus</h4>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $teacherCase->summary }}</p>
            </div>
            @endif

            <!-- Resolution (if resolved) -->
            @if($teacherCase->isResolved())
            <div class="bg-green-50 rounded-xl border border-green-200 p-6">
                <div class="flex items-center mb-3">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="font-medium text-green-800">Kasus Terselesaikan</h4>
                </div>
                <p class="text-sm text-green-700 mb-2"><strong>Hasil:</strong> {{ $teacherCase->outcome_label }}</p>
                <p class="text-green-700">{{ $teacherCase->resolution_notes }}</p>
                <p class="text-sm text-green-600 mt-2">Diselesaikan pada {{ $teacherCase->resolved_at->format('d/m/Y H:i') }}</p>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-medium text-gray-900">Timeline Kasus</h4>
                    @if(!$teacherCase->isResolved())
                    <button onclick="document.getElementById('addFollowUpModal').classList.remove('hidden')" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        + Tambah Tindak Lanjut
                    </button>
                    @endif
                </div>

                @php $timeline = $teacherCase->timeline; @endphp
                
                @if($timeline->count() > 0)
                <div class="space-y-4">
                    @foreach($timeline as $event)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                                {{ $event['type'] === 'report' ? 'bg-red-100' : 'bg-blue-100' }}">
                                {{ $event['type'] === 'report' ? '📝' : $event['data']->type_icon }}
                            </div>
                            @if(!$loop->last)
                            <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                            @endif
                        </div>
                        <div class="flex-1 pb-6">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-medium text-gray-900">{{ $event['title'] }}</span>
                                <span class="text-xs text-gray-500">
                                    {{ $event['type'] === 'report' ? $event['data']->created_at->format('d/m/Y') : $event['data']->follow_up_date->format('d/m/Y') }}
                                </span>
                            </div>
                            @if($event['type'] === 'report')
                                <p class="text-sm text-gray-600">{{ Str::limit($event['data']->content, 150) }}</p>
                                <a href="{{ route('reports.show', $event['data']) }}" class="text-xs text-primary-600 hover:underline mt-1 inline-block">Lihat Laporan →</a>
                            @else
                                <p class="text-sm text-gray-600">{{ $event['data']->notes }}</p>
                                @if($event['data']->outcome)
                                <p class="text-xs text-gray-500 mt-1"><strong>Hasil:</strong> {{ $event['data']->outcome }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">oleh {{ $event['data']->user->name ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <p>Belum ada aktivitas dalam kasus ini.</p>
                </div>
                @endif
            </div>

            <!-- Link Reports -->
            @if($availableReports->count() > 0 && !$teacherCase->isResolved())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Tautkan Laporan Terkait</h4>
                <div class="space-y-2">
                    @foreach($availableReports->take(5) as $report)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-sm text-gray-900">{{ $report->title }}</p>
                            <p class="text-xs text-gray-500">{{ $report->created_at->format('d/m/Y') }} • {{ ucfirst($report->getCategory()) }}</p>
                        </div>
                        <form method="POST" action="{{ route('teacher-cases.link-report', $teacherCase) }}">
                            @csrf
                            <input type="hidden" name="report_id" value="{{ $report->id }}">
                            <button type="submit" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Tautkan</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Teacher Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Informasi Guru</h4>
                @if($teacherCase->teacher)
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                        <span class="text-indigo-700 text-xl font-semibold">{{ strtoupper(substr($teacherCase->teacher->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $teacherCase->teacher->name }}</p>
                        <p class="text-sm text-gray-500">{{ $teacherCase->teacher?->getRoleDisplayName() ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="space-y-2 mb-4">
                    <div>
                        <span class="text-xs text-gray-500">Email</span>
                        <p class="text-sm text-gray-900">{{ $teacherCase->teacher->email ?? 'N/A' }}</p>
                    </div>
                </div>
                <a href="{{ route('teacher-cases.teacher-profile', $teacherCase->teacher) }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    Lihat Profil Lengkap →
                </a>
                @else
                    @php
                        // Get accused teachers/staff from linked reports
                        $accusedTeachers = collect();
                        foreach($teacherCase->reports as $report) {
                            $teachers = $report->accusedUsers->whereIn('role', ['guru', 'staf_kesiswaan']);
                            $accusedTeachers = $accusedTeachers->merge($teachers);
                        }
                        $accusedTeachers = $accusedTeachers->unique('id');
                    @endphp
                    
                    @if($accusedTeachers->count() > 0)
                    <p class="text-xs text-indigo-600 mb-3">Terdeteksi dari laporan terkait:</p>
                    <div class="space-y-3">
                        @foreach($accusedTeachers->take(3) as $teacher)
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                <span class="text-indigo-700 font-semibold">{{ strtoupper(substr($teacher->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $teacher->name }}</p>
                                <p class="text-xs text-gray-500">{{ $teacher->getRoleDisplayName() }}</p>
                            </div>
                        </div>
                        @endforeach
                        @if($accusedTeachers->count() > 3)
                        <p class="text-xs text-gray-500">+{{ $accusedTeachers->count() - 3 }} guru lainnya</p>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">Kasus umum</p>
                        <p class="text-xs text-gray-400">Belum ditentukan guru terkait</p>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Case Status Update -->
            @if(!$teacherCase->isResolved())
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Update Status</h4>
                <form method="POST" action="{{ route('teacher-cases.update', $teacherCase) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                        @foreach(\App\Models\TeacherCase::STATUSES as $key => $status)
                            @if(!in_array($key, ['resolved', 'closed']))
                            <option value="{{ $key }}" {{ $teacherCase->status === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Resolve Case -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Selesaikan Kasus</h4>
                <form method="POST" action="{{ route('teacher-cases.resolve', $teacherCase) }}">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Hasil</label>
                            <select name="resolution_outcome" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                                <option value="">Pilih Hasil</option>
                                @foreach(\App\Models\TeacherCase::OUTCOMES as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Catatan Penyelesaian</label>
                            <textarea name="resolution_notes" rows="3" required minlength="10" placeholder="Jelaskan bagaimana kasus ini diselesaikan..."
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full btn-primary py-2 text-sm">
                            ✓ Tandai Selesai
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Handler Info -->
            @if($teacherCase->handler)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Ditangani Oleh</h4>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                        <span class="text-blue-700 font-semibold">{{ strtoupper(substr($teacherCase->handler->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $teacherCase->handler->name }}</p>
                        <p class="text-sm text-gray-500">{{ $teacherCase->handler->getRoleDisplayName() }}</p>
                    </div>
                </div>

                @if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah']) || auth()->user()->isSuperAdmin())
                <div class="pt-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('teacher-cases.reassign', $teacherCase) }}">
                        @csrf
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tugaskan Ulang</label>
                        <div class="space-y-2">
                            <select name="handler_id" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                                @php
                                    $handlers = \App\Models\User::where('school_id', auth()->user()->school_id)
                                        ->whereIn('role', ['admin_sekolah', 'manajemen_sekolah'])
                                        ->where('id', '!=', $teacherCase->handler_id)
                                        ->orderBy('name')
                                        ->get();
                                @endphp
                                <option value="">-- Pilih Handler --</option>
                                @foreach($handlers as $handler)
                                    <option value="{{ $handler->id }}">{{ $handler->name }} ({{ $handler->getRoleDisplayName() }})</option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                Ubah Handler
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>

    <!-- Add Follow-Up Modal -->
    <div id="addFollowUpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Tambah Tindak Lanjut</h3>
                    <button onclick="document.getElementById('addFollowUpModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{ route('teacher-cases.follow-ups.store', $teacherCase) }}" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="follow_up_date" required value="{{ now()->format('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                        <select name="type" required class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                            @foreach(\App\Models\CaseTeacherFollowUp::TYPES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="3" required minlength="10" placeholder="Apa yang dibahas atau diamati..."
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hasil</label>
                    <textarea name="outcome" rows="2" placeholder="Hasil dari tindak lanjut..."
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('addFollowUpModal').classList.add('hidden')" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
