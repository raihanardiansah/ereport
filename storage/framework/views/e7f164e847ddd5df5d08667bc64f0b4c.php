<?php $__env->startSection('title', 'Checkout - ' . $package->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="<?php echo e(route('subscriptions.packages')); ?>" class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Pilihan Paket
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-2">
            <div class="card">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Checkout</h1>

                <form action="<?php echo e(route('subscriptions.process', $package)); ?>" method="POST" id="checkout-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="promo_code" id="promo-code-input" value="<?php echo e($promoCode ?? ''); ?>">

                    <!-- Package Details -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 mb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e($package->name); ?></h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mb-2"><?php echo e($package->description ?? 'Paket langganan'); ?></p>
                                <div class="flex flex-wrap gap-3 text-sm text-gray-600 dark:text-gray-400">
                                    <span>👥 <?php echo e($package->max_users); ?> pengguna</span>
                                    <span>📝 <?php echo e($package->max_reports_per_month); ?> laporan/bulan</span>
                                    <span>📅 <?php echo e($package->duration_months); ?> bulan</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($package->formatted_price); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Promo Code -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kode Promo</label>
                        <div class="flex gap-3">
                            <input type="text" id="promo-input" value="<?php echo e($promoCode ?? ''); ?>"
                                class="flex-1 px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white uppercase font-mono"
                                placeholder="Masukkan kode promo"
                                <?php echo e($promoCode ? 'readonly' : ''); ?>>
                            <button type="button" id="apply-promo-btn" onclick="validatePromo()" 
                                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 font-medium <?php echo e($promoCode ? 'hidden' : ''); ?>">
                                Terapkan
                            </button>
                            <button type="button" onclick="removePromo()" class="px-6 py-3 text-red-600 hover:text-red-800 font-medium <?php echo e($promoCode ? '' : 'hidden'); ?>" id="remove-promo-btn">
                                Hapus
                            </button>
                        </div>
                        <div id="promo-message" class="mt-2 text-sm <?php echo e($promoCode ? 'text-green-600' : 'hidden'); ?>">
                            <?php if($appliedPromo): ?>
                                ✓ Promo "<?php echo e($appliedPromo->name); ?>" (<?php echo e($appliedPromo->formatted_value); ?>) berhasil diterapkan!
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Metode Pembayaran</label>
                        <div class="grid sm:grid-cols-3 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="qris" class="peer sr-only payment-method-radio" checked>
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-8 h-8 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </div>
                                    <p class="text-center text-sm font-medium text-gray-900 dark:text-white">QRIS</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="virtual_account" class="peer sr-only payment-method-radio">
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-8 h-8 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-center text-sm font-medium text-gray-900 dark:text-white">Virtual Account</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="ewallet" class="peer sr-only payment-method-radio">
                                <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-8 h-8 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-center text-sm font-medium text-gray-900 dark:text-white">E-Wallet</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Bank VA Selection (shown when Virtual Account is selected) -->
                    <div id="bank-va-selection" class="mb-6 hidden">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Pilih Bank Virtual Account</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="bank_va" value="bca" class="peer sr-only" checked>
                                <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all hover:border-gray-300 flex flex-col items-center justify-center gap-2 min-h-[80px]">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/960px-Bank_Central_Asia.svg.png" alt="BCA" class="h-4 md:h-5 w-auto object-contain">
                                    <p class="text-center text-xs font-medium text-gray-900 dark:text-white">BCA VA</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="bank_va" value="bri" class="peer sr-only">
                                <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all hover:border-gray-300 flex flex-col items-center justify-center gap-2 min-h-[80px]">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/BRI_2025_%28with_full_name%29.svg/960px-BRI_2025_%28with_full_name%29.svg.png" alt="BRI" class="h-4 md:h-5 w-auto object-contain">
                                    <p class="text-center text-xs font-medium text-gray-900 dark:text-white">BRI VA</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="bank_va" value="bni" class="peer sr-only">
                                <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all hover:border-gray-300 flex flex-col items-center justify-center gap-2 min-h-[80px]">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Bank_Negara_Indonesia_logo_%282004%29.svg/330px-Bank_Negara_Indonesia_logo_%282004%29.svg.png" alt="BNI" class="h-4 md:h-5 w-auto object-contain">
                                    <p class="text-center text-xs font-medium text-gray-900 dark:text-white">BNI VA</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="bank_va" value="permata" class="peer sr-only">
                                <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all hover:border-gray-300 flex flex-col items-center justify-center gap-2 min-h-[80px]">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/ff/Permata_Bank_%282024%29.svg/330px-Permata_Bank_%282024%29.svg.png" alt="Permata" class="h-4 md:h-5 w-auto object-contain">
                                    <p class="text-center text-xs font-medium text-gray-900 dark:text-white">Permata VA</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="bank_va" value="cimb" class="peer sr-only">
                                <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all hover:border-gray-300 flex flex-col items-center justify-center gap-2 min-h-[80px]">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/CIMB_Niaga_logo.svg/960px-CIMB_Niaga_logo.svg.png" alt="CIMB" class="h-4 md:h-5 w-auto object-contain">
                                    <p class="text-center text-xs font-medium text-gray-900 dark:text-white">CIMB VA</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="bank_va" value="mandiri" class="peer sr-only">
                                <div class="p-3 border border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 peer-checked:ring-2 peer-checked:ring-indigo-500 transition-all hover:border-gray-300 flex flex-col items-center justify-center gap-2 min-h-[80px]">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/330px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" class="h-4 md:h-5 w-auto object-contain">
                                    <p class="text-center text-xs font-medium text-gray-900 dark:text-white">Mandiri VA</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- School Info -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 mb-6">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informasi Sekolah</h4>
                        <p class="text-gray-900 dark:text-white font-medium"><?php echo e($school->name); ?></p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm"><?php echo e($school->email); ?></p>
                    </div>

                    <button type="submit" class="w-full btn-primary py-4 text-lg">
                        Lanjutkan Pembayaran
                    </button>
                </form>
            </div>
        </div>

        <!-- Price Summary -->
        <div class="lg:col-span-1">
            <div class="card sticky top-24">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ringkasan</h2>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span><?php echo e($package->name); ?></span>
                        <span><?php echo e($package->formatted_price); ?></span>
                    </div>
                    <div id="discount-row" class="flex justify-between text-green-600 <?php echo e($promoDiscount > 0 ? '' : 'hidden'); ?>">
                        <span>Diskon Promo</span>
                        <span id="discount-amount">-Rp <?php echo e(number_format($promoDiscount, 0, ',', '.')); ?></span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-900 dark:text-white">Total</span>
                        <span id="total-price" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">Rp <?php echo e(number_format($finalPrice, 0, ',', '.')); ?></span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-start gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Pembayaran aman dan terenkripsi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const packagePrice = <?php echo e($package->price); ?>;
const packageId = <?php echo e($package->id); ?>;

// Toggle bank VA selection based on payment method
document.querySelectorAll('.payment-method-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        const bankVaSection = document.getElementById('bank-va-selection');
        if (this.value === 'virtual_account') {
            bankVaSection.classList.remove('hidden');
        } else {
            bankVaSection.classList.add('hidden');
        }
    });
});

// Handle form submission
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Memproses...';
    
    // Get selected payment method
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethod) {
        showToast('Silakan pilih metode pembayaran', 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        return;
    }
    
    // Get selected bank VA if Virtual Account is selected
    let bankVa = null;
    if (paymentMethod.value === 'virtual_account') {
        const selectedBank = document.querySelector('input[name="bank_va"]:checked');
        if (selectedBank) {
            bankVa = selectedBank.value;
        }
    }
    
    try {
        // Create transaction via Midtrans
        const response = await fetch('<?php echo e(route("subscriptions.process", $package)); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                payment_method: paymentMethod.value,
                bank_va: bankVa
            })
        });
        
        // Check if response is ok
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({ error: 'Server error' }));
            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.order_id) {
            // Check if this is a trial package
            if (data.is_trial && data.redirect_url) {
                // For trial packages, redirect to dashboard with success message
                window.location.href = data.redirect_url + '?trial_activated=1';
            } else {
                // For paid packages, redirect to waiting payment page
                window.location.href = '<?php echo e(url("subscriptions/waiting")); ?>/' + data.order_id;
            }
        } else {
            showToast('Gagal membuat transaksi: ' + (data.error || 'Unknown error'), 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan: ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

async function validatePromo() {
    const code = document.getElementById('promo-input').value.trim();
    if (!code) return;
    
    const btn = document.getElementById('apply-promo-btn');
    btn.textContent = 'Memvalidasi...';
    btn.disabled = true;
    
    try {
        const response = await fetch('<?php echo e(route("subscriptions.validate-promo")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ code, package_id: packageId })
        });
        
        const data = await response.json();
        const messageEl = document.getElementById('promo-message');
        
        if (data.valid) {
            messageEl.textContent = '✓ ' + data.message;
            messageEl.className = 'mt-2 text-sm text-green-600';
            messageEl.classList.remove('hidden');
            
            // Update UI
            document.getElementById('promo-code-input').value = code.toUpperCase();
            document.getElementById('promo-input').readOnly = true;
            document.getElementById('apply-promo-btn').classList.add('hidden');
            document.getElementById('remove-promo-btn').classList.remove('hidden');
            
            // Update price display
            document.getElementById('discount-row').classList.remove('hidden');
            document.getElementById('discount-amount').textContent = '-' + data.formatted_discount;
            document.getElementById('total-price').textContent = data.formatted_final_price;
        } else {
            messageEl.textContent = '✗ ' + data.message;
            messageEl.className = 'mt-2 text-sm text-red-600';
            messageEl.classList.remove('hidden');
        }
    } catch (error) {
        document.getElementById('promo-message').textContent = '✗ Gagal memvalidasi kode promo';
        document.getElementById('promo-message').className = 'mt-2 text-sm text-red-600';
        document.getElementById('promo-message').classList.remove('hidden');
    } finally {
        btn.textContent = 'Terapkan';
        btn.disabled = false;
    }
}

function removePromo() {
    document.getElementById('promo-code-input').value = '';
    document.getElementById('promo-input').value = '';
    document.getElementById('promo-input').readOnly = false;
    document.getElementById('apply-promo-btn').classList.remove('hidden');
    document.getElementById('remove-promo-btn').classList.add('hidden');
    document.getElementById('promo-message').classList.add('hidden');
    document.getElementById('discount-row').classList.add('hidden');
    document.getElementById('total-price').textContent = 'Rp <?php echo e(number_format($package->price, 0, ",", ".")); ?>';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\E-Report\resources\views/subscriptions/checkout.blade.php ENDPATH**/ ?>