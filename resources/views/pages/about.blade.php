<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tentang e-Report - Sistem Informasi Laporan Digital untuk Sekolah Modern">
    <title>Tentang e-Report - Sistem Laporan Siswa Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #3CB371 0%, #00B4D8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="antialiased bg-slate-950 text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2">
                        <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-10 w-auto">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Beranda</a>
                    <a href="/about" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Tentang</a>
                    <a href="/blog" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Blog</a>
                    <div class="h-5 w-px bg-white/10"></div>
                    <a href="/login" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Masuk</a>
                    <a href="/register" class="text-sm font-medium bg-white text-slate-900 px-5 py-2.5 rounded-full hover:bg-gray-100 transition-colors">Daftar Sekarang</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-300 hover:text-white p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-slate-950 border-t border-white/10">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="/" class="block px-3 py-3 text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Beranda</a>
                <a href="/about" class="block px-3 py-3 text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Tentang</a>
                <a href="/blog" class="block px-3 py-3 text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Blog</a>
                <div class="my-4 border-t border-white/10"></div>
                <a href="/login" class="block px-3 py-3 text-base font-medium text-gray-300 hover:text-white hover:bg-white/5 rounded-lg">Masuk</a>
                <a href="/register" class="block px-3 py-3 text-base font-medium text-emerald-400 hover:text-emerald-300 hover:bg-white/5 rounded-lg">Daftar Sekarang</a>
            </div>
        </div>
    </nav>

    <script>
        // Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

    <!-- Main Content -->
    <main class="pt-32 pb-20 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <div class="mb-8">
                    <img src="https://i.ibb.co.com/chWmj9Bf/Logo-1.png" alt="e-Report Logo" class="h-32 mx-auto">
                </div>
                <h1 class="text-5xl font-bold mb-6">
                    Tentang <span class="gradient-text">e-Report</span>
                </h1>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">
                    Sistem Informasi Laporan Digital untuk Sekolah Modern
                </p>
            </div>

            <!-- What is e-Report -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-10 mb-8">
                <h2 class="text-3xl font-bold mb-6 text-white">Apa itu e-Report?</h2>
                <p class="text-white/80 leading-relaxed text-lg mb-4">
                    <strong class="text-white">e-Report</strong> (Sistem Informasi Laporan Digital) adalah platform berbasis web yang dikembangkan 
                    untuk mengatasi isu di sekolah. Sistem ini bertujuan untuk memodernisasi dan menyederhanakan proses 
                    pelaporan di lingkungan sekolah, menggantikan metode konvensional dengan solusi digital yang cepat, 
                    transparan, dan akurat.
                </p>
                <p class="text-white/80 leading-relaxed text-lg">
                    Dengan e-Report, siswa, guru, maupun staf dapat mengirimkan laporan (akademik, sarana prasarana, 
                    atau kedisiplinan) secara real-time dan memantau status tindak lanjutnya.
                </p>
            </div>

            <!-- Features -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold mb-8 text-center text-white">Fitur Unggulan</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="glass-card rounded-2xl p-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">📝 Pelaporan Mudah & Cepat</h3>
                        <p class="text-white/70">Antarmuka yang ramah pengguna (user-friendly) untuk membuat laporan baru.</p>
                    </div>

                    <div class="glass-card rounded-2xl p-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">🔒 Keamanan & Privasi</h3>
                        <p class="text-white/70">Data pelapor dan isi laporan dijaga kerahasiaannya.</p>
                    </div>

                    <div class="glass-card rounded-2xl p-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">📱 Responsif</h3>
                        <p class="text-white/70">Akses mudah melalui Desktop, Tablet, maupun Smartphone.</p>
                    </div>

                    <div class="glass-card rounded-2xl p-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">📊 Dashboard Admin</h3>
                        <p class="text-white/70">Panel kontrol terpusat untuk memverifikasi dan menindaklanjuti laporan masuk.</p>
                    </div>

                    <div class="glass-card rounded-2xl p-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">🔔 Notifikasi Status</h3>
                        <p class="text-white/70">Pelacakan status laporan (Terkirim, Diproses, Selesai).</p>
                    </div>

                    <div class="glass-card rounded-2xl p-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-red-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-white">📂 Manajemen Arsip</h3>
                        <p class="text-white/70">Penyimpanan riwayat laporan digital yang terorganisir.</p>
                    </div>
                </div>
            </div>

            <!-- Technology Stack -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-10 mb-8">
                <h2 class="text-3xl font-bold mb-6 text-white">Teknologi yang Digunakan</h2>
                <p class="text-white/80 leading-relaxed text-lg mb-6">
                    Aplikasi ini dibangun menggunakan teknologi web modern untuk menjamin performa dan skalabilitas:
                </p>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-2xl">🔧</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-1">Backend</h3>
                            <p class="text-white/70">Laravel (PHP Framework)</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-2xl">🎨</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-1">Frontend</h3>
                            <p class="text-white/70">Tailwind CSS & Blade Templating</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-2xl">💾</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-1">Database</h3>
                            <p class="text-white/70">MySQL</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-2xl">🌐</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-1">Web Server</h3>
                            <p class="text-white/70">Apache/Nginx</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partnership -->
            <div class="bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 border border-emerald-500/30 rounded-3xl p-10 mb-8">
                <h2 class="text-3xl font-bold mb-6 text-white">Kerjasama</h2>
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <div class="flex gap-6">
                        <img src="https://i.ibb.co.com/VcT1YMcT/Logo-Utama.png" alt="SMAN 1 TURI Logo" class="h-24 object-contain">
                        <img src="https://i.ibb.co.com/JRjQhmqm/ADW-300x149.png" alt="Smart School Logo" class="h-24 object-contain">
                    </div>
                    <div class="flex-1">
                        <p class="text-white/80 leading-relaxed text-lg">
                            Proyek ini dikembangkan dengan kerjasama bersama <strong class="text-white">SMAN 1 TURI</strong>. 
                            Jika Anda menemukan bug atau memiliki saran pengembangan fitur, silakan buat issue baru 
                            atau hubungi tim pengembang.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Company Info -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-10 mb-8">
                <h2 class="text-3xl font-bold mb-6 text-white">Tentang Perusahaan</h2>
                <p class="text-white/80 leading-relaxed text-lg mb-4">
                    e-Report adalah perangkat lunak open-source yang dikembangkan oleh 
                    <strong class="text-white">PT. Kreasi Digital Creative Minds Indonesia</strong>.
                </p>
                <p class="text-white/80 leading-relaxed text-lg mb-6">
                    Kami berkomitmen untuk menyediakan solusi teknologi pendidikan yang inovatif, 
                    membantu sekolah-sekolah di Indonesia dalam transformasi digital mereka.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="mailto:support@ereport.systems" class="inline-flex items-center px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        support@ereport.systems
                    </a>
                    <a href="https://wa.me/628990772526" target="_blank" class="inline-flex items-center px-6 py-3 bg-green-500/20 hover:bg-green-500/30 border border-green-500/30 rounded-xl transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/>
                        </svg>
                        +62 899 077 2526
                    </a>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="bg-amber-500/10 border border-amber-500/30 rounded-3xl p-10">
                <h2 class="text-3xl font-bold mb-4 text-white">⚠️ Keamanan</h2>
                <p class="text-white/80 leading-relaxed text-lg">
                    Jika Anda menemukan celah keamanan pada sistem ini, harap segera laporkan kepada 
                    tim IT sekolah atau pengembang melalui email. <strong class="text-white">Jangan mempublikasikannya secara umum.</strong>
                </p>
            </div>

            <!-- CTA Section -->
            <div class="text-center mt-16">
                <h2 class="text-3xl font-bold mb-4 text-white">Siap Memulai?</h2>
                <p class="text-xl text-white/70 mb-8">
                    Bergabunglah dengan 500+ sekolah yang telah mempercayai e-Report
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register" class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-semibold text-lg rounded-2xl hover:shadow-lg transition-all">
                        Daftar Gratis 7 Hari
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="/#contact" class="inline-flex items-center justify-center px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold text-lg rounded-2xl transition-all">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500">© {{ date('Y') }} e-Report. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="/privacy" class="text-gray-400 hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="/terms" class="text-gray-400 hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="/about" class="text-gray-400 hover:text-white transition-colors">Tentang</a>
                    <a href="/blog" class="text-gray-400 hover:text-white transition-colors">Blog</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
