<x-layouts.app title="Audit Log">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Audit Log</h1>
        <p class="text-gray-600 mt-1">Rekam jejak aktivitas sistem</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="w-40">
                <select name="action" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    <option value="">Semua Aksi</option>
                    @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg" placeholder="Dari">
            </div>
            <div class="w-40">
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg" placeholder="Sampai">
            </div>
            <button type="submit" class="btn-secondary py-2">Filter</button>
        </form>
    </div>

    <!-- Logs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($logs as $log)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full {{ $log->action_color }} flex items-center justify-center mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $log->action_icon }}"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">{{ $log->description }}</p>
                            <span class="text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="flex items-center gap-4 mt-1 text-sm text-gray-500">
                            <span>{{ $log->user?->name ?? 'System' }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $log->action_color }}">
                                {{ ucfirst($log->action) }}
                            </span>
                            @if($log->ip_address)
                            <span>IP: {{ $log->ip_address }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="font-medium text-lg">Tidak ada log</p>
                <p class="text-sm mt-1">Aktivitas sistem akan muncul di sini</p>
            </div>
            @endforelse
        </div>

        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $logs->withQueryString()->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
