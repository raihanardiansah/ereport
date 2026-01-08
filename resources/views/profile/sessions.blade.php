<x-layouts.app title="Sesi Aktif">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sesi Aktif</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola perangkat yang login ke akun Anda</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Sessions List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Perangkat yang Login</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Berikut adalah daftar perangkat yang sedang login ke akun Anda.
            </p>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($sessions as $session)
                <div class="p-6 flex items-center justify-between gap-4 {{ $session->is_current ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                    <div class="flex items-center gap-4">
                        <!-- Device Icon -->
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center {{ $session->is_current ? 'bg-green-100 dark:bg-green-800' : 'bg-gray-100 dark:bg-gray-700' }}">
                            @if($session->device['device_type'] === 'mobile')
                                <svg class="w-6 h-6 {{ $session->is_current ? 'text-green-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            @elseif($session->device['device_type'] === 'tablet')
                                <svg class="w-6 h-6 {{ $session->is_current ? 'text-green-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 {{ $session->is_current ? 'text-green-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>

                        <!-- Device Info -->
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $session->device['browser'] }} - {{ $session->device['platform'] }}
                                </span>
                                @if($session->is_current)
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-300">
                                        Perangkat ini
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <span>{{ $session->ip_address }}</span>
                                <span class="mx-2">•</span>
                                <span>Aktif {{ $session->last_activity->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if(!$session->is_current)
                        <form method="POST" action="{{ route('profile.sessions.destroy', $session->id) }}" 
                              onsubmit="return confirm('Yakin ingin logout dari perangkat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                Logout
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                    <p>Tidak ada sesi aktif ditemukan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Logout Other Sessions -->
    @if($sessions->count() > 1)
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Logout Semua Perangkat Lain</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Logout dari semua sesi kecuali perangkat yang sedang Anda gunakan ini.
        </p>

        <form method="POST" action="{{ route('profile.sessions.destroy-others') }}" class="flex items-end gap-4">
            @csrf
            @method('DELETE')
            <div class="flex-1 max-w-sm">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Konfirmasi Password
                </label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Masukkan password Anda">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                Logout Semua Lainnya
            </button>
        </form>
    </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200">
            ← Kembali ke Profil
        </a>
    </div>
</x-layouts.app>
