@extends('layouts.app')

@section('title', 'Tambah Promosi')

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
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Tambah Promosi Baru</h1>

        <form action="{{ route('admin.promotions.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode Promo <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white uppercase font-mono"
                        placeholder="DISKON50">
                    <p class="text-xs text-gray-500 mt-1">Akan diubah ke huruf kapital</p>
                    @error('code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Promosi <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        placeholder="Diskon Hari Kemerdekaan">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                <textarea name="description" rows="2"
                    class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white resize-none"
                    placeholder="Deskripsi promosi...">{{ old('description') }}</textarea>
            </div>

            <div class="grid sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Diskon <span class="text-red-500">*</span></label>
                    <select name="type" required
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nilai Diskon <span class="text-red-500">*</span></label>
                    <input type="number" name="value" value="{{ old('value', 10) }}" required min="0" step="0.01"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        placeholder="10">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Diskon (Rp)</label>
                    <input type="number" name="max_discount" value="{{ old('max_discount') }}" min="0" step="1000"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        placeholder="100000">
                    <p class="text-xs text-gray-500 mt-1">Untuk tipe %</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min. Pembelian (Rp)</label>
                    <input type="number" name="min_purchase" value="{{ old('min_purchase', 0) }}" min="0" step="1000"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limit Penggunaan</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                        placeholder="Kosongkan = unlimited">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Per User</label>
                    <input type="number" name="usage_per_user" value="{{ old('usage_per_user', 1) }}" min="1"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mulai Berlaku</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Berakhir</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                        class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Berlaku untuk Paket</label>
                <div class="grid sm:grid-cols-3 gap-3">
                    @foreach($packages as $pkg)
                        <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800">
                            <input type="checkbox" name="applicable_packages[]" value="{{ $pkg->id }}"
                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $pkg->name }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-2">Kosongkan = berlaku untuk semua paket</p>
            </div>

            <div class="flex items-center">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-gray-700 dark:text-gray-300">Aktifkan sekarang</span>
                </label>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.packages') }}" class="px-6 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-800 font-medium">Batal</a>
                <button type="submit" class="btn-primary">Simpan Promosi</button>
            </div>
        </form>
    </div>
</div>
@endsection
