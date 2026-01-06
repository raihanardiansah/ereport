<x-layouts.app title="Notifikasi">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
            <p class="text-gray-600 mt-1">Semua notifikasi Anda</p>
        </div>
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
            <div class="p-4 {{ $notification->isRead() ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition-colors">
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $notification->icon }}"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="font-medium text-gray-900">{{ $notification->title }}</p>
                            <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                        @if(!$notification->isRead())
                        <button onclick="markAsRead({{ $notification->id }})" class="text-primary-600 hover:text-primary-700 text-sm mt-2">
                            Tandai Dibaca
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="font-medium text-lg">Tidak ada notifikasi</p>
                <p class="text-sm mt-1">Notifikasi baru akan muncul di sini</p>
            </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>

    <script>
        function markAsRead(id) {
            fetch('/notifications/' + id + '/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    </script>
</x-layouts.app>
