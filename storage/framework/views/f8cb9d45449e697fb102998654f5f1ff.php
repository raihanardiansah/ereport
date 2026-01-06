<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Laporan'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan</h1>
            <p class="text-gray-600 mt-1">
                <?php if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) || auth()->user()->isSuperAdmin()): ?>
                    Semua laporan sekolah
                <?php else: ?>
                    Laporan yang telah Anda kirim
                <?php endif; ?>
            </p>
        </div>
        <?php if(auth()->user()->hasAnyRole(['guru', 'siswa', 'staf_kesiswaan'])): ?>
        <a href="<?php echo e(route('reports.create')); ?>" class="btn-primary inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Laporan
        </a>
        <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500"
                    placeholder="Cari judul atau isi laporan...">
            </div>
            <div class="sm:w-40">
                <select name="category" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Kategori</option>
                    <option value="perilaku" <?php echo e(request('category') == 'perilaku' ? 'selected' : ''); ?>>Perilaku</option>
                    <option value="akademik" <?php echo e(request('category') == 'akademik' ? 'selected' : ''); ?>>Akademik</option>
                    <option value="kehadiran" <?php echo e(request('category') == 'kehadiran' ? 'selected' : ''); ?>>Kehadiran</option>
                    <option value="bullying" <?php echo e(request('category') == 'bullying' ? 'selected' : ''); ?>>Bullying</option>
                    <option value="konseling" <?php echo e(request('category') == 'konseling' ? 'selected' : ''); ?>>Konseling</option>
                    <option value="lainnya" <?php echo e(request('category') == 'lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                </select>
            </div>
            <div class="sm:w-40">
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    <option value="dikirim" <?php echo e(request('status') == 'dikirim' ? 'selected' : ''); ?>>Dikirim</option>
                    <option value="diproses" <?php echo e(request('status') == 'diproses' ? 'selected' : ''); ?>>Diproses</option>
                    <option value="ditindaklanjuti" <?php echo e(request('status') == 'ditindaklanjuti' ? 'selected' : ''); ?>>Ditindaklanjuti</option>
                    <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary py-2">Filter</button>
        </form>
    </div>

    <!-- Reports List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('reports.show', $report)); ?>" class="block p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-semibold text-gray-900 truncate"><?php echo e($report->title); ?></h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                <?php if($report->status === 'selesai'): ?> bg-green-100 text-green-700
                                <?php elseif($report->status === 'ditindaklanjuti'): ?> bg-blue-100 text-blue-700
                                <?php elseif($report->status === 'diproses'): ?> bg-yellow-100 text-yellow-700
                                <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                                <?php echo e(ucfirst($report->status)); ?>

                            </span>
                        </div>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-3"><?php echo e(Str::limit($report->content, 150)); ?></p>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <?php echo e(ucfirst($report->category)); ?>

                            </span>
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <?php echo e($report->user->name); ?>

                            </span>
                            <span><?php echo e($report->created_at->format('d/m/Y H:i')); ?></span>
                        </div>
                    </div>
                    <div class="ml-4 flex flex-col items-end">
                        <?php $classification = $report->manual_classification ?? $report->ai_classification; ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            <?php if($classification === 'positif'): ?> bg-green-100 text-green-700
                            <?php elseif($classification === 'negatif'): ?> bg-red-100 text-red-700
                            <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                            <?php echo e(ucfirst($classification ?? 'Netral')); ?>

                            <?php if($report->manual_classification): ?>
                                <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20" title="Dikoreksi manual">
                                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                            <?php endif; ?>
                        </span>
                        <?php if($report->attachment_path): ?>
                            <span class="mt-2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="font-medium text-lg">Belum ada laporan</p>
                <p class="text-sm mt-1">Laporan yang dikirim akan muncul di sini</p>
            </div>
            <?php endif; ?>
        </div>

        <?php if($reports->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-100">
            <?php echo e($reports->withQueryString()->links()); ?>

        </div>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfa710ee477a7171fb238cadd060c5959)): ?>
<?php $attributes = $__attributesOriginalfa710ee477a7171fb238cadd060c5959; ?>
<?php unset($__attributesOriginalfa710ee477a7171fb238cadd060c5959); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfa710ee477a7171fb238cadd060c5959)): ?>
<?php $component = $__componentOriginalfa710ee477a7171fb238cadd060c5959; ?>
<?php unset($__componentOriginalfa710ee477a7171fb238cadd060c5959); ?>
<?php endif; ?>
<?php /**PATH D:\laragon\www\E-Report\resources\views/reports/index.blade.php ENDPATH**/ ?>