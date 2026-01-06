@extends('layouts.app')

@section('title', 'Tambah Paket Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.packages') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="card">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Tambah Paket Baru</h1>

        <form action="{{ route('admin.packages.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        placeholder="contoh: Starter, Professional, Enterprise">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" required min="0" step="1000"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        placeholder="299000">
                    @error('price')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white resize-none"
                    placeholder="Deskripsi singkat paket...">{{ old('description') }}</textarea>
            </div>

            <div class="grid sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Durasi (Hari) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', 30) }}" required min="1"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    <p class="text-xs text-gray-500 mt-1">30 = 1 bulan</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Pengguna <span class="text-red-500">*</span></label>
                    <input type="number" name="max_users" value="{{ old('max_users', 5) }}" required min="1"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Laporan/Bulan <span class="text-red-500">*</span></label>
                    <input type="number" name="max_reports_per_month" value="{{ old('max_reports_per_month', 100) }}" required min="1"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Badge Text</label>
                    <input type="text" name="badge_text" value="{{ old('badge_text') }}"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        placeholder="contoh: Paling Populer">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <!-- Features/Benefits Editor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fitur/Benefit Paket</label>
                <div id="features-container" class="space-y-2">
                    <!-- Dynamic features will be added here -->
                </div>
                <button type="button" onclick="addFeature()" class="mt-3 flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Fitur
                </button>
                <p class="text-xs text-gray-500 mt-1">Fitur yang akan ditampilkan pada paket</p>
            </div>

            <div class="flex items-center space-x-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Aktif</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Featured</span>
                </label>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.packages') }}" class="px-6 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 font-medium">Batal</a>
                <button type="submit" class="btn-primary">Simpan Paket</button>
            </div>
        </form>
    </div>
</div>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 feature-item';
    div.innerHTML = `
        <input type="text" name="features[]" value=""
            class="flex-1 px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
            placeholder="Masukkan fitur/benefit">
        <button type="button" onclick="removeFeature(this)" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;
    container.appendChild(div);
    div.querySelector('input').focus();
}

function removeFeature(btn) {
    btn.closest('.feature-item').remove();
}
</script>
@endsection

