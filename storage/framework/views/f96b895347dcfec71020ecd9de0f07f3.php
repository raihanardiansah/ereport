<?php if (isset($component)) { $__componentOriginal7ddf49af801524849d67e38f92bf39c7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7ddf49af801524849d67e38f92bf39c7 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\Guest::resolve(['title' => 'Lupa Password'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.guest'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\Guest::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Lupa Password</h1>
        <p class="text-gray-500 mt-1 text-sm">Masukkan email untuk menerima link reset password.</p>
    </div>

    
    <?php if(session('success')): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-medium">Email Terkirim!</p>
                    <p class="mt-1"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="mb-4 p-3 bg-danger-50 border border-danger-200 text-danger-700 rounded-lg text-sm">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Forgot Password Form -->
    <form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                Email
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?php echo e(old('email')); ?>"
                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3CB371] focus:border-[#3CB371] transition-all text-sm <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                placeholder="Masukkan email terdaftar"
                required
                autofocus
            >
        </div>



        <!-- Submit Button -->
        <button type="submit" class="w-full bg-gradient-to-r from-[#3CB371] to-[#00B4D8] hover:from-[#2E8B57] hover:to-[#0096C7] text-white font-semibold py-2.5 px-4 rounded-lg transition-all flex items-center justify-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Kirim Link Reset Password
        </button>
    </form>

    <!-- Back to Login -->
    <div class="mt-6 text-center text-sm">
        <a href="<?php echo e(route('login')); ?>" class="text-[#00B4D8] hover:text-[#155E75] font-medium inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Login
        </a>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7ddf49af801524849d67e38f92bf39c7)): ?>
<?php $attributes = $__attributesOriginal7ddf49af801524849d67e38f92bf39c7; ?>
<?php unset($__attributesOriginal7ddf49af801524849d67e38f92bf39c7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7ddf49af801524849d67e38f92bf39c7)): ?>
<?php $component = $__componentOriginal7ddf49af801524849d67e38f92bf39c7; ?>
<?php unset($__componentOriginal7ddf49af801524849d67e38f92bf39c7); ?>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>