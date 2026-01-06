<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Pengaturan'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Pengaturan</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola preferensi dan pengaturan akun Anda</p>
    </div>

    <div class="max-w-3xl">
        <!-- Notification Preferences -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Preferensi Email
            </h3>
            
            <?php if(session('success')): ?>
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                <p class="text-green-700 dark:text-green-300 text-sm"><?php echo e(session('success')); ?></p>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('settings.update')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="space-y-4">
                    <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">Notifikasi Laporan Baru</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Terima email saat ada laporan baru yang perlu ditindaklanjuti</p>
                        </div>
                        <input type="checkbox" name="email_new_report" value="1" 
                            <?php echo e($user->getEmailPreference('new_report') ? 'checked' : ''); ?>

                            class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    </label>
                    
                    <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">Update Status Laporan</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Terima email saat status laporan Anda berubah</p>
                        </div>
                        <input type="checkbox" name="email_status_update" value="1"
                            <?php echo e($user->getEmailPreference('status_update') ? 'checked' : ''); ?>

                            class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    </label>
                    
                    <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">Email Digest Mingguan</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Terima ringkasan laporan mingguan via email</p>
                        </div>
                        <input type="checkbox" name="email_weekly_digest" value="1"
                            <?php echo e($user->getEmailPreference('weekly_digest') ? 'checked' : ''); ?>

                            class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    </label>
                    
                    <label class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">Notifikasi Komentar</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Terima email saat ada komentar baru pada laporan Anda</p>
                        </div>
                        <input type="checkbox" name="email_comment_notification" value="1"
                            <?php echo e($user->getEmailPreference('comment_notification') ? 'checked' : ''); ?>

                            class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    </label>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Preferensi Email
                    </button>
                </div>
            </form>
        </div>

        <!-- Display Preferences -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Preferensi Tampilan
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Mode Gelap</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gunakan tema gelap untuk mengurangi ketegangan mata</p>
                    </div>
                    <button onclick="toggleTheme()" class="px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-colors">
                        <span id="theme-toggle-text">Toggle</span>
                    </button>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Bahasa Antarmuka</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih bahasa tampilan (Google Translate Beta)</p>
                    </div>
                    <select id="language-selector" onchange="changeLanguage(this.value)" class="px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                        <option value="">Bahasa Indonesia (Original)</option>
                        <option value="en">English</option>
                        <option value="ja">Japanese (日本語)</option>
                        <option value="zh-CN">Chinese (中文)</option>
                        <option value="de">German (Deutsch)</option>
                        <option value="fr">French (Français)</option>
                        <option value="ar">Arabic (العربية)</option>
                        <option value="ru">Russian (Русский)</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-gray-100">Jumlah Item per Halaman</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Atur berapa item yang ditampilkan dalam daftar</p>
                    </div>
                    <select class="px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                        <option value="10">10 item</option>
                        <option value="25">25 item</option>
                        <option value="50">50 item</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Akun
            </h3>
            
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 mb-1">Akun Dibuat</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($user->created_at->translatedFormat('d F Y')); ?></p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-gray-500 dark:text-gray-400 mb-1">Login Terakhir</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100"><?php echo e(now()->translatedFormat('d F Y H:i')); ?></p>
                </div>
                <?php if($user->school): ?>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg md:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400 mb-1">Sekolah</p>
                    <p class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($user->school->name); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Admin-Only Settings -->
        <?php if(auth()->user()->hasAnyRole(['admin_sekolah', 'super_admin'])): ?>
        <div class="mt-6">
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Pengaturan Admin
                </h3>
                <p class="text-yellow-700 dark:text-yellow-300 text-sm mb-4">
                    Pengaturan tambahan hanya tersedia untuk administrator.
                </p>
                <div class="flex flex-wrap gap-3">
                    <?php if(auth()->user()->isAdminSekolah()): ?>
                    <a href="<?php echo e(route('school.profile')); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Profil Sekolah
                    </a>
                    <a href="<?php echo e(route('subscriptions.index')); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Kelola Langganan
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
<?php /**PATH D:\laragon\www\E-Report\resources\views/profile/settings.blade.php ENDPATH**/ ?>