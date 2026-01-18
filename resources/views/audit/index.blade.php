<x-layouts.app title="Audit Trail">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Audit Trail</h1>
            <p class="text-gray-600 mt-1">Riwayat aktivitas dan akses data sensitif</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari Laporan/Action</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ID Laporan atau Action..." class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Action</label>
                <select name="action" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Action</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $action)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                <select name="user_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full btn-primary py-2 text-sm">Filter</button>
            </div>
        </form>
    </div>

    <!-- Audit Log Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Waktu</th>
                        <th class="px-6 py-3 font-medium">User</th>
                        <th class="px-6 py-3 font-medium">Action</th>
                        <th class="px-6 py-3 font-medium">Laporan</th>
                        <th class="px-6 py-3 font-medium">Detail</th>
                        <th class="px-6 py-3 font-medium">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($auditLogs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-500">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200 overflow-hidden shrink-0">
                                    <img src="{{ $log->user->avatar_url }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <span class="font-medium text-gray-900">{{ $log->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                @if(in_array($log->action, ['user_banned', 'report_deleted'])) bg-red-100 text-red-700
                                @elseif(in_array($log->action, ['status_changed', 'urgency_changed'])) bg-yellow-100 text-yellow-700
                                @elseif($log->action == 'report_viewed') bg-blue-50 text-blue-600
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucwords(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($log->report)
                            <a href="{{ route('reports.show', $log->report) }}" class="text-primary-600 hover:text-primary-700 hover:underline">
                                #{{ $log->report->id }} - {{ Str::limit($log->report->title, 20) }}
                            </a>
                            @else
                            <span class="text-gray-400 italic">Terhapus</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($log->old_values || $log->new_values)
                                <button onclick="alert('Detail perubahan:\nOld: {{ json_encode($log->old_values) }}\nNew: {{ json_encode($log->new_values) }}')" class="text-xs text-blue-600 hover:underline">
                                    Lihat Perubahan
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-xs">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada log audit.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($auditLogs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
