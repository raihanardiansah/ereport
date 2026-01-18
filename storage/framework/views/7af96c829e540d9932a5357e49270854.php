<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Dashboard'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Selamat datang, <?php echo e(auth()->user()->name); ?>!</p>
    </div>

    <!-- Emergency Triage Alert -->
    <?php
        $criticalReports = \App\Models\Report::where('urgency', 'critical')
            ->whereIn('status', ['dikirim', 'diproses', 'ditindaklanjuti'])
            ->when(!auth()->user()->isSuperAdmin() && auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']), function($q) {
                return $q->where('school_id', auth()->user()->school_id);
            })
            ->get();
    ?>

    <?php if($criticalReports->count() > 0 && auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan', 'super_admin'])): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg animate-pulse">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    PERHATIAN: <?php echo e($criticalReports->count()); ?> Laporan Urgent Terdeteksi!
                </h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>Sistem AI mendeteksi laporan dengan tingkat urgensi KRITIS (Ancaman nyawa/Kekerasan/Napza). Segera tindak lanjuti.</p>
                </div>
                <div class="mt-4">
                    <div class="-mx-2 -my-1.5 flex">
                        <a href="<?php echo e(route('reports.index', ['urgency' => 'critical'])); ?>" class="px-3 py-2 rounded-md text-sm font-medium text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Lihat Laporan Kritis &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Pending Approval Alert -->
    <?php if(isset($pendingUsersCount) && $pendingUsersCount > 0): ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">
                    Persetujuan Diperlukan: <?php echo e($pendingUsersCount); ?> Kredensial Baru
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Ada siswa/guru baru yang mendaftar dan menunggu persetujuan Anda untuk bisa login.</p>
                </div>
                <div class="mt-4">
                    <div class="-mx-2 -my-1.5 flex">
                        <a href="<?php echo e(route('users.index')); ?>" class="px-3 py-2 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Kelola Pengguna &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1000)" class="mb-8">
        <!-- Skeleton Loading State -->
        <template x-if="loading">
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Skeleton 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="space-y-2">
                            <div class="skeleton h-4 w-24 rounded"></div>
                            <div class="skeleton h-8 w-16 rounded"></div>
                        </div>
                        <div class="skeleton h-12 w-12 rounded-xl"></div>
                    </div>
                    <div class="skeleton h-4 w-32 rounded"></div>
                </div>
                <!-- Skeleton 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="space-y-2">
                            <div class="skeleton h-4 w-24 rounded"></div>
                            <div class="skeleton h-8 w-16 rounded"></div>
                        </div>
                        <div class="skeleton h-12 w-12 rounded-xl"></div>
                    </div>
                    <div class="skeleton h-4 w-32 rounded"></div>
                </div>
                <!-- Skeleton 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="space-y-2">
                            <div class="skeleton h-4 w-24 rounded"></div>
                            <div class="skeleton h-8 w-16 rounded"></div>
                        </div>
                        <div class="skeleton h-12 w-12 rounded-xl"></div>
                    </div>
                    <div class="skeleton h-4 w-32 rounded"></div>
                </div>
                <!-- Skeleton 4 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="space-y-2">
                            <div class="skeleton h-4 w-24 rounded"></div>
                            <div class="skeleton h-8 w-16 rounded"></div>
                        </div>
                        <div class="skeleton h-12 w-12 rounded-xl"></div>
                    </div>
                    <div class="skeleton h-4 w-32 rounded"></div>
                </div>
            </div>
        </template>

        <!-- Real Content -->
        <div x-show="!loading" style="display: none;" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Reports -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Laporan</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1"><?php echo e($stats['total_reports'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    <span class="text-primary-600 font-medium">+<?php echo e($stats['new_reports'] ?? 0); ?></span> laporan baru
                </p>
            </div>

            <!-- Pending Reports -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Proses</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1"><?php echo e($stats['pending_reports'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Perlu ditindaklanjuti</p>
            </div>

            <!-- Completed Reports -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1"><?php echo e($stats['completed_reports'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Laporan selesai diproses</p>
            </div>

            <!-- Users / Students (Admin Only) -->
            <?php if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) || auth()->user()->isSuperAdmin()): ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            <?php if(auth()->user()->isSuperAdmin()): ?>
                                Total Sekolah
                            <?php else: ?>
                                Total Pengguna
                            <?php endif; ?>
                        </p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1"><?php echo e($stats['total_users'] ?? 0); ?></p>
                    </div>
                    <div class="w-12 h-12 bg-secondary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Terdaftar di sistem</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Analytics Charts -->
    <?php if(!empty($reportTrends) && !empty($categoryStats)): ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Monthly Trends -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Tren Laporan Bulanan</h3>
            <div class="relative h-72 w-full">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>

        <!-- Categories Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Kategori Terbanyak</h3>
            
            <?php if(empty($categoryStats)): ?>
                <div class="flex-1 flex flex-col items-center justify-center text-center text-gray-500 min-h-[200px]">
                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    <p>Belum ada data</p>
                </div>
            <?php else: ?>
                <div class="space-y-4 flex-1 overflow-y-auto pr-2 max-h-[300px]">
                    <?php $__currentLoopData = $categoryStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="group">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-primary-600 transition-colors"><?php echo e($stat['label']); ?></span>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400"><?php echo e($stat['count']); ?> Laporan (<?php echo e($stat['percentage']); ?>%)</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full transition-all duration-500 ease-out 
                                <?php if($stat['color'] === 'red'): ?> bg-red-500
                                <?php elseif($stat['color'] === 'green'): ?> bg-green-500
                                <?php elseif($stat['color'] === 'blue'): ?> bg-blue-500
                                <?php elseif($stat['color'] === 'orange'): ?> bg-orange-500
                                <?php else: ?> bg-gray-500 <?php endif; ?>
                            " style="width: <?php echo e($stat['percentage']); ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(!empty($reportTrends) && !empty($categoryStats)): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if chart element exists
            const trendsChart = document.getElementById('trendsChart');
            if (!trendsChart) return;

            // Dark mode check
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9CA3AF' : '#4B5563';
            const gridColor = isDark ? '#374151' : '#E5E7EB';

            // Trends Chart
            const trendsCtx = document.getElementById('trendsChart').getContext('2d');
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($reportTrends['labels'], 15, 512) ?>,
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: <?php echo json_encode($reportTrends['data'], 15, 512) ?>,
                        borderColor: '#10B981', // Primary color
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10B981',
                        pointHoverBackgroundColor: '#10B981',
                        pointHoverBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: isDark ? '#1F2937' : '#fff',
                            titleColor: isDark ? '#F3F4F6' : '#111827',
                            bodyColor: isDark ? '#D1D5DB' : '#4B5563',
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                color: textColor,
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });

            
            /* Categories Chart Removed - Replaced with List View */
        });
    </script>
    <?php endif; ?>


    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Recent Reports -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Laporan Terbaru</h2>
                    <a href="<?php echo e(route('reports.index')); ?>" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
                </div>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $recentReports ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($report->title); ?></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?php echo e(Str::limit($report->content, 80)); ?></p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-xs text-gray-400 dark:text-gray-500"><?php echo e($report->created_at->diffForHumans()); ?></span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        <?php if($report->status === 'selesai'): ?> bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        <?php elseif($report->status === 'diproses'): ?> bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                        <?php else: ?> bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 <?php endif; ?>">
                                        <?php echo e(ucfirst($report->status)); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>Belum ada laporan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Aksi Cepat</h2>
            <div class="space-y-3">
                <?php if(auth()->user()->hasAnyRole(['guru', 'siswa', 'staf_kesiswaan'])): ?>
                <a href="<?php echo e(route('reports.create')); ?>" class="flex items-center p-3 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 dark:hover:bg-primary-900/50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Buat Laporan Baru</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Kirim laporan baru</p>
                    </div>
                </a>
                <?php endif; ?>

                <a href="<?php echo e(route('reports.index')); ?>" class="flex items-center p-3 bg-secondary-50 dark:bg-secondary-900/30 hover:bg-secondary-100 dark:hover:bg-secondary-900/50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-secondary-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Lihat Laporan Saya</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Cek status laporan</p>
                    </div>
                </a>

                <?php if(auth()->user()->isAdminSekolah()): ?>
                <a href="<?php echo e(route('users.create')); ?>" class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded-lg transition-colors">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Tambah Pengguna</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Guru atau siswa baru</p>
                    </div>
                </a>
                <?php endif; ?>
            </div>
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
<?php /**PATH /var/www/html/resources/views/dashboard/index.blade.php ENDPATH**/ ?>