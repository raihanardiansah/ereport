<x-layouts.app title="Import Pengguna">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Import Pengguna</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Upload file CSV untuk menambahkan pengguna secara massal</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn-secondary inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Success Message -->
    @if(session('import_success') !== null)
    <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl p-4">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold text-green-800 dark:text-green-200">
                    {{ session('import_success') }} pengguna berhasil diimport!
                </p>
                <p class="text-sm text-green-600 dark:text-green-400">
                    Password default: <code class="bg-green-100 dark:bg-green-800 px-1 rounded">Nip{NIP/NISN}!</code>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Import Errors -->
    @if(session('import_errors') && count(session('import_errors')) > 0)
    <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="flex-1">
                <p class="font-semibold text-yellow-800 dark:text-yellow-200">
                    {{ count(session('import_errors')) }} baris gagal diimport:
                </p>
                <div class="mt-3 max-h-48 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-yellow-700 dark:text-yellow-300">
                            <tr>
                                <th class="pb-2 pr-4">Baris</th>
                                <th class="pb-2 pr-4">Nama</th>
                                <th class="pb-2">Error</th>
                            </tr>
                        </thead>
                        <tbody class="text-yellow-600 dark:text-yellow-400">
                            @foreach(session('import_errors') as $error)
                            <tr class="border-t border-yellow-200 dark:border-yellow-700">
                                <td class="py-2 pr-4 align-top">{{ $error['row'] }}</td>
                                <td class="py-2 pr-4 align-top">{{ $error['nama'] }}</td>
                                <td class="py-2 align-top">
                                    <ul class="list-disc list-inside">
                                        @foreach($error['errors'] as $e)
                                        <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Upload Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Upload File CSV</h2>
            
            <form method="POST" action="{{ route('users.import.process') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-primary-400 transition-colors" 
                     id="dropzone"
                     ondragover="event.preventDefault(); this.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20')"
                     ondragleave="this.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20')"
                     ondrop="handleDrop(event)">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                        Drag & drop file CSV, atau
                    </p>
                    <label class="btn-primary cursor-pointer inline-block">
                        Pilih File
                        <input type="file" name="csv_file" accept=".csv" class="hidden" id="fileInput" onchange="updateFileName(this)">
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-3" id="fileName">Max 2MB, format .csv</p>
                </div>

                @error('csv_file')
                <p class="text-danger-600 text-sm mt-2">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn-primary w-full mt-4">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Import Pengguna
                </button>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Panduan Import</h2>
            
            <!-- Download Template -->
            <a href="{{ route('users.import.template') }}" class="block w-full mb-6 py-3 px-4 bg-gradient-to-r from-primary-50 to-secondary-50 dark:from-primary-900/30 dark:to-secondary-900/30 rounded-lg border border-primary-200 dark:border-primary-700 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-800 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">Download Template CSV</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Contoh format yang benar</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Format Table -->
            <h3 class="font-medium text-gray-800 dark:text-gray-200 mb-3">Format Kolom CSV</h3>
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-300">Kolom</th>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-300">Wajib</th>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-300">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr><td class="px-3 py-2 font-mono text-primary-600 dark:text-primary-400">nama</td><td class="px-3 py-2">✓</td><td class="px-3 py-2 text-gray-600 dark:text-gray-400">Huruf dan spasi saja</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-primary-600 dark:text-primary-400">email</td><td class="px-3 py-2">✓</td><td class="px-3 py-2 text-gray-600 dark:text-gray-400">Format email valid</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-primary-600 dark:text-primary-400">username</td><td class="px-3 py-2">✓</td><td class="px-3 py-2 text-gray-600 dark:text-gray-400">8-30 huruf kecil/angka</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-primary-600 dark:text-primary-400">role</td><td class="px-3 py-2">✓</td><td class="px-3 py-2 text-gray-600 dark:text-gray-400">siswa, guru, staf_kesiswaan, manajemen_sekolah</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-primary-600 dark:text-primary-400">nip_nisn</td><td class="px-3 py-2">✓</td><td class="px-3 py-2 text-gray-600 dark:text-gray-400">Angka saja (untuk password)</td></tr>
                        <tr><td class="px-3 py-2 font-mono text-primary-600 dark:text-primary-400">telepon</td><td class="px-3 py-2">-</td><td class="px-3 py-2 text-gray-600 dark:text-gray-400">Format +62...</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Password Info -->
            <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-700">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-blue-800 dark:text-blue-200">Password Default</p>
                        <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                            Password otomatis: <code class="bg-blue-100 dark:bg-blue-800 px-1.5 py-0.5 rounded font-mono">Nip{NIP/NISN}!</code>
                        </p>
                        <p class="text-sm text-blue-600 dark:text-blue-400">
                            Contoh: NIP/NISN <code class="font-mono">12345678</code> → Password <code class="font-mono">Nip12345678!</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileName = document.getElementById('fileName');
            if (input.files.length > 0) {
                fileName.textContent = input.files[0].name;
                fileName.classList.add('text-primary-600', 'font-medium');
            }
        }

        function handleDrop(e) {
            e.preventDefault();
            const dropzone = document.getElementById('dropzone');
            dropzone.classList.remove('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
            
            const fileInput = document.getElementById('fileInput');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                updateFileName(fileInput);
            }
        }
    </script>
</x-layouts.app>
