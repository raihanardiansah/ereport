<x-layouts.app title="Semua Kasus Siswa">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Semua Kasus Siswa</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Pantau kasus penanganan siswa dari semua sekolah</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Kasus</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalCases }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Terbuka</p>
            <p class="text-2xl font-bold text-blue-600">{{ $openCases }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Dalam Penanganan</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $inProgressCases }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <p class="text-sm text-gray-500 dark:text-gray-400">Terselesaikan</p>
            <p class="text-2xl font-bold text-green-600">{{ $resolvedCases }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kasus..."
                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <div>
                <select name="school_id" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Semua Sekolah</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\StudentCase::STATUSES as $key => $status)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="priority" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="">Semua Prioritas</option>
                    @foreach(\App\Models\StudentCase::PRIORITIES as $key => $priority)
                        <option value="{{ $key }}" {{ request('priority') === $key ? 'selected' : '' }}>{{ $priority['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="w-full btn-primary py-2">Filter</button>
            </div>
        </form>
    </div>

    <!-- Cases Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kasus</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Sekolah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Prioritas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($cases as $case)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ Str::limit($case->title, 40) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $case->case_number }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.school.detail', $case->school) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                {{ $case->school->name ?? '-' }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $case->student->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $case->priority_color }}">
                                {{ $case->priority_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $case->status_color }}">
                                {{ $case->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-sm">{{ $case->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada kasus ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cases->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $cases->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <!-- Export Button -->
    <div class="mt-4">
        <a href="{{ route('admin.export.csv', 'cases') }}" class="btn-secondary text-sm">
            📁 Export Semua Kasus (CSV)
        </a>
    </div>
</x-layouts.app>
