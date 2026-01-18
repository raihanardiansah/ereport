<x-layouts.app title="Audit Trail - {{ $report->title }}">
    <div class="mb-6">
        <a href="{{ route('reports.show', $report) }}" class="text-primary-600 hover:text-primary-700 inline-flex items-center mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Laporan
        </a>
        
        <h1 class="text-2xl font-bold text-gray-900">Audit Trail</h1>
        <p class="text-gray-600 mt-1">Riwayat akses dan perubahan pada laporan: <strong>{{ $report->title }}</strong></p>
    </div>

    <!-- Report Summary Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <div class="text-sm text-gray-500">Tingkat Urgensi</div>
                <div class="mt-1">
                    @if($report->urgency === 'critical')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-600 text-white">
                            KRITIS
                        </span>
                    @elseif($report->urgency === 'high')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-orange-100 text-orange-800">
                            TINGGI
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Normal
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Status</div>
                <div class="mt-1 font-medium text-gray-900">{{ ucfirst($report->status) }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Kategori</div>
                <div class="mt-1 font-medium text-gray-900">{{ ucfirst($report->category) }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Dibuat</div>
                <div class="mt-1 font-medium text-gray-900">{{ $report->created_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Timeline -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Riwayat Aktivitas ({{ $auditLogs->total() }})</h2>
        </div>

        @if($auditLogs->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium text-lg">Belum ada aktivitas</p>
            </div>
        @else
            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($auditLogs as $index => $log)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                @if($log->action === 'created') bg-green-500
                                                @elseif($log->action === 'viewed') bg-blue-500
                                                @elseif($log->action === 'status_changed') bg-yellow-500
                                                @elseif($log->action === 'commented') bg-purple-500
                                                @elseif($log->action === 'assigned') bg-indigo-500
                                                @elseif($log->action === 'exported') bg-pink-500
                                                @else bg-gray-500
                                                @endif">
                                                @if($log->action === 'created')
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($log->action === 'viewed')
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                @elseif($log->action === 'status_changed')
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $log->user->name }}</span>
                                                    <span class="text-sm text-gray-500">{{ $log->user->getRoleDisplayName() }}</span>
                                                </div>
                                                <time class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }}</time>
                                            </div>
                                            
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($log->action === 'created') bg-green-100 text-green-800
                                                    @elseif($log->action === 'viewed') bg-blue-100 text-blue-800
                                                    @elseif($log->action === 'status_changed') bg-yellow-100 text-yellow-800
                                                    @elseif($log->action === 'commented') bg-purple-100 text-purple-800
                                                    @elseif($log->action === 'assigned') bg-indigo-100 text-indigo-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                                                </span>
                                            </div>
                                            
                                            @if($log->description)
                                                <p class="mt-2 text-sm text-gray-700">{{ $log->description }}</p>
                                            @endif
                                            
                                            @if($log->metadata)
                                                <div class="mt-2 bg-gray-50 rounded-lg p-3">
                                                    <div class="text-xs font-medium text-gray-500 mb-1">Detail:</div>
                                                    <div class="text-sm text-gray-700 space-y-1">
                                                        @foreach($log->metadata as $key => $value)
                                                            <div>
                                                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                <span>{{ is_bool($value) ? ($value ? 'Ya' : 'Tidak') : $value }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="mt-2 text-xs text-gray-500">
                                                <span>IP: {{ $log->ip_address }}</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $log->created_at->format('d M Y, H:i:s') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if($auditLogs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $auditLogs->links() }}
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
