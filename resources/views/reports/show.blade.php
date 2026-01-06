<x-layouts.app title="{{ $report->title }}">
    <div class="mb-6">
        <a href="{{ route('reports.index') }}" class="text-primary-600 hover:text-primary-700 text-sm inline-flex items-center mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Laporan
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ $report->title }}</h1>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Report Content -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                        {{ ucfirst($report->category) }}
                    </span>
                    <span class="text-sm text-gray-500">{{ $report->created_at->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $report->content }}</p>
                </div>

                @if($report->attachment_path)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Lampiran</h4>
                    <a href="{{ Storage::url($report->attachment_path) }}" target="_blank" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm text-gray-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        Lihat Lampiran
                    </a>
                </div>
                @endif
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Komentar & Tindak Lanjut
                    <span class="ml-2 bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                        {{ $report->comments()->visibleTo(auth()->user())->count() }}
                    </span>
                </h4>

                <!-- Comment Form (Staff Only) -->
                @if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) || auth()->user()->isSuperAdmin())
                <form method="POST" action="{{ route('reports.comments.store', $report) }}" class="mb-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                                <select name="type" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                                    <option value="comment">💬 Komentar</option>
                                    <option value="follow_up">📋 Tindak Lanjut</option>
                                    <option value="counseling_note">📝 Catatan Konseling</option>
                                    <option value="action_taken">✅ Aksi yang Diambil</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_private" value="1" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-600">🔒 Catatan Privat (hanya staff)</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <textarea name="content" rows="3" placeholder="Tulis komentar atau catatan tindak lanjut..."
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 resize-none @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary text-sm py-2 px-4">
                                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Kirim
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                <!-- Comments Timeline -->
                @php
                    $comments = $report->comments()->visibleTo(auth()->user())->with('user')->latest()->get();
                @endphp

                @if($comments->count() > 0)
                <div class="space-y-4">
                    @foreach($comments as $comment)
                    <div class="flex space-x-3 {{ $comment->is_private ? 'bg-amber-50 -mx-2 px-2 py-2 rounded-lg border border-amber-100' : '' }}">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                <span class="text-primary-700 text-sm font-semibold">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-900 text-sm">{{ $comment->user->name }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $comment->type_color }}">
                                        {!! $comment->type_icon !!}
                                        <span class="ml-1">{{ $comment->type_label }}</span>
                                    </span>
                                    @if($comment->is_private)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">
                                        🔒 Privat
                                    </span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $comment->content }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-sm">Belum ada komentar</p>
                </div>
                @endif
            </div>

            <!-- Actions for Owner -->
            @if(auth()->id() === $report->user_id && $report->status === 'dikirim')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Aksi</h4>
                <button type="button" onclick="openDeleteModal()" class="btn-danger text-sm py-2">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Laporan
                </button>
                
                <!-- Delete Confirmation Form (hidden) -->
                <form id="deleteReportForm" method="POST" action="{{ route('reports.destroy', $report) }}" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Laporan?</h3>
                        <p class="text-gray-600 mb-6">Laporan "{{ Str::limit($report->title, 40) }}" akan dihapus permanen dan tidak dapat dikembalikan.</p>
                        <div class="flex gap-3 justify-center">
                            <button type="button" onclick="closeDeleteModal()" 
                                class="px-6 py-2.5 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
                                Batal
                            </button>
                            <button type="button" onclick="confirmDelete()" 
                                class="px-6 py-2.5 text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                                Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function openDeleteModal() {
                    document.getElementById('deleteModal').classList.remove('hidden');
                    document.getElementById('deleteModal').classList.add('flex');
                }
                
                function closeDeleteModal() {
                    document.getElementById('deleteModal').classList.remove('flex');
                    document.getElementById('deleteModal').classList.add('hidden');
                }
                
                function confirmDelete() {
                    document.getElementById('deleteReportForm').submit();
                }
                
                // Close on backdrop click
                document.getElementById('deleteModal').addEventListener('click', function(e) {
                    if (e.target === this) closeDeleteModal();
                });
                
                // Close on Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeDeleteModal();
                });
            </script>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Status Laporan</h4>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                    @if($report->status === 'selesai') bg-green-100 text-green-700
                    @elseif($report->status === 'ditindaklanjuti') bg-blue-100 text-blue-700
                    @elseif($report->status === 'diproses') bg-yellow-100 text-yellow-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($report->status) }}
                </span>

                @if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) || auth()->user()->isSuperAdmin())
                <form method="POST" action="{{ route('reports.status', $report) }}" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <label class="block text-sm text-gray-600 mb-2">Ubah Status:</label>
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                        <option value="dikirim" {{ $report->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="diproses" {{ $report->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="ditindaklanjuti" {{ $report->status === 'ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                        <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </form>
                @endif
            </div>

            <!-- Classification Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Analisis AI
                </h4>
                
                <!-- Sentiment Section -->
                <div class="mb-4 pb-4 border-b border-gray-100">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Sentimen</p>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">AI Deteksi:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @if($report->ai_classification === 'positif') bg-green-100 text-green-700
                            @elseif($report->ai_classification === 'negatif') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            🤖 {{ ucfirst($report->ai_classification ?? 'Netral') }}
                        </span>
                    </div>

                    @if($report->manual_classification)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Koreksi Manual:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @if($report->manual_classification === 'positif') bg-green-100 text-green-700
                            @elseif($report->manual_classification === 'negatif') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            {{ ucfirst($report->manual_classification) }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Category Section -->
                <div class="mb-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Kategori</p>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">AI Deteksi:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                            🤖 {{ ucfirst($report->ai_category ?? 'Tidak terdeteksi') }}
                        </span>
                    </div>

                    @if($report->manual_category)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Koreksi Manual:</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            {{ ucfirst($report->manual_category) }}
                        </span>
                    </div>
                    @endif
                </div>

                @if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) || auth()->user()->isSuperAdmin())
                <form method="POST" action="{{ route('reports.classification', $report) }}" class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                    @csrf
                    @method('PATCH')
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Koreksi Manual</p>
                    
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Sentimen:</label>
                        <select name="manual_classification"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                            <option value="">-- Biarkan --</option>
                            <option value="positif" {{ $report->manual_classification === 'positif' ? 'selected' : '' }}>Positif</option>
                            <option value="negatif" {{ $report->manual_classification === 'negatif' ? 'selected' : '' }}>Negatif</option>
                            <option value="netral" {{ $report->manual_classification === 'netral' ? 'selected' : '' }}>Netral</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Kategori:</label>
                        <select name="manual_category"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500">
                            <option value="">-- Biarkan --</option>
                            <option value="perilaku" {{ $report->manual_category === 'perilaku' ? 'selected' : '' }}>Perilaku</option>
                            <option value="akademik" {{ $report->manual_category === 'akademik' ? 'selected' : '' }}>Akademik</option>
                            <option value="kehadiran" {{ $report->manual_category === 'kehadiran' ? 'selected' : '' }}>Kehadiran</option>
                            <option value="bullying" {{ $report->manual_category === 'bullying' ? 'selected' : '' }}>Bullying</option>
                            <option value="konseling" {{ $report->manual_category === 'konseling' ? 'selected' : '' }}>Konseling</option>
                            <option value="kesehatan" {{ $report->manual_category === 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="fasilitas" {{ $report->manual_category === 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="prestasi" {{ $report->manual_category === 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                            <option value="keamanan" {{ $report->manual_category === 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                            <option value="ekstrakurikuler" {{ $report->manual_category === 'ekstrakurikuler' ? 'selected' : '' }}>Ekstrakurikuler</option>
                            <option value="sosial" {{ $report->manual_category === 'sosial' ? 'selected' : '' }}>Sosial</option>
                            <option value="keuangan" {{ $report->manual_category === 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                            <option value="kebersihan" {{ $report->manual_category === 'kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                            <option value="kantin" {{ $report->manual_category === 'kantin' ? 'selected' : '' }}>Kantin</option>
                            <option value="transportasi" {{ $report->manual_category === 'transportasi' ? 'selected' : '' }}>Transportasi</option>
                            <option value="teknologi" {{ $report->manual_category === 'teknologi' ? 'selected' : '' }}>Teknologi</option>
                            <option value="guru" {{ $report->manual_category === 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="kurikulum" {{ $report->manual_category === 'kurikulum' ? 'selected' : '' }}>Kurikulum</option>
                            <option value="perpustakaan" {{ $report->manual_category === 'perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                            <option value="laboratorium" {{ $report->manual_category === 'laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                            <option value="olahraga" {{ $report->manual_category === 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                            <option value="keagamaan" {{ $report->manual_category === 'keagamaan' ? 'selected' : '' }}>Keagamaan</option>
                            <option value="saran" {{ $report->manual_category === 'saran' ? 'selected' : '' }}>Saran</option>
                            <option value="lainnya" {{ $report->manual_category === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-primary-500 text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-colors">
                        Simpan Koreksi
                    </button>
                </form>
                @endif
            </div>

            <!-- Reporter Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Pengirim</h4>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                        <span class="text-primary-700 font-semibold">{{ strtoupper(substr($report->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $report->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $report->user->getRoleDisplayName() }}</p>
                    </div>
                </div>
            </div>

            <!-- Accused Persons (Multi-accused support) -->
            @php
                $accusedUsers = $report->accusedUsers;
            @endphp
            @if($accusedUsers->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">
                    Orang yang Dilaporkan
                    <span class="text-xs font-normal text-gray-500">({{ $accusedUsers->count() }} orang)</span>
                </h4>
                <div class="space-y-3">
                    @foreach($accusedUsers as $accused)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                <span class="text-red-700 text-sm font-semibold">{{ strtoupper(substr($accused->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $accused->name }}</p>
                                <p class="text-xs text-gray-500">{{ $accused->getRoleDisplayName() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center gap-1 text-xs">
                                <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded">+{{ $accused->positive_index }}</span>
                                <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded">{{ $accused->neutral_index }}</span>
                                <span class="px-1.5 py-0.5 bg-red-100 text-red-700 rounded">-{{ $accused->negative_index }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Indeks</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-medium text-gray-900 mb-4">Orang yang Dilaporkan</h4>
                <p class="text-sm text-gray-500">Laporan umum (tidak ada individu spesifik yang dilaporkan)</p>
                <p class="text-xs text-gray-400 mt-2">Laporan ini tidak mempengaruhi indeks siapapun.</p>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>

