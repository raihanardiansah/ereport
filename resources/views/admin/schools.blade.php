<x-layouts.app title="Semua Sekolah">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Monitoring Sekolah</h1>
        <p class="text-gray-600 mt-1">Kelola dan pantau semua sekolah terdaftar</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Total Sekolah</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalSchools }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Aktif</p>
            <p class="text-2xl font-bold text-green-600">{{ $activeSchools }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Trial</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $trialSchools }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-sm text-gray-500">Total User</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500"
                    placeholder="Cari nama, email, atau NPSN...">
            </div>
            <div class="sm:w-40">
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="trial" {{ request('status') == 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary py-2">Filter</button>
        </form>
    </div>

    <!-- Schools Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Sekolah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">NPSN</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Users</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Terdaftar</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schools as $school)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ $school->name }}</p>
                                <p class="text-sm text-gray-500">{{ $school->email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-mono">{{ $school->npsn ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-900 font-semibold">{{ $school->users_count }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($school->subscription_status === 'active') bg-green-100 text-green-700
                                @elseif($school->subscription_status === 'trial') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($school->subscription_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $school->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.school.detail', $school) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada sekolah ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schools->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $schools->withQueryString()->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
