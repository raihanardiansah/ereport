<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="e-Report - Sistem manajemen laporan siswa digital untuk sekolah modern.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>e-Report - Sistem Laporan Siswa Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /* Modern Aurora Gradient Background */
        .aurora-bg {
            background: linear-gradient(135deg, #155E75 0%, #0d9488 25%, #3CB371 50%, #0d9488 75%, #155E75 100%);
            position: relative;
            overflow: hidden;
        }
        .aurora-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: 
                radial-gradient(ellipse 80% 50% at 20% 40%, rgba(0, 180, 216, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse 60% 40% at 80% 60%, rgba(60, 179, 113, 0.25) 0%, transparent 50%),
                radial-gradient(ellipse 50% 30% at 40% 80%, rgba(126, 216, 126, 0.2) 0%, transparent 50%);
            animation: aurora 15s ease-in-out infinite;
        }
        @keyframes aurora {
            0%, 100% { transform: translateX(0) rotate(0deg); opacity: 1; }
            25% { transform: translateX(5%) rotate(1deg); opacity: 0.8; }
            50% { transform: translateX(-5%) rotate(-1deg); opacity: 1; }
            75% { transform: translateX(3%) rotate(0.5deg); opacity: 0.9; }
        }
        
        /* Floating Elements */
        .float-slow { animation: floatSlow 8s ease-in-out infinite; }
        .float-medium { animation: floatMedium 6s ease-in-out infinite; }
        .float-fast { animation: floatFast 4s ease-in-out infinite; }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(3deg); }
        }
        @keyframes floatMedium {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-2deg); }
        }
        @keyframes floatFast {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #B4F8B4 50%, #7FDBFF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        /* Modern Button Glow */
        .btn-glow {
            position: relative;
            background: linear-gradient(135deg, #3CB371 0%, #00B4D8 100%);
            box-shadow: 0 0 30px rgba(0, 180, 216, 0.4);
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 50px rgba(60, 179, 113, 0.6);
        }
        
        /* Bento Grid */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.5rem;
        }
        .bento-item { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .bento-item:hover { transform: translateY(-8px); }
        
        /* Scroll Reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Noise Texture */
        .noise::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            opacity: 0.02;
            pointer-events: none;
        }
    </style>
</head>
<body class="antialiased bg-slate-950 text-white">
    <div id="google_translate_element" class="hidden"></div>
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-11">
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-gray-300 hover:text-white font-medium transition-colors">Home</a>
                    <a href="#features" class="text-gray-300 hover:text-white font-medium transition-colors">Fitur</a>
                    <a href="#pricing" class="text-gray-300 hover:text-white font-medium transition-colors">Harga</a>
                    <a href="#contact" class="text-gray-300 hover:text-white font-medium transition-colors">Kontak</a>
                </div>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/login" class="text-gray-300 hover:text-white font-medium transition-colors">Login</a>
                    <a href="/register" class="btn-glow text-white font-semibold py-2.5 px-6 rounded-xl">
                        Daftar Gratis
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden glass-card border-t border-white/10">
            <div class="px-4 py-4 space-y-3">
                <a href="#home" class="block text-gray-300 hover:text-white font-medium py-2">Home</a>
                <a href="#features" class="block text-gray-300 hover:text-white font-medium py-2">Fitur</a>
                <a href="#pricing" class="block text-gray-300 hover:text-white font-medium py-2">Harga</a>
                <a href="#contact" class="block text-gray-300 hover:text-white font-medium py-2">Kontak</a>
                <hr class="border-white/10 my-3">
                <a href="/login" class="block text-gray-300 font-medium py-2">Login</a>
                <a href="/register" class="block btn-glow text-center text-white font-semibold py-3 rounded-xl">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="aurora-bg noise min-h-screen flex items-center relative">
        <!-- Floating Orbs -->
        <div class="absolute top-32 left-20 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl float-slow"></div>
        <div class="absolute bottom-32 right-20 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl float-medium"></div>
        <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-pink-500/10 rounded-full blur-2xl float-fast"></div>
        
        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:64px_64px] [mask-image:radial-gradient(ellipse_50%_50%_at_50%_50%,black_40%,transparent_100%)]"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 lg:py-40">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 glass-card rounded-full mb-8 reveal">
                    <span class="flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-gray-300 text-sm font-medium">Platform Terpercaya 500+ Sekolah</span>
                </div>
                
                <!-- Headline -->
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black leading-tight mb-8 reveal">
                    <span class="text-white">Kelola Laporan</span><br>
                    <span class="gradient-text">Siswa Tanpa Ribet</span>
                </h1>
                
                <!-- Subheadline -->
                <p class="text-xl text-white/80 mb-10 max-w-2xl mx-auto reveal">
                    Sistem manajemen laporan digital yang membantu sekolah memproses, melacak, dan menangani pelanggaran siswa dengan efisien dan terstruktur.
                </p>
                
                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center reveal">
                    <a href="/register" class="btn-glow text-white font-semibold text-lg py-4 px-10 rounded-2xl inline-flex items-center justify-center group">
                        Mulai Gratis 7 Hari
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#features" class="glass-card text-white font-semibold text-lg py-4 px-10 rounded-2xl inline-flex items-center justify-center hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lihat Demo
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 mt-16 pt-16 border-t border-white/20 reveal">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">500+</div>
                        <div class="text-white/70 mt-2 text-sm uppercase tracking-wider">Sekolah</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">50K+</div>
                        <div class="text-white/70 mt-2 text-sm uppercase tracking-wider">Laporan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-white">99.9%</div>
                        <div class="text-white/70 mt-2 text-sm uppercase tracking-wider">Uptime</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <!-- Features Section - Bento Grid -->
    <section id="features" class="py-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-20 reveal">
                <div class="inline-flex items-center px-4 py-2 glass-card rounded-full mb-6">
                    <span class="text-cyan-400 text-sm font-medium inline-flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> Fitur Unggulan</span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    Semua yang Anda <span class="gradient-text">Butuhkan</span>
                </h2>
                <p class="text-xl text-white/70">
                    Fitur lengkap untuk mengelola laporan siswa dari awal hingga selesai
                </p>
            </div>

            <!-- Bento Grid -->
            <div class="bento-grid">
                <!-- Main Feature -->
                <div class="bento-item col-span-12 lg:col-span-8 glass-card rounded-3xl p-8 lg:p-12 reveal">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="flex-1">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl lg:text-3xl font-bold mb-4">Dashboard Analytics Real-time</h3>
                            <p class="text-white/70 text-lg mb-6">
                                Pantau semua laporan, tren pelanggaran, dan progress penanganan dalam satu dashboard yang intuitif.
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <span class="px-4 py-2 bg-white/5 rounded-lg text-sm text-gray-300">Grafik Interaktif</span>
                                <span class="px-4 py-2 bg-white/5 rounded-lg text-sm text-gray-300">Export PDF</span>
                                <span class="px-4 py-2 bg-white/5 rounded-lg text-sm text-gray-300">Filter Canggih</span>
                            </div>
                        </div>
                        <div class="flex-1 rounded-2xl overflow-hidden shadow-2xl relative min-h-[300px] h-full group bg-black">
                            <iframe 
                                class="absolute inset-0 w-full h-full"
                                src="https://www.youtube.com/embed/_o_LpWK9PYQ?si=xBAbmol_CI_Zql5O&autoplay=1&mute=1&loop=1&playlist=_o_LpWK9PYQ&controls=1&rel=0" 
                                title="App Demo"
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="bento-item col-span-12 lg:col-span-4 glass-card rounded-3xl p-8 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Keamanan Maksimal</h3>
                    <p class="text-white/70">Enkripsi end-to-end, backup otomatis, dan audit log untuk data yang aman.</p>
                </div>

                <!-- Multi Role -->
                <div class="bento-item col-span-12 sm:col-span-6 lg:col-span-4 glass-card rounded-3xl p-8 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Multi-Role Access</h3>
                    <p class="text-white/70">Super Admin, Admin Sekolah, Guru BK, dan Guru dengan akses yang berbeda.</p>
                </div>

                <!-- Notifications -->
                <div class="bento-item col-span-12 sm:col-span-6 lg:col-span-4 glass-card rounded-3xl p-8 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Notifikasi Real-time</h3>
                    <p class="text-white/70">Notifikasi in-app, email, dan WhatsApp untuk update status laporan.</p>
                </div>

                <!-- Tracking -->
                <div class="bento-item col-span-12 sm:col-span-6 lg:col-span-4 glass-card rounded-3xl p-8 reveal">
                    <div class="w-14 h-14 bg-gradient-to-br from-sky-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Status Tracking</h3>
                    <p class="text-white/70">Lacak progress setiap laporan dari laporan masuk hingga selesai ditangani.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-32 relative">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-teal-950/30 to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-12 reveal">
                <div class="inline-flex items-center px-4 py-2 glass-card rounded-full mb-6">
                    <span class="text-cyan-400 text-sm font-medium inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Harga Transparan</span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    Pilih Paket <span class="gradient-text">Terbaik</span>
                </h2>
                <p class="text-xl text-white/70">
                    Mulai gratis dengan trial 7 hari, upgrade kapan saja. Tanpa biaya tersembunyi.
                </p>
            </div>

            <!-- Trial Banner -->
            <div class="glass-card rounded-3xl p-8 mb-12 reveal border border-green-500/30 bg-gradient-to-r from-green-500/10 to-emerald-500/10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center text-3xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Coba Gratis 7 Hari!</h3>
                            <p class="text-white/70">Akses semua fitur premium tanpa kartu kredit. Daftar sekarang dan mulai transformasi sekolah Anda.</p>
                        </div>
                    </div>
                    <a href="/register" class="flex-shrink-0 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-semibold text-lg py-4 px-8 rounded-2xl transition-all shadow-lg shadow-green-500/25 hover:shadow-green-500/40">
                        Mulai Trial Gratis
                    </a>
                </div>
            </div>

            <!-- Pricing Cards -->
            <div class="grid md:grid-cols-3 gap-8">
                @forelse($packages ?? [] as $index => $package)
                @php
                    $isPopular = $index === 1; // Second package is "popular"
                    $features = is_array($package->features) ? $package->features : [];
                @endphp
                <div class="relative glass-card rounded-3xl p-8 reveal bento-item flex flex-col {{ $isPopular ? 'border-2 border-cyan-500/50' : '' }}">
                    @if($isPopular)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white text-sm font-semibold px-4 py-1 rounded-full">
                            Paling Populer
                        </span>
                    </div>
                    @endif
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-2">{{ $package->name }}</h3>
                        <p class="text-white/70">{{ $package->description }}</p>
                    </div>
                    <div class="mb-6">
                        @if($package->price == 0)
                        <span class="text-5xl font-bold">Gratis</span>
                        @else
                        <span class="text-5xl font-bold">Rp {{ number_format($package->price / 1000) }}K</span>
                        <span class="text-white/70">/bulan</span>
                        @endif
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Hingga {{ $package->max_users }} pengguna
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $package->max_reports_per_month }} laporan/bulan
                        </li>
                        @foreach($features as $feature)
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="/register" class="block w-full {{ $isPopular ? 'btn-glow' : 'glass-card hover:bg-white/10' }} text-center text-white font-semibold py-4 rounded-2xl transition-colors mt-auto">
                        {{ $package->price == 0 ? 'Mulai Gratis' : 'Pilih Paket Ini' }}
                    </a>
                </div>
                @empty
                <!-- Fallback static cards if no packages -->
                <div class="glass-card rounded-3xl p-8 reveal bento-item flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-2">Starter</h3>
                        <p class="text-white/70">Untuk sekolah kecil</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-5xl font-bold">Rp 99K</span>
                        <span class="text-white/70">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            25 pengguna
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            50 laporan/bulan
                        </li>
                    </ul>
                    <a href="/register" class="block w-full glass-card text-center text-white font-semibold py-4 rounded-2xl hover:bg-white/10 transition-colors mt-auto">
                        Mulai Trial
                    </a>
                </div>
                <div class="relative glass-card rounded-3xl p-8 reveal bento-item flex flex-col border-2 border-cyan-500/50">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white text-sm font-semibold px-4 py-1 rounded-full">
                            Paling Populer
                        </span>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-2">Professional</h3>
                        <p class="text-white/70">Untuk sekolah menengah</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-5xl font-bold">Rp 249K</span>
                        <span class="text-white/70">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            100 pengguna
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            200 laporan/bulan
                        </li>
                    </ul>
                    <a href="/register" class="block w-full btn-glow text-center text-white font-semibold py-4 rounded-2xl mt-auto">
                        Pilih Paket Ini
                    </a>
                </div>
                <div class="glass-card rounded-3xl p-8 reveal bento-item flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold mb-2">Enterprise</h3>
                        <p class="text-white/70">Untuk sekolah besar</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-5xl font-bold">Rp 499K</span>
                        <span class="text-white/70">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            500 pengguna
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            1000 laporan/bulan
                        </li>
                    </ul>
                    <a href="/register" class="block w-full glass-card text-center text-white font-semibold py-4 rounded-2xl hover:bg-white/10 transition-colors mt-auto">
                        Hubungi Kami
                    </a>
                </div>
                @endforelse
            </div>

            <!-- Bottom Trial Reminder -->
            <div class="text-center mt-12 reveal">
                <p class="text-white/80 text-lg inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Semua paket dimulai dengan <span class="text-emerald-400 font-semibold">trial gratis 7 hari</span> tanpa kartu kredit!
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Contact Info -->
                <div class="reveal">
                    <div class="inline-flex items-center px-4 py-2 glass-card rounded-full mb-6">
                        <span class="text-cyan-400 text-sm font-medium inline-flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Hubungi Kami</span>
                    </div>
                    <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                        Ada <span class="gradient-text">Pertanyaan?</span>
                    </h2>
                    <p class="text-xl text-white/70 mb-10">
                        Tim kami siap membantu Anda. Kirim pesan dan kami akan merespons secepatnya.
                    </p>
                    
                    <div class="space-y-6">
                        <a href="https://wa.me/628990772526" target="_blank" class="flex items-center p-5 glass-card rounded-2xl hover:bg-white/5 transition-colors group">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mr-5">
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">WhatsApp</p>
                                <p class="text-white/70">+62 899 077 2526</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 ml-auto group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                        
                        <a href="mailto:support@ereport.systems" class="flex items-center p-5 glass-card rounded-2xl hover:bg-white/5 transition-colors group">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center mr-5">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Email</p>
                                <p class="text-white/70">support@ereport.systems</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-500 ml-auto group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="glass-card rounded-3xl p-8 lg:p-10 reveal">
                    <h3 class="text-2xl font-bold mb-6">Kirim Pesan</h3>
                    <div id="form-message" class="hidden p-4 rounded-xl text-sm mb-6"></div>
                    <form id="contact-form" class="space-y-6">
                        @csrf
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nama <span class="text-red-400">*</span></label>
                                <input type="text" name="name" required class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-white placeholder-gray-500" placeholder="Nama lengkap">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email <span class="text-red-400">*</span></label>
                                <input type="email" name="email" required class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-white placeholder-gray-500" placeholder="email@sekolah.sch.id">
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Sekolah</label>
                                <input type="text" name="school_name" class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-white placeholder-gray-500" placeholder="Opsional">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">WhatsApp</label>
                                <input type="tel" name="whatsapp" class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-white placeholder-gray-500" placeholder="08xx-xxxx-xxxx">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pesan <span class="text-red-400">*</span></label>
                            <textarea rows="4" name="message" required class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-white placeholder-gray-500 resize-none" placeholder="Tulis pesan Anda..."></textarea>
                        </div>
                        <div class="mb-6">
                            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div>
                        <button type="submit" id="submit-btn" class="w-full btn-glow text-white font-semibold py-4 rounded-xl">
                            <span id="btn-text">Kirim Pesan</span>
                            <span id="btn-loading" class="hidden">
                                <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Mengirim...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-16 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center mb-6">
                        <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-10">
                    </div>
                    <p class="text-white/70">E-Report adalah sistem manajemen laporan siswa digital untuk sekolah.</p>
                </div>
                
                <!-- Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Produk</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-white/70 hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#pricing" class="text-white/70 hover:text-white transition-colors">Harga</a></li>
                        <li><a href="/register" class="text-white/70 hover:text-white transition-colors">Daftar</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Perusahaan</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Tentang</a></li>
                        <li><a href="#contact" class="text-white/70 hover:text-white transition-colors">Kontak</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Legal</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Privacy</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Terms</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500">© {{ date('Y') }} e-Report. All rights reserved.</p>
                
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <select id="language-selector" onchange="changeLanguage(this.value)" class="bg-slate-900 border border-white/10 rounded-lg text-sm text-gray-400 focus:ring-emerald-500 focus:border-emerald-500 p-2 cursor-pointer">
                        <option value="">Bahasa Indonesia</option>
                        <option value="en">English</option>
                        <option value="ja">Japanese (日本語)</option>
                        <option value="zh-CN">Chinese (中文)</option>
                        <option value="de">German (Deutsch)</option>
                        <option value="fr">French (Français)</option>
                        <option value="ar">Arabic (العربية)</option>
                        <option value="ru">Russian (Русский)</option>
                    </select>
                    <p class="text-gray-500 inline-flex items-center gap-1">PT. KREASI DIGITAL CREATIVE MINDS </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        /* Multi-Language Dropdown Logic */
        function changeLanguage(langCode) {
            if (!langCode) {
                // Revert to original (Indonesian) -> clear cookies
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + window.location.hostname;
                window.location.reload();
            } else {
                // Set Google Translate cookie: /source/target
                document.cookie = `googtrans=/id/${langCode}; path=/`;
                window.location.reload();
            }
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        function updateActiveLanguage() {
            const selector = document.getElementById('language-selector');
            if (selector) {
                const currentCookie = getCookie('googtrans'); // e.g., "/id/en" or "/id/ja"
                if (currentCookie) {
                    const code = currentCookie.split('/')[2]; // get "en" from "/id/en"
                    if (code) selector.value = code;
                }
            }
        }

        // Initialize Google Translate
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'en,ja,zh-CN,de,fr,ar,ru',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }

        // Load Google Translate Script
        (function() {
            var googleTranslateScript = document.createElement('script');
            googleTranslateScript.type = 'text/javascript';
            googleTranslateScript.async = true;
            googleTranslateScript.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(googleTranslateScript);
        })();

        // Update selector on load
        document.addEventListener('DOMContentLoaded', () => {
            updateActiveLanguage();
        });

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Scroll reveal
        const revealElements = document.querySelectorAll('.reveal');
        const revealOnScroll = () => {
            revealElements.forEach(el => {
                const elementTop = el.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                if (elementTop < windowHeight - 100) {
                    el.classList.add('active');
                }
            });
        };
        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('glass-card');
            } else {
                navbar.classList.remove('glass-card');
            }
        });

        // Contact form
        document.getElementById('contact-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const formMessage = document.getElementById('form-message');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const submitBtn = document.getElementById('submit-btn');
            
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            submitBtn.disabled = true;
            formMessage.classList.add('hidden');
            
            try {
                const formData = new FormData(form);
                const response = await fetch('/contact', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    formMessage.textContent = data.message;
                    formMessage.className = 'p-4 rounded-xl text-sm bg-green-500/20 text-green-400 border border-green-500/30';
                    formMessage.classList.remove('hidden');
                    form.reset();
                } else {
                    formMessage.textContent = data.message || 'Terjadi kesalahan.';
                    formMessage.className = 'p-4 rounded-xl text-sm bg-red-500/20 text-red-400 border border-red-500/30';
                    formMessage.classList.remove('hidden');
                }
            } catch (error) {
                formMessage.textContent = 'Tidak dapat mengirim pesan.';
                formMessage.className = 'p-4 rounded-xl text-sm bg-red-500/20 text-red-400 border border-red-500/30';
                formMessage.classList.remove('hidden');
            } finally {
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
                submitBtn.disabled = false;
            }
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>
