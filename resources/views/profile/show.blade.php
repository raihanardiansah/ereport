<x-layouts.app title="Profil Saya">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Profil Saya</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola informasi profil dan keamanan akun</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
                <!-- Avatar with ring - perfectly circular -->
                <div class="mx-auto mb-6">
                    <div class="w-28 h-28 rounded-full ring-4 ring-primary-500 ring-offset-4 ring-offset-white dark:ring-offset-gray-800 overflow-hidden mx-auto">
                        <img class="w-full h-full object-cover" 
                            src="{{ $user->avatar_url }}" 
                            alt="{{ $user->name }}" />
                    </div>
                </div>
                
                <!-- User info with better spacing -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $user->name }}</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-4">{{ $user->email }}</p>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 shadow-sm">
                    {{ $user->getRoleDisplayName() }}
                </span>
                
                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 text-left">
                    <div class="space-y-4 text-sm">
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $user->username }}
                        </div>
                        @if($user->nip_nisn)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            {{ $user->isSiswa() ? 'NISN' : 'NIP' }}: {{ $user->nip_nisn }}
                        </div>
                        @endif
                        @if($user->phone)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $user->phone }}
                        </div>
                        @endif
                        @if($user->school)
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $user->school->name }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Session Management Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Keamanan Akun</h3>
                <a href="{{ route('profile.sessions') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">Sesi Aktif</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola perangkat yang login</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Update Profile Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Profil</h3>
                
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('name')
                            <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('email')
                            <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Profil</label>
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0">
                                    <img class="h-12 w-12 object-cover rounded-full border border-gray-200 aspect-square" 
                                        src="{{ $user->avatar_url }}" 
                                        alt="Current profile photo" />
                                </div>
                                <label class="block w-full">
                                    <span class="sr-only">Choose profile photo</span>
                                    <input type="file" name="avatar" accept="image/*"
                                        class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-primary-50 file:text-primary-700
                                        hover:file:bg-primary-100 dark:file:bg-primary-900/30 dark:file:text-primary-300
                                    "/>
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">JPG, PNG, atau WebP. Maksimal 5MB.</p>
                            @error('avatar')
                                <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor HP</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+6281234567890"
                                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('phone')
                            <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                            <input type="text" value="{{ $user->username }}" disabled
                                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Username tidak dapat diubah</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ubah Password</h3>
                
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" 
                                class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            @error('current_password')
                            <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
                                <input type="password" name="password" 
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('password')
                                <p class="text-danger-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" 
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Password minimal 8 karakter, harus ada huruf besar, huruf kecil, angka, dan simbol.
                        </p>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn-secondary">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
