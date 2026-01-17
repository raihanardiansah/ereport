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
    <!-- Filters -->
    <div x-data="{ showAdvanced: {{ request('date_start') || request('date_end') || request('reporter_id') ? 'true' : 'false' }} }" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET">
            <div class="flex flex-col lg:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500"
                        placeholder="Cari judul atau isi laporan...">
                </div>
                
                <div class="flex gap-4">
                    <div class="w-40">
                        <select name="urgency" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <option value="">Semua Urgency</option>
                            <option value="critical" {{ request('urgency') == 'critical' ? 'selected' : '' }}>🚨 Critical</option>
                            <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>⚠️ High</option>
                            <option value="normal" {{ request('urgency') == 'normal' ? 'selected' : '' }}>Normal</option>
                        </select>
                    </div>
                    <div class="w-40">
                        <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <option value="">Semua Status</option>
                            <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="ditindaklanjuti" {{ request('status') == 'ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>
                
                <button type="button" @click="showAdvanced = !showAdvanced" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span>Filter</span>
                </button>
                <button type="submit" class="btn-secondary py-2">Cari</button>
            </div>

            <!-- Advanced Filters Section -->
            <div x-show="showAdvanced" class="pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-4 gap-4" style="display: none;">
                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="">Semua Kategori</option>
                        <option value="perilaku" {{ request('category') == 'perilaku' ? 'selected' : '' }}>Perilaku</option>
                        <option value="akademik" {{ request('category') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                        <option value="kehadiran" {{ request('category') == 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                        <option value="bullying" {{ request('category') == 'bullying' ? 'selected' : '' }}>Bullying</option>
                        <!-- ... other categories ... -->
                        <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_start" value="{{ request('date_start') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_end" value="{{ request('date_end') }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                </div>

                <!-- Reporter Filter (Admin Only) -->
                @if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) || auth()->user()->isSuperAdmin())
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Pelapor</label>
                    <select name="reporter_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <option value="">Semua Pelapor</option>
                        @foreach($reporters ?? [] as $reporter)
                            <option value="{{ $reporter->id }}" {{ request('reporter_id') == $reporter->id ? 'selected' : '' }}>
                                {{ Str::limit($reporter->name, 20) }} ({{ $reporter->getRoleDisplayName() }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports List -->
    <div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 800)" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            <!-- Skeleton Loading State -->
            <template x-if="loading">
                <div>
                     @for($i = 0; $i < 5; $i++)
                     <div class="p-4 sm:p-6 block">
                        <div class="flex sm:items-start sm:justify-between sm:gap-6 mb-4">
                             <div class="flex-1">
                                 <div class="flex items-center gap-3 mb-2">
                                     <div class="skeleton h-5 w-48 rounded"></div>
                                     <div class="skeleton h-5 w-20 rounded-full"></div>
                                 </div>
                                 <div class="space-y-2 mb-3">
                                     <div class="skeleton h-4 w-full rounded"></div>
                                     <div class="skeleton h-4 w-3/4 rounded"></div>
                                 </div>
                                 <div class="flex items-center gap-4">
                                     <div class="skeleton h-4 w-24 rounded"></div>
                                     <div class="skeleton h-4 w-32 rounded"></div>
                                 </div>
                             </div>
                             <div class="skeleton h-4 w-24 rounded"></div>
                        </div>
                     </div>
                     @endfor
                </div>
            </template>

            <!-- Real Content -->
            <template x-if="!loading">
                <div>
                    @forelse($reports as $report)
            <a href="{{ route('reports.show', $report) }}" class="block p-4 sm:p-6 hover:bg-gray-50 transition-colors">
                <!-- Desktop Layout -->
                <div class="hidden sm:flex sm:items-start sm:justify-between sm:gap-6">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-gray-900 text-base">{{ $report->title }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium shrink-0
                                @if($report->status === 'selesai') bg-green-100 text-green-700
                                @elseif($report->status === 'ditindaklanjuti') bg-blue-100 text-blue-700
                                @elseif($report->status === 'diproses') bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($report->status) }}
                            </span>
                            
                            <!-- Urgency Badge -->
                            @if($report->urgency === 'critical')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-600 text-white shrink-0 animate-pulse">
                                    🚨 KRITIS
                                </span>
                            @elseif($report->urgency === 'high')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-800 shrink-0">
                                    ⚠️ TINGGI
                                </span>
                            @endif

                            @if($report->isEscalated())
                                {!! $report->escalation_badge !!}
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ Str::limit($report->content, 150) }}</p>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                {{ ucfirst($report->category) }}
                            </span>
                            <span class="inline-flex items-center">
                                @if($report->is_anonymous)
                                    <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    @if(auth()->id() === $report->user_id)Anonim (Anda)@else Pengguna Anonim @endif
                                @else
                                    <img src="{{ $report->user->avatar_url }}" 
                                         alt="{{ $report->user->name }}" 
                                         class="w-5 h-5 rounded-full object-cover mr-2"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($report->user->name) }}&color=7F9CF5&background=EBF4FF'">
                                    {{ $report->user->name }}
                                @endif
                            </span>
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $report->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 shrink-0">
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
                            <span class="text-gray-400" title="Ada Lampiran">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Mobile Layout -->
                <div class="sm:hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            @if($report->status === 'selesai') bg-green-100 text-green-700
                            @elseif($report->status === 'ditindaklanjuti') bg-blue-100 text-blue-700
                            @elseif($report->status === 'diproses') bg-yellow-100 text-yellow-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($report->status) }}
                        </span>
                        
                        <!-- Urgency Badge Mobile -->
                        @if($report->urgency === 'critical')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-600 text-white animate-pulse">
                                🚨
                            </span>
                        @elseif($report->urgency === 'high')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-800">
                                ⚠️
                            </span>
                        @endif

                        @php $classification = $report->manual_classification ?? $report->ai_classification; @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            @if($classification === 'positif') bg-green-100 text-green-700
                            @elseif($classification === 'negatif') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($classification ?? 'Netral') }}
                        </span>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $report->title }}</h3>
                    <p class="text-gray-600 text-sm line-clamp-2 mb-3">{{ Str::limit($report->content, 100) }}</p>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500">
                        <span class="inline-flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ ucfirst($report->category) }}
                        </span>
                        <span class="inline-flex items-center">
                            @if($report->is_anonymous)
                                <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                @if(auth()->id() === $report->user_id)Anda @else Anonim @endif
                            @else
                                <img src="{{ $report->user->avatar_url }}" 
                                     alt="{{ $report->user->name }}" 
                                     class="w-4 h-4 rounded-full object-cover mr-1"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($report->user->name) }}&color=7F9CF5&background=EBF4FF'">
                                {{ Str::limit($report->user->name, 15) }}
                            @endif
                        </span>
                        <span class="inline-flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $report->created_at->format('d/m/Y H:i') }}
                        </span>
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
