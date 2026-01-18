<x-layouts.app title="Pengaturan Auto-Assignment">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Auto-Assignment</h1>
        <p class="text-gray-600 mt-1">Atur penugasan otomatis laporan berdasarkan kategori</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Add New Assignment Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tambah/Update Auto-Assignment</h2>
        
        <form method="POST" action="{{ route('settings.auto-assignment.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Laporan</label>
                    <select name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ditugaskan ke</label>
                    <select name="assigned_user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Tidak ada (Manual Assignment)</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ old('assigned_user_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->getRoleDisplayName() }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Current Assignments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Auto-Assignment Aktif</h2>
        </div>

        @if($assignments->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="font-medium text-lg">Belum ada auto-assignment</p>
                <p class="text-sm mt-1">Tambahkan pengaturan di atas untuk mengaktifkan auto-assignment</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ditugaskan ke</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($assignments as $assignment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $categories[$assignment->category] ?? ucfirst($assignment->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($assignment->assignedUser)
                                        <div class="flex items-center">
                                            <img src="{{ $assignment->assignedUser->avatar_url }}" 
                                                 alt="{{ $assignment->assignedUser->name }}"
                                                 class="w-8 h-8 rounded-full mr-3"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($assignment->assignedUser->name) }}&color=7F9CF5&background=EBF4FF'">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $assignment->assignedUser->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $assignment->assignedUser->getRoleDisplayName() }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($assignment->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $assignment->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form method="POST" action="{{ route('settings.auto-assignment.destroy', $assignment) }}" 
                                          onsubmit="return confirm('Hapus auto-assignment untuk kategori {{ $categories[$assignment->category] ?? $assignment->category }}?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Cara Kerja Auto-Assignment</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Saat laporan baru masuk dengan kategori tertentu, sistem akan otomatis menugaskan ke staf yang sudah ditentukan</li>
                        <li>Staf yang ditugaskan akan menerima notifikasi dan email</li>
                        <li>Jika tidak ada auto-assignment untuk kategori tertentu, laporan akan menunggu penugasan manual</li>
                        <li>Anda dapat mengubah atau menghapus pengaturan kapan saja</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
