<x-layouts.guest title="Daftar Sekolah">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Sekolah</h1>
        <p class="text-gray-500 mt-2">Mulai dengan 7 hari percobaan gratis!</p>
    </div>

    @if(session('error'))
        <div class="mb-4 p-3 bg-danger-50 border border-danger-200 text-danger-700 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
        @csrf

        <!-- School Info Section -->
        <div class="pb-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Informasi Sekolah</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah *</label>
                    <input type="text" name="school_name" value="{{ old('school_name') }}" required maxlength="100"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('school_name') border-danger-500 @enderror"
                        placeholder="SMA Negeri 1 Contoh">
                    @error('school_name')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Sekolah *</label>
                        <input type="email" name="school_email" value="{{ old('school_email') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('school_email') border-danger-500 @enderror"
                            placeholder="info@sekolah.sch.id">
                        @error('school_email')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NPSN</label>
                        <input type="text" name="npsn" value="{{ old('npsn') }}" maxlength="20" pattern="[0-9]*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('npsn') border-danger-500 @enderror"
                            placeholder="12345678">
                        @error('npsn')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('phone') border-danger-500 @enderror"
                            placeholder="08123456789">
                        <p class="mt-1 text-xs text-gray-500">Format: 08xxx atau +628xxx</p>
                        @error('phone')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                        <select name="province" id="province-select" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Pilih Provinsi</option>
                        </select>
                        <input type="hidden" name="province_name" id="province_name">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                    <select name="city" id="city-select" required disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 text-gray-500">
                        <option value="">Pilih Provinsi Terlebih Dahulu</option>
                    </select>
                    <input type="hidden" name="city_name" id="city_name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" rows="2" maxlength="500"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none"
                        placeholder="Jl. Pendidikan No. 1">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Admin Info Section -->
        <div class="pt-2">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Akun Admin Sekolah</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Admin *</label>
                    <input type="text" name="admin_name" value="{{ old('admin_name') }}" required maxlength="50"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('admin_name') border-danger-500 @enderror"
                        placeholder="Nama lengkap admin">
                    @error('admin_name')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Admin *</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('admin_email') border-danger-500 @enderror"
                            placeholder="admin@email.com">
                        @error('admin_email')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                        <input type="text" name="admin_username" value="{{ old('admin_username') }}" required
                            minlength="8" maxlength="30" pattern="[a-z0-9]+"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('admin_username') border-danger-500 @enderror"
                            placeholder="adminsekolah1">
                        <p class="mt-1 text-xs text-gray-500">8-30 karakter, huruf kecil & angka</p>
                        @error('admin_username')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <div class="relative">
                            <input type="password" name="admin_password" id="admin_password" required minlength="8"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 pr-10 @error('admin_password') border-danger-500 @enderror"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('admin_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg id="eye-icon-admin_password" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-off-icon-admin_password" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.557 2.888m1.977-4.464L21 21" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Min 8 karakter</p>
                        @error('admin_password')<p class="mt-1 text-sm text-danger-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password *</label>
                        <div class="relative">
                            <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 pr-10"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('admin_password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg id="eye-icon-admin_password_confirmation" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-off-icon-admin_password_confirmation" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.557 2.888m1.977-4.464L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- reCAPTCHA v2 -->
        <div class="pt-2">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 mt-6">
            Daftar Sekarang
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Dengan mendaftar, Anda menyetujui <a href="#" class="text-primary-600 hover:underline">Syarat & Ketentuan</a> kami.
        </p>
    </form>

    <div class="mt-6 text-center">
        <p class="text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-semibold">Login</a>
        </p>
    </div>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById('eye-icon-' + inputId);
        const eyeOff = document.getElementById('eye-off-icon-' + inputId);
        
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.add('hidden');
            eyeOff.classList.remove('hidden');
        } else {
            input.type = 'password';
            eye.classList.remove('hidden');
            eyeOff.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        const provinceSelect = document.getElementById('province-select');
        const citySelect = document.getElementById('city-select');
        
        // Helper to convert to Title Case
        const toTitleCase = (str) => {
            return str.replace(/\w\S*/g, (txt) => {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        };

        // Old values from Laravel
        const oldProvince = "{{ old('province') }}";
        const oldCity = "{{ old('city') }}";

        try {
            // Fetch Provinces
            const response = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            let provinces = await response.json();
            
            // Sort alphabetically
            provinces.sort((a, b) => a.name.localeCompare(b.name));

            provinces.forEach(province => {
                const option = document.createElement('option');
                const name = toTitleCase(province.name);
                option.value = name;
                option.textContent = name;
                option.dataset.id = province.id;
                
                if (oldProvince && oldProvince === name) {
                    option.selected = true;
                }
                
                provinceSelect.appendChild(option);
            });

            // Trigger change if we have an old province to load cities
            if (oldProvince) {
                // Find selected option to get ID
                const selectedOption = Array.from(provinceSelect.options).find(opt => opt.value === oldProvince);
                if (selectedOption) {
                    loadCities(selectedOption.dataset.id, oldCity);
                }
            }

        } catch (error) {
            console.error('Error fetching provinces:', error);
            provinceSelect.innerHTML += '<option value="">Gagal memuat data</option>';
        }

        // Listener for Province Change
        provinceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const provinceId = selectedOption.dataset.id;
            
            if (provinceId) {
                loadCities(provinceId);
            } else {
                citySelect.innerHTML = '<option value="">Pilih Provinsi Terlebih Dahulu</option>';
                citySelect.disabled = true;
                citySelect.classList.add('bg-gray-50', 'text-gray-500');
            }
        });

        async function loadCities(provinceId, selectedCity = null) {
            citySelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.disabled = true;
            citySelect.classList.remove('bg-gray-50', 'text-gray-500');

            try {
                const response = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`);
                let regencies = await response.json();
                
                // Sort alphabetically
                regencies.sort((a, b) => a.name.localeCompare(b.name));

                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                
                regencies.forEach(regency => {
                    const option = document.createElement('option');
                    const name = toTitleCase(regency.name);
                    option.value = name;
                    option.textContent = name;
                    
                    if (selectedCity && selectedCity === name) {
                        option.selected = true;
                    }
                    
                    citySelect.appendChild(option);
                });
                
                citySelect.disabled = false;
            } catch (error) {
                console.error('Error fetching regencies:', error);
                citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
            }
        }
    });
</script>
</x-layouts.guest>
