<x-layouts.app title="Dashboard">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Reports -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Laporan</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total_reports'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                <span class="text-primary-600 font-medium">+{{ $stats['new_reports'] ?? 0 }}</span> laporan baru
            </p>
        </div>

        <!-- Pending Reports -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Proses</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['pending_reports'] ?? 0 }}</p>
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
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['completed_reports'] ?? 0 }}</p>
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
        @if(auth()->user()->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan']) || auth()->user()->isSuperAdmin())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        @if(auth()->user()->isSuperAdmin())
                            Total Sekolah
                        @else
                            Total Pengguna
                        @endif
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-secondary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Terdaftar di sistem</p>
        </div>
        @endif
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Monthly Trends -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Tren Laporan Bulanan</h3>
            <div class="relative h-72 w-full">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>

        <!-- Categories Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Kategori Laporan</h3>
            <div class="relative h-72 w-full flex items-center justify-center">
                <canvas id="categoriesChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dark mode check
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9CA3AF' : '#4B5563';
            const gridColor = isDark ? '#374151' : '#E5E7EB';

            // Trends Chart
            const trendsCtx = document.getElementById('trendsChart').getContext('2d');
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: @json($reportTrends['labels']),
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: @json($reportTrends['data']),
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

            // Categories Chart
            const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
            new Chart(categoriesCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($categoryStats['labels']),
                    datasets: [{
                        data: @json($categoryStats['data']),
                        backgroundColor: [
                            '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#6B7280'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                color: textColor,
                                padding: 20
                            }
                        }
                    }
                }
            });
        });
    </script>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Recent Reports -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Laporan Terbaru</h2>
                    <a href="{{ route('reports.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
                </div>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($recentReports ?? [] as $report)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100">{{ $report->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($report->content, 80) }}</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $report->created_at->diffForHumans() }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        @if($report->status === 'selesai') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($report->status === 'diproses') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>Belum ada laporan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Aksi Cepat</h2>
            <div class="space-y-3">
                @if(auth()->user()->hasAnyRole(['guru', 'siswa', 'staf_kesiswaan']))
                <a href="{{ route('reports.create') }}" class="flex items-center p-3 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 dark:hover:bg-primary-900/50 rounded-lg transition-colors">
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
                @endif

                <a href="{{ route('reports.index') }}" class="flex items-center p-3 bg-secondary-50 dark:bg-secondary-900/30 hover:bg-secondary-100 dark:hover:bg-secondary-900/50 rounded-lg transition-colors">
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

                @if(auth()->user()->isAdminSekolah())
                <a href="{{ route('users.create') }}" class="flex items-center p-3 bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 rounded-lg transition-colors">
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
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
