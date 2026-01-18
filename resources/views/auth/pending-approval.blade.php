<x-layouts.auth title="Menunggu Persetujuan">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Persetujuan</h2>
            <p class="text-gray-600 mb-6">
                Pendaftaran Anda berhasil. Akun Anda sedang menunggu persetujuan dari <strong>Admin Sekolah</strong>.
            </p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-sm text-left">
                <p class="font-medium text-gray-900 mb-1">Apa selanjutnya?</p>
                <ul class="list-disc list-inside text-gray-600 space-y-1">
                    <li>Hubungi Admin Sekolah/Guru Anda untuk mempercepat proses.</li>
                    <li>Silakan cek kembali secara berkala.</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Kembali ke Halaman Login
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>
