<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blog e-Report - Artikel dan Berita Seputar Pendidikan Digital">
    <title>Blog - e-Report</title>
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
                <h1 class="text-5xl font-bold mb-6">
                    <span class="gradient-text">Blog e-Report</span>
                </h1>
                <p class="text-xl text-white/70">
                    Artikel dan berita seputar pendidikan digital
                </p>
            </div>

            <!-- Featured Post -->
            <article class="bg-white/5 border border-white/10 rounded-3xl overflow-hidden hover:bg-white/10 transition-colors mb-12">
                <div class="grid md:grid-cols-2 gap-8 p-8">
                    <div class="rounded-2xl overflow-hidden h-64 md:h-auto relative">
                        <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070&auto=format&fit=crop" 
                             alt="Modern School Reporting" 
                             class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="flex items-center gap-3 text-sm text-emerald-400 mb-4">
                            <span class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">Rilis Produk</span>
                            <span>{{ date('d F Y') }}</span>
                        </div>
                        <h2 class="text-3xl font-bold mb-4 text-white">Memperkenalkan e-Report: Transformasi Digital untuk Sekolah Modern</h2>
                        <p class="text-white/70 mb-6 leading-relaxed">
                            Hari ini kami dengan bangga meluncurkan e-Report, platform pelaporan digital komprehensif yang dirancang khusus untuk memodernisasi cara sekolah menangani laporan siswa, kedisiplinan, dan sarana prasarana.
                        </p>
                        <a href="#" class="inline-flex items-center text-emerald-400 font-semibold hover:text-emerald-300 transition-colors">
                            Baca Selengkapnya
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Recent Posts Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Post 1 -->
                <article class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:bg-white/10 transition-colors group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=2071&auto=format&fit=crop" 
                             alt="Student Safety" 
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-cyan-400 mb-2">Fitur</div>
                        <h3 class="text-xl font-bold mb-3 text-white">Anonymous Reporting: Memberikan Rasa Aman bagi Pelapor</h3>
                        <p class="text-white/60 mb-4 line-clamp-2">
                            Mengapa fitur pelaporan anonim sangat penting untuk meningkatkan partisipasi siswa dalam menjaga lingkungan sekolah.
                        </p>
                        <a href="#" class="text-sm text-white/80 group-hover:text-emerald-400 transition-colors">Baca Artikel →</a>
                    </div>
                </article>

                <!-- Post 2 -->
                <article class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:bg-white/10 transition-colors group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop" 
                             alt="Data Analytics" 
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-purple-400 mb-2">Analytics</div>
                        <h3 class="text-xl font-bold mb-3 text-white">Pentingnya Data-Driven Decision Making di Sekolah</h3>
                        <p class="text-white/60 mb-4 line-clamp-2">
                            Bagaimana dashboard analitik e-Report membantu manajemen sekolah mengambil keputusan yang lebih tepat sasaran.
                        </p>
                        <a href="#" class="text-sm text-white/80 group-hover:text-emerald-400 transition-colors">Baca Artikel →</a>
                    </div>
                </article>

                <!-- Post 3 -->
                <article class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:bg-white/10 transition-colors group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=2070&auto=format&fit=crop" 
                             alt="Gamification" 
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-yellow-400 mb-2">Gamification</div>
                        <h3 class="text-xl font-bold mb-3 text-white">Membangun Budaya Positif dengan Gamifikasi</h3>
                        <p class="text-white/60 mb-4 line-clamp-2">
                            Penerapan sistem poin dan lencana (badges) untuk mengapresiasi siswa yang berkontribusi positif.
                        </p>
                        <a href="#" class="text-sm text-white/80 group-hover:text-emerald-400 transition-colors">Baca Artikel →</a>
                    </div>
                </article>
            </div>

                <!-- Quick Links -->
                <div class="mt-12 grid sm:grid-cols-3 gap-6">
                    <a href="/about" class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl p-6 transition-all group">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Tentang e-Report</h3>
                        <p class="text-sm text-white/70">Pelajari lebih lanjut tentang platform kami</p>
                    </a>

                    <a href="/#features" class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl p-6 transition-all group">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Fitur Unggulan</h3>
                        <p class="text-sm text-white/70">Lihat semua fitur yang tersedia</p>
                    </a>

                    <a href="/#contact" class="bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl p-6 transition-all group">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white mb-2">Hubungi Kami</h3>
                        <p class="text-sm text-white/70">Ada pertanyaan? Kami siap membantu</p>
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
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
