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
                <div class="flex items-center">
                    <a href="/">
                        <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-11">
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-gray-300 hover:text-white font-medium transition-colors">Beranda</a>
                    <a href="/about" class="text-gray-300 hover:text-white font-medium transition-colors">Tentang</a>
                    <a href="/login" class="text-gray-300 hover:text-white font-medium transition-colors">Login</a>
                </div>
            </div>
        </div>
    </nav>

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

            <!-- Empty State -->
            <div class="max-w-2xl mx-auto text-center">
                <div class="bg-white/5 border border-white/10 rounded-3xl p-16">
                    <div class="mb-8">
                        <svg class="w-24 h-24 mx-auto text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 text-white">Segera Hadir</h2>
                    <p class="text-xl text-white/70 mb-8">
                        Kami sedang menyiapkan konten blog yang menarik untuk Anda. 
                        Nantikan artikel seputar tips manajemen sekolah, update fitur terbaru, 
                        dan best practices dalam pengelolaan laporan siswa.
                    </p>
                    
                    <!-- Newsletter Subscription (Optional) -->
                    <div class="bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 border border-emerald-500/30 rounded-2xl p-8">
                        <h3 class="text-xl font-bold mb-3 text-white">Dapatkan Notifikasi</h3>
                        <p class="text-white/70 mb-6">
                            Daftarkan email Anda untuk mendapatkan pemberitahuan saat artikel pertama kami dipublikasikan.
                        </p>
                        <form class="flex flex-col sm:flex-row gap-3">
                            <input 
                                type="email" 
                                placeholder="email@sekolah.sch.id" 
                                class="flex-1 px-5 py-3 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-white placeholder-gray-500"
                                required
                            >
                            <button 
                                type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-semibold rounded-xl hover:shadow-lg transition-all"
                            >
                                Berlangganan
                            </button>
                        </form>
                    </div>
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
