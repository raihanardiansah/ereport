<?php $__env->startSection('title', 'Kelola Paket & Promosi'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Paket & Promosi</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Atur paket langganan dan kode promosi</p>
        </div>
        <button onclick="syncPackages()" class="btn-primary flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Sinkronkan
        </button>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($stats['total_packages']); ?></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Paket</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($stats['active_packages']); ?></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Paket Aktif</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($stats['total_promotions']); ?></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Promosi</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($stats['active_promotions']); ?></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Promosi Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Packages Section -->
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Paket Langganan</h2>
            <a href="<?php echo e(route('admin.packages.create')); ?>" class="btn-primary text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Paket
            </a>
        </div>

        <?php if($packages->count() > 0): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4" id="packages-grid">
                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="relative border rounded-xl p-5 <?php echo e($package->is_active ? 'border-gray-200 dark:border-gray-700' : 'border-gray-100 dark:border-gray-800 opacity-60'); ?> <?php echo e($package->is_featured ? 'ring-2 ring-indigo-500' : ''); ?>">
                        <?php if($package->badge_text): ?>
                            <span class="absolute -top-3 left-4 px-3 py-1 text-xs font-semibold rounded-full <?php echo e($package->badge_color ?? 'bg-indigo-500 text-white'); ?>">
                                <?php echo e($package->badge_text); ?>

                            </span>
                        <?php endif; ?>
                        
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e($package->name); ?></h3>
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1"><?php echo e($package->formatted_price); ?></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($package->duration_months); ?> bulan</p>
                            </div>
                            <div class="flex items-center space-x-1">
                                <button onclick="togglePackage(<?php echo e($package->id); ?>)" title="<?php echo e($package->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>"
                                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                    <?php if($package->is_active): ?>
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    <?php else: ?>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    <?php endif; ?>
                                </button>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4"><?php echo e($package->description ?? 'Tidak ada deskripsi'); ?></p>

                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1 mb-4">
                            <p>👥 Max <?php echo e($package->max_users); ?> pengguna</p>
                            <p>📝 Max <?php echo e($package->max_reports_per_month); ?> laporan/bulan</p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-xs <?php echo e($package->is_active ? 'text-green-600' : 'text-gray-400'); ?>">
                                <?php echo e($package->is_active ? 'Aktif' : 'Nonaktif'); ?>

                            </span>
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('admin.packages.edit', $package)); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                                <button onclick="deletePackage(<?php echo e($package->id); ?>)" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p>Belum ada paket. <a href="<?php echo e(route('admin.packages.create')); ?>" class="text-indigo-600 hover:underline">Buat paket pertama</a></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Promotions Section -->
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Kode Promosi</h2>
            <a href="<?php echo e(route('admin.promotions.create')); ?>" class="btn-primary text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Promosi
            </a>
        </div>

        <?php if($promotions->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-gray-500 dark:text-gray-400">
                            <th class="pb-3 font-medium">Kode</th>
                            <th class="pb-3 font-medium">Nama</th>
                            <th class="pb-3 font-medium">Diskon</th>
                            <th class="pb-3 font-medium">Penggunaan</th>
                            <th class="pb-3 font-medium">Berlaku</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e($promo->is_active ? '' : 'opacity-50'); ?>">
                                <td class="py-4">
                                    <span class="font-mono font-semibold text-indigo-600 dark:text-indigo-400"><?php echo e($promo->code); ?></span>
                                </td>
                                <td class="py-4">
                                    <span class="text-gray-900 dark:text-white"><?php echo e($promo->name); ?></span>
                                </td>
                                <td class="py-4">
                                    <span class="font-medium text-green-600 dark:text-green-400"><?php echo e($promo->formatted_value); ?></span>
                                </td>
                                <td class="py-4 text-gray-600 dark:text-gray-400">
                                    <?php echo e($promo->used_count); ?>/<?php echo e($promo->usage_limit ?? '∞'); ?>

                                </td>
                                <td class="py-4 text-sm text-gray-600 dark:text-gray-400">
                                    <?php if($promo->expires_at): ?>
                                        <?php echo e($promo->expires_at->format('d M Y')); ?>

                                    <?php else: ?>
                                        Selamanya
                                    <?php endif; ?>
                                </td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($promo->status_color); ?>">
                                        <?php echo e($promo->status); ?>

                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="togglePromotion(<?php echo e($promo->id); ?>)" title="Toggle Status"
                                            class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <?php if($promo->is_active): ?>
                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            <?php endif; ?>
                                        </button>
                                        <a href="<?php echo e(route('admin.promotions.edit', $promo)); ?>" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button onclick="deletePromotion(<?php echo e($promo->id); ?>)" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <p>Belum ada promosi. <a href="<?php echo e(route('admin.promotions.create')); ?>" class="text-indigo-600 hover:underline">Buat promosi pertama</a></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Password Confirmation Modal -->
<div id="password-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closePasswordModal()"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Konfirmasi Password</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan password untuk menghapus paket</p>
                </div>
            </div>
            <div class="mb-4">
                <input type="password" id="confirm-password" placeholder="Password Anda"
                    class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white">
                <p id="password-error" class="text-red-500 text-sm mt-2 hidden"></p>
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="closePasswordModal()" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 font-medium">Batal</button>
                <button onclick="confirmDeletePackage()" id="confirm-delete-btn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
let packageToDelete = null;

async function togglePackage(id) {
    try {
        const response = await fetch(`/admin/packages/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
            }
        });
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan', 'error');
    }
}

function deletePackage(id) {
    packageToDelete = id;
    document.getElementById('password-modal').classList.remove('hidden');
    document.getElementById('confirm-password').value = '';
    document.getElementById('password-error').classList.add('hidden');
    document.getElementById('confirm-password').focus();
}

function closePasswordModal() {
    document.getElementById('password-modal').classList.add('hidden');
    packageToDelete = null;
}

async function confirmDeletePackage() {
    const password = document.getElementById('confirm-password').value;
    if (!password) {
        document.getElementById('password-error').textContent = 'Password wajib diisi';
        document.getElementById('password-error').classList.remove('hidden');
        return;
    }

    const btn = document.getElementById('confirm-delete-btn');
    btn.disabled = true;
    btn.textContent = 'Menghapus...';
    
    try {
        const response = await fetch(`/admin/packages/${packageToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ password })
        });
        const data = await response.json();
        
        if (data.success) {
            closePasswordModal();
            location.reload();
        } else {
            document.getElementById('password-error').textContent = data.message;
            document.getElementById('password-error').classList.remove('hidden');
        }
    } catch (error) {
        document.getElementById('password-error').textContent = 'Terjadi kesalahan';
        document.getElementById('password-error').classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Hapus';
    }
}

async function togglePromotion(id) {
    try {
        const response = await fetch(`/admin/promotions/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
            }
        });
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan', 'error');
    }
}

async function deletePromotion(id) {
    if (!confirm('Yakin ingin menghapus promosi ini?')) return;
    
    try {
        const response = await fetch(`/admin/promotions/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
            }
        });
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan', 'error');
    }
}

async function syncPackages() {
    try {
        const response = await fetch('<?php echo e(route("admin.packages.sync")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
            }
        });
        const data = await response.json();
        showToast(data.message, 'success');
    } catch (error) {
        showToast('Gagal menyinkronkan', 'error');
    }
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closePasswordModal();
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/packages/index.blade.php ENDPATH**/ ?>