<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} - e-Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 25%, #0d9488 50%, #14b8a6 75%, #34d399 100%);
            position: relative;
            overflow: hidden;
        }
        .gradient-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(ellipse 40% 60% at 30% 40%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse 50% 40% at 70% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(3%, 3%) rotate(2deg); }
            66% { transform: translate(-2%, 2%) rotate(-1deg); }
        }
    </style>
</head>
<body class="antialiased bg-gray-50 min-h-screen">
    <div class="min-h-screen flex">
        <!-- Left Side - Gradient Background -->
        <div class="hidden lg:flex lg:w-1/2 gradient-bg relative">
            <div class="absolute inset-0 flex flex-col justify-center items-center p-12 text-white">
                <!-- Logo -->
                <div class="absolute top-8 left-8 flex items-center">
                    <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-10">
                </div>
                
                <!-- Main Text -->
                <div class="text-center">
                    <h1 class="text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        Selamat<br>Datang.
                    </h1>
                    <p class="text-lg text-white/80 max-w-md">
                        Sistem manajemen laporan digital untuk sekolah modern Indonesia.
                    </p>
                </div>

                <!-- Bottom decoration -->
                <div class="absolute bottom-8 left-8 right-8">
                    <div class="flex items-center justify-between text-white/60 text-sm">
                        <span>© {{ date('Y') }} e-Report</span>
                        <a href="/" class="hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12 bg-white">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center mb-8">
                    <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-10">
                </div>

                {{ $slot }}

                <!-- Mobile Back Link -->
                <div class="lg:hidden mt-8 text-center">
                    <a href="/" class="text-gray-500 hover:text-gray-700 text-sm inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
