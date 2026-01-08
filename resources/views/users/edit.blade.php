<x-layouts.app title="Edit Pengguna">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Pengguna</h1>
        <p class="text-gray-600 mt-1">Perbarui informasi pengguna</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-danger-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="50" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-danger-500 @enderror"
                    placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-danger-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-danger-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                    Username <span class="text-danger-500">*</span>
                </label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" 
                    minlength="8" maxlength="30" required pattern="[a-z0-9]+"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('username') border-danger-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">8-30 karakter, huruf kecil dan angka saja</p>
                @error('username')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Peran <span class="text-danger-500">*</span>
                </label>
                <select id="role" name="role" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('role') border-danger-500 @enderror">
                    <option value="manajemen_sekolah" {{ old('role', $user->role) == 'manajemen_sekolah' ? 'selected' : '' }}>Manajemen Sekolah</option>
                    <option value="staf_kesiswaan" {{ old('role', $user->role) == 'staf_kesiswaan' ? 'selected' : '' }}>Staf Kesiswaan</option>
                    <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIP/NISN -->
            <div>
                <label for="nip_nisn" class="block text-sm font-medium text-gray-700 mb-2">NIP/NISN</label>
                <input type="text" id="nip_nisn" name="nip_nisn" value="{{ old('nip_nisn', $user->nip_nisn) }}" 
                    maxlength="20" pattern="[0-9]*"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('nip_nisn') border-danger-500 @enderror">
                @error('nip_nisn')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone') border-danger-500 @enderror"
                    placeholder="08123456789">
                <p class="mt-1 text-sm text-gray-500">Format: 08xxx atau +628xxx</p>
                @error('phone')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                <p class="text-sm text-yellow-700 mb-4">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Kosongkan password jika tidak ingin mengubahnya
                </p>
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" id="password" name="password" minlength="8"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('password') border-danger-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('users.index') }}" class="px-6 py-3 text-gray-600 hover:text-gray-800 font-medium">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
