<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Analitik'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Analitik & Statistik</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Ringkasan data laporan sekolah Anda</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('pdf.analytics')); ?>" class="btn-primary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF Rapat
                </a>
                <a href="<?php echo e(route('pdf.monthly')); ?>" class="btn-secondary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    PDF Bulanan
                </a>
                <a href="<?php echo e(route('analytics.export')); ?>" class="btn-secondary inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    CSV
                </a>
            </div>
        </div>
    </div>


    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Laporan</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_reports']); ?></p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['this_month_reports']); ?></p>
                    <?php if($stats['month_change'] != 0): ?>
                    <p class="text-xs <?php echo e($stats['month_change'] > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                        <?php echo e($stats['month_change'] > 0 ? '+' : ''); ?><?php echo e($stats['month_change']); ?>% dari bulan lalu
                    </p>
                    <?php endif; ?>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Menunggu Proses</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['pending_reports']); ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Rata-rata Resolusi</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['avg_resolution_days']); ?> hari</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <!-- Reports Trend -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Tren Laporan (6 Bulan)</h3>
            <div class="h-64" id="trendChart">
                <div class="flex items-end justify-between h-48 gap-2 px-4">
                    <?php 
                        $maxCount = max(array_column($reportsTrend, 'count')) ?: 1;
                    ?>
                    <?php $__currentLoopData = $reportsTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-primary-100 rounded-t relative" 
                             style="height: <?php echo e(($data['count'] / $maxCount) * 100); ?>%">
                            <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-sm font-medium text-gray-900">
                                <?php echo e($data['count']); ?>

                            </span>
                        </div>
                        <span class="text-xs text-gray-500 mt-2"><?php echo e(explode(' ', $data['label'])[0]); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Classification Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Distribusi Klasifikasi</h3>
            <div class="space-y-4">
                <?php
                    $totalClassification = array_sum($classificationStats) ?: 1;
                ?>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Positif</span>
                        <span class="text-sm font-medium text-green-600"><?php echo e($classificationStats['positif']); ?></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: <?php echo e(($classificationStats['positif'] / $totalClassification) * 100); ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Negatif</span>
                        <span class="text-sm font-medium text-red-600"><?php echo e($classificationStats['negatif']); ?></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-red-500 h-3 rounded-full" style="width: <?php echo e(($classificationStats['negatif'] / $totalClassification) * 100); ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-gray-600">Netral</span>
                        <span class="text-sm font-medium text-gray-600"><?php echo e($classificationStats['netral']); ?></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gray-500 h-3 rounded-full" style="width: <?php echo e(($classificationStats['netral'] / $totalClassification) * 100); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Charts Row -->
    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <!-- Reports by Category -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Laporan per Kategori</h3>
            <div class="space-y-3">
                <?php
                    $categories = ['perilaku' => 'Perilaku', 'akademik' => 'Akademik', 'kehadiran' => 'Kehadiran', 'bullying' => 'Bullying', 'konseling' => 'Konseling', 'lainnya' => 'Lainnya'];
                    $categoryColors = ['perilaku' => 'bg-purple-500', 'akademik' => 'bg-blue-500', 'kehadiran' => 'bg-yellow-500', 'bullying' => 'bg-red-500', 'konseling' => 'bg-green-500', 'lainnya' => 'bg-gray-500'];
                    $totalCategory = array_sum($reportsByCategory) ?: 1;
                ?>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center">
                    <span class="w-24 text-sm text-gray-600"><?php echo e($label); ?></span>
                    <div class="flex-1 mx-3">
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="<?php echo e($categoryColors[$key]); ?> h-2.5 rounded-full" 
                                 style="width: <?php echo e((($reportsByCategory[$key] ?? 0) / $totalCategory) * 100); ?>%"></div>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 w-8 text-right"><?php echo e($reportsByCategory[$key] ?? 0); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Reports by Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Laporan per Status</h3>
            <div class="grid grid-cols-2 gap-4">
                <?php
                    $statuses = ['dikirim' => ['label' => 'Dikirim', 'color' => 'bg-gray-100 text-gray-700'], 'diproses' => ['label' => 'Diproses', 'color' => 'bg-yellow-100 text-yellow-700'], 'ditindaklanjuti' => ['label' => 'Ditindaklanjuti', 'color' => 'bg-blue-100 text-blue-700'], 'selesai' => ['label' => 'Selesai', 'color' => 'bg-green-100 text-green-700']];
                ?>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 rounded-xl <?php echo e($status['color']); ?>">
                    <p class="text-2xl font-bold"><?php echo e($reportsByStatus[$key] ?? 0); ?></p>
                    <p class="text-sm"><?php echo e($status['label']); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Top Reporters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Top 5 Pengirim Laporan</h3>
        <?php if($topReporters->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Peran</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $topReporters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $reporter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="px-4 py-3 text-gray-500"><?php echo e($index + 1); ?></td>
                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($reporter->name); ?></td>
                        <td class="px-4 py-3 text-gray-600 capitalize"><?php echo e(str_replace('_', ' ', $reporter->role)); ?></td>
                        <td class="px-4 py-3 text-right font-semibold text-primary-600"><?php echo e($reporter->total); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-gray-500 text-center py-8">Belum ada data laporan</p>
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
<?php /**PATH /var/www/html/resources/views/analytics/index.blade.php ENDPATH**/ ?>