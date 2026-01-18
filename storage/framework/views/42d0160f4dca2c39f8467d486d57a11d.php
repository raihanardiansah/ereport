<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Analytics Platform'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Analytics Platform</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Statistik dan analitik seluruh platform</p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.export.csv', 'reports')); ?>" class="btn-secondary text-sm">
                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
            <a href="<?php echo e(route('admin.export.pdf')); ?>" class="btn-primary text-sm">
                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sekolah</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo e($stats['total_schools']); ?></p>
                    <p class="text-xs text-green-600 mt-1"><?php echo e($stats['active_schools']); ?> aktif</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total User</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo e(number_format($stats['total_users'])); ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Laporan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo e(number_format($stats['total_reports'])); ?></p>
                    <p class="text-xs text-blue-600 mt-1"><?php echo e($stats['this_month_reports']); ?> bulan ini</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kasus Siswa</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo e(number_format($stats['total_cases'])); ?></p>
                    <p class="text-xs text-orange-600 mt-1"><?php echo e($stats['open_cases']); ?> terbuka</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <!-- Reports Trend Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Tren Laporan (6 Bulan Terakhir)</h3>
            <div class="flex items-end justify-between h-48 gap-2">
                <?php $maxCount = max(array_column($reportsTrend, 'count')) ?: 1; ?>
                <?php $__currentLoopData = $reportsTrend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex-1 flex flex-col items-center">
                    <div class="w-full bg-primary-100 dark:bg-primary-900/30 rounded-t" 
                         style="height: <?php echo e(($month['count'] / $maxCount) * 100); ?>%">
                        <div class="w-full h-full bg-primary-500 rounded-t hover:bg-primary-600 transition-colors" 
                             title="<?php echo e($month['count']); ?> laporan"></div>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-2"><?php echo e($month['label']); ?></span>
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300"><?php echo e($month['count']); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Reports by Category -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Laporan per Kategori</h3>
            <div class="space-y-3">
                <?php $totalCat = array_sum($reportsByCategory) ?: 1; ?>
                <?php $__currentLoopData = $reportsByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400 capitalize"><?php echo e($category); ?></span>
                        <span class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($count); ?></span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-primary-500 h-2 rounded-full" style="width: <?php echo e(($count / $totalCat) * 100); ?>%"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Top Schools -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Sekolah Teraktif</h3>
            <div class="space-y-3">
                <?php $__currentLoopData = $topSchools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('admin.school.detail', $school)); ?>" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-primary-700 font-bold"><?php echo e(strtoupper(substr($school->name, 0, 1))); ?></span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($school->name); ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($school->users_count); ?> user</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($school->reports_count); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">laporan</p>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Laporan Terbaru</h3>
            <div class="space-y-3">
                <?php $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900 dark:text-gray-100 text-sm"><?php echo e(Str::limit($report->title, 40)); ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <?php echo e($report->school->name ?? 'Unknown'); ?> • <?php echo e($report->created_at->diffForHumans()); ?>

                            </p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            <?php if($report->status === 'selesai'): ?> bg-green-100 text-green-700
                            <?php elseif($report->status === 'diproses'): ?> bg-yellow-100 text-yellow-700
                            <?php else: ?> bg-blue-100 text-blue-700 <?php endif; ?>">
                            <?php echo e($report->status); ?>

                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Quick Export Buttons -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Export Data</h3>
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo e(route('admin.export.csv', 'schools')); ?>" class="btn-secondary text-sm">
                📊 Export Sekolah (CSV)
            </a>
            <a href="<?php echo e(route('admin.export.csv', 'reports')); ?>" class="btn-secondary text-sm">
                📋 Export Laporan (CSV)
            </a>
            <a href="<?php echo e(route('admin.export.csv', 'cases')); ?>" class="btn-secondary text-sm">
                📁 Export Kasus (CSV)
            </a>
            <a href="<?php echo e(route('admin.export.pdf')); ?>" class="btn-primary text-sm">
                📄 Export Laporan Platform (PDF)
            </a>
        </div>
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
<?php /**PATH /var/www/html/resources/views/admin/analytics.blade.php ENDPATH**/ ?>