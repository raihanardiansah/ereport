<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? 'Login'); ?> - e-Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- reCAPTCHA v2 Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #7ED87E 0%, #3CB371 30%, #00B4D8 70%, #155E75 100%);
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
                radial-gradient(ellipse 40% 60% at 30% 40%, rgba(255, 255, 255, 0.12) 0%, transparent 50%),
                radial-gradient(ellipse 50% 40% at 70% 70%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(3%, 3%) rotate(2deg); }
            66% { transform: translate(-2%, 2%) rotate(-1deg); }
        }
    </style>
</head>
<body class="antialiased bg-white min-h-screen">
    <div class="min-h-screen flex">
        <!-- Left Side - Gradient Background -->
        <div class="hidden md:flex md:w-1/2 gradient-bg relative">
            <div class="absolute inset-0 flex flex-col justify-center items-center px-8 py-12 text-white">
                <!-- Logo -->
                <div class="absolute top-6 left-6 flex items-center">
                    <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-8">
                </div>
                
                <!-- Main Text -->
                <div class="text-center">
                    <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight mb-4">
                        Selamat<br>Datang.
                    </h1>
                    <p class="text-base text-white/80 max-w-sm">
                        Sistem manajemen laporan digital untuk sekolah modern Indonesia.
                    </p>
                </div>

                <!-- Bottom decoration -->
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="flex items-center justify-between text-white/60 text-xs">
                        <span>© <?php echo e(date('Y')); ?> e-Report</span>
                        <a href="/" class="hover:text-white transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center px-6 py-8 md:px-12 bg-white">
            <div class="w-full max-w-sm">
                <!-- Mobile Logo -->
                <div class="md:hidden flex items-center justify-center mb-6">
                    <img src="https://i.ibb.co.com/bgHHDbVR/Logo-1-1.png" alt="Logo" class="h-8">
                </div>

                <?php echo e($slot ?? ''); ?>

                <?php echo $__env->yieldContent('content'); ?>

                <!-- Mobile Back Link -->
                <div class="md:hidden mt-6 text-center">
                    <a href="/" class="text-gray-400 hover:text-gray-600 text-sm inline-flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <div id="toast-container" class="fixed top-6 right-4 z-50 space-y-3 max-w-md"></div>

    <script>
        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            // Clear existing toasts to prevent stacking
            container.innerHTML = '';

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `transform translate-x-full transition-all duration-300 ease-out opacity-0`;
            
            // Set colors based on type
            let bgColor, borderColor, textColor, icon;
            if (type === 'success') {
                bgColor = 'bg-green-600';
                borderColor = 'border-green-700';
                textColor = 'text-white';
                icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>`;
            } else if (type === 'error') {
                bgColor = 'bg-red-600';
                borderColor = 'border-red-700';
                textColor = 'text-white';
                icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`;
            } else {
                bgColor = 'bg-blue-600';
                borderColor = 'border-blue-700';
                textColor = 'text-white';
                icon = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>`;
            }

            const toastId = 'toast-' + Date.now();
            toast.innerHTML = `
                <div class="${bgColor} ${textColor} ${borderColor} border rounded-lg shadow-lg overflow-hidden min-w-[320px] max-w-md">
                    <div class="p-4 flex items-center gap-3">
                        <div class="flex-shrink-0">
                            ${icon}
                        </div>
                        <div class="flex-1 text-sm font-medium">
                            ${message}
                        </div>
                        <button onclick="this.closest('[class*=\\'translate\\']').remove()" class="flex-shrink-0 hover:opacity-75 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Progress bar countdown -->
                    <div class="h-1 bg-white/20">
                        <div id="${toastId}-progress" class="h-full bg-white transition-all duration-100 ease-linear" style="width: 100%"></div>
                    </div>
                </div>
            `;

            container.appendChild(toast);

            // Trigger slide-in animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);

            // Countdown progress bar animation (5 seconds)
            const progressBar = document.getElementById(`${toastId}-progress`);
            const duration = 5000;
            const interval = 100;
            const steps = duration / interval;
            let currentStep = 0;

            const countdown = setInterval(() => {
                currentStep++;
                const percentage = 100 - (currentStep / steps * 100);
                if (progressBar) {
                    progressBar.style.width = percentage + '%';
                }
                
                if (currentStep >= steps) {
                    clearInterval(countdown);
                }
            }, interval);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                clearInterval(countdown);
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }
    </script>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/guest.blade.php ENDPATH**/ ?>