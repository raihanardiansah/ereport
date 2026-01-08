<x-layouts.guest title="Login">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Login</h1>
        <p class="text-gray-500 mt-1 text-sm">Selamat datang! Silakan login dengan akun Anda.</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-start gap-2">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Global Error Alert --}}
    @if($errors->any())
        <div class="mb-4 p-3 bg-danger-50 border border-danger-200 text-danger-700 rounded-lg text-sm">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-medium">Oops! Ada kesalahan:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                Username, Email, atau No. HP
            </label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                value="{{ old('username') }}"
                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3CB371] focus:border-[#3CB371] transition-all text-sm @error('username') border-danger-500 @enderror"
                placeholder="Masukkan username, email, atau no. HP"
                required
                autofocus
            >
            @error('username')
                <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password
            </label>
            <div class="relative">
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3CB371] focus:border-[#3CB371] transition-all text-sm pr-10 @error('password') border-danger-500 @enderror"
                    placeholder="Masukkan password"
                    required
                >
                <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg id="eye-icon-password" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="eye-off-icon-password" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.557 2.888m1.977-4.464L21 21" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
            @enderror
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
</script>

        <!-- reCAPTCHA v2 -->
        <div>
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
                <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    name="remember" 
                    class="w-4 h-4 text-[#3CB371] border-gray-300 rounded focus:ring-[#3CB371]"
                >
                <span class="ml-2 text-gray-600">Ingat Saya</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-[#00B4D8] hover:text-[#155E75] font-medium">
                Lupa Password?
            </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-[#3CB371] to-[#00B4D8] hover:from-[#2E8B57] hover:to-[#0096C7] text-white font-semibold py-2.5 px-4 rounded-lg transition-all flex items-center justify-center gap-2 text-sm">
            Login
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>
    </form>

    <!-- Register Link -->
    <div class="mt-6 text-center text-sm">
        <p class="text-gray-600">
            Pengguna Baru?
            <a href="{{ route('register') }}" class="text-[#00B4D8] hover:text-[#155E75] font-semibold">
                Daftar
            </a>
        </p>
    </div>
</x-layouts.guest>
