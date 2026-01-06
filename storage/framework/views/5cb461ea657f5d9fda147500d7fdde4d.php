<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Menunggu Pembayaran'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Waiting Status Card -->
            <div id="waiting-card" class="bg-white rounded-2xl shadow-sm p-8 mb-6 text-center">
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h1>
                <p class="text-gray-600 mb-6">Silakan selesaikan pembayaran sebelum batas waktu</p>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-1">Batas waktu pembayaran</p>
                    <p class="text-xl font-semibold text-gray-900" id="expiry-time">
                        <?php echo e($transaction->expiry_time ? $transaction->expiry_time->format('d M Y, H:i') . ' WIB' : '24 jam dari sekarang'); ?>

                    </p>
                </div>
            </div>

            <!-- Success Status Card (Hidden by default) -->
            <div id="success-card" class="hidden bg-white rounded-2xl shadow-sm p-8 mb-6 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-green-600 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-gray-600 mb-4">Terima kasih, pembayaran Anda telah kami terima.</p>
                <p class="text-sm text-gray-500">Subscription Anda telah aktif.</p>
                <p class="text-sm text-gray-500 mt-2">Redirecting ke dashboard dalam <span id="countdown">5</span> detik...</p>
            </div>

            <?php
                $isQris = ($transaction->payment_method === 'gopay' || $transaction->payment_method === 'qris');
                $wrapperClass = $isQris ? 'flex flex-col gap-6' : 'grid md:grid-cols-2 gap-6';
            ?>

            <div id="payment-content" class="<?php echo e($wrapperClass); ?> hidden">
                <!-- Detail Pembayaran -->
                <div class="bg-white rounded-2xl shadow-sm p-6 <?php echo e($isQris ? 'order-2' : ''); ?>">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Pembayaran</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">No. Invoice</span>
                            <span class="font-mono font-semibold text-gray-900"><?php echo e($transaction->order_id); ?></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Paket</span>
                            <span class="font-semibold text-gray-900"><?php echo e($transaction->package->name); ?></span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Durasi</span>
                            <span class="font-semibold text-gray-900"><?php echo e($transaction->package->duration_months); ?> bulan</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Metode</span>
                            <span class="font-semibold text-gray-900"><?php echo e($transaction->payment_method ? ucwords(str_replace('_', ' ', $transaction->payment_method)) : 'Virtual Account'); ?></span>
                        </div>
                        <div class="flex justify-between py-3">
                            <span class="text-gray-900 font-semibold">Total Bayar</span>
                            <span class="text-2xl font-bold text-primary-600">Rp <?php echo e(number_format($transaction->gross_amount, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Instruksi Pembayaran -->
                <div class="bg-white rounded-2xl shadow-sm p-6 <?php echo e($isQris ? 'order-1' : ''); ?>">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Instruksi Pembayaran</h2>
                    
                    <?php if(($transaction->payment_method === 'gopay' || $transaction->payment_method === 'qris') && $transaction->qr_code_url): ?>
                    <!-- QRIS Payment Section -->
                    <!-- QRIS Payment Section -->
                    <!-- QRIS Payment Section -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 mb-6">
                        <!-- Header with Logos -->
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-2">
                                <div class="bg-white px-3 py-1 rounded-lg shadow-sm">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/QRIS_logo.svg/330px-QRIS_logo.svg.png" alt="QRIS" class="h-6">
                                </div>
                            </div>
                            <div class="bg-white px-3 py-1 rounded-lg shadow-sm">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Gerbang_Pembayaran_Nasional_logo.svg/250px-Gerbang_Pembayaran_Nasional_logo.svg.png" alt="GPN" class="h-6">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Left Column: Visuals (QR, Timer) -->
                            <div class="text-center md:text-left">
                                <!-- Logos moved to header -->

                                <!-- Countdown Timer -->
                                <?php if($transaction->expiry_time): ?>
                                <div class="bg-white rounded-xl p-3 mb-6 shadow-sm">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="bg-orange-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Batas Pembayaran</span>
                                        <div id="qris-countdown" class="flex gap-1 text-orange-500 font-bold text-lg">
                                            <span id="days">00</span>:<span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-center gap-4 text-xs text-gray-500 mt-1">
                                        <span>Hari</span>
                                        <span>Jam</span>
                                        <span>Menit</span>
                                        <span>Detik</span>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- QR Code -->
                                <div class="bg-white rounded-xl p-4 shadow-md inline-block mb-4 md:mb-0">
                                    <img src="<?php echo e($transaction->qr_code_url); ?>" 
                                         alt="QRIS QR Code" 
                                         class="w-64 h-64 border-4 border-gray-200 rounded-lg">
                                </div>
                            </div>



                            <!-- Right Column: Info & Instructions -->
                            <div>
                                <!-- Title -->
                                <div class="mb-6 text-center md:text-left">
                                    <h3 class="text-xl font-bold text-gray-800 mb-1">Terima Pembayaran QRIS</h3>
                                    <p class="text-sm text-gray-600">Scan QR code untuk menyelesaikan pembayaran.</p>
                                </div>

                                <!-- Merchant Info & Demo URL -->
                                <div class="bg-white rounded-xl p-4 shadow-sm mb-4">
                                    <div class="text-center md:text-left">
                                        <p class="text-sm font-semibold text-gray-700 mb-1"><?php echo e(auth()->user()->school->name ?? 'E-Report'); ?></p>
                                        <?php if(isset($transaction->merchant_id)): ?>
                                        <p class="text-xs text-gray-500 mb-2">NMID: <?php echo e($transaction->merchant_id); ?></p>
                                        <?php endif; ?>
                                        
                                        <!-- Demo: Copyable QR URL -->
                                        <div class="pt-2 border-t border-gray-100">
                                            <p class="text-[10px] text-gray-400 mb-1">Demo: Copy QR Image URL</p>
                                            <div class="flex items-center gap-2 bg-gray-50 rounded p-2">
                                                <input type="text" 
                                                       value="<?php echo e($transaction->qr_code_url); ?>" 
                                                       id="qr-url-input"
                                                       class="text-xs bg-transparent border-none p-0 w-full text-gray-500 focus:ring-0 truncate" 
                                                       readonly>
                                                <button onclick="copyQrUrl()" class="p-1 text-gray-400 hover:text-blue-600 transition-colors" title="Copy URL">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Total Payment -->
                                <div class="bg-white rounded-xl p-4 shadow-sm mb-4">
                                    <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                                    <p class="text-3xl font-bold text-indigo-600">Rp <?php echo e(number_format($transaction->gross_amount, 0, ',', '.')); ?></p>
                                </div>

                                <!-- Instructions -->
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <p class="text-sm font-semibold text-blue-900 mb-2">📱 Cara Pembayaran:</p>
                                    <ol class="text-sm text-blue-800 space-y-1 ml-4 list-decimal">
                                        <li>Buka aplikasi e-wallet / m-banking</li>
                                        <li>Pilih menu Scan QR / QRIS</li>
                                        <li>Scan QR Code di samping</li>
                                        <li>Konfirmasi pembayaran</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <?php elseif($transaction->payment_method === 'gopay' || $transaction->payment_method === 'qris'): ?>
                    <!-- QRIS Loading State -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-yellow-600 mx-auto mb-4"></div>
                        <h3 class="text-lg font-semibold text-yellow-900 mb-2">Memuat QR Code QRIS...</h3>
                        <p class="text-sm text-yellow-700">Mohon tunggu sebentar, QR code sedang di-generate oleh Midtrans.</p>
                        <button onclick="window.location.reload()" class="mt-4 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                            Refresh Halaman
                        </button>
                    </div>
                    <?php elseif($transaction->va_number): ?>
                    <!-- Virtual Account Number -->
                    <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 mb-4">
                        <p class="text-sm text-primary-700 font-medium mb-2">Nomor Virtual Account:</p>
                        <div class="flex items-center justify-between bg-white rounded-lg p-3 gap-3">
                            <span class="text-lg md:text-xl font-bold text-primary-600 font-mono tracking-wide break-all" id="va-number"><?php echo e($transaction->va_number); ?></span>
                            <button onclick="copyVA()" class="flex-shrink-0 p-2 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors" title="Copy VA Number">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                        <?php
                            // Map bank codes to display names
                            $bankNames = [
                                'bca' => 'BCA',
                                'bri' => 'BRI',
                                'bni' => 'BNI',
                                'permata' => 'Permata',
                                'cimb' => 'CIMB Niaga',
                                'echannel' => 'Mandiri',
                                'mandiri' => 'Mandiri',
                                'danamon' => 'Danamon',
                                'bsi' => 'BSI',
                                'seabank' => 'SeaBank',
                            ];
                            $bankCode = strtolower($transaction->bank ?? 'bca');
                            $bankName = $bankNames[$bankCode] ?? strtoupper($bankCode);
                        ?>
                        <p class="text-sm text-gray-600 mt-2">Bank <?php echo e($bankName); ?> Virtual Account</p>
                    </div>

                    <!-- Payment Steps -->
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-sm font-semibold">1</div>
                            <p class="text-gray-700 text-sm">Transfer sesuai nominal yang tertera</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-sm font-semibold">2</div>
                            <p class="text-gray-700 text-sm">Simpan bukti pembayaran</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-primary-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-sm font-semibold">3</div>
                            <p class="text-gray-700 text-sm">Langganan akan aktif otomatis (1x24 jam)</p>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Loading VA -->
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">Memuat informasi pembayaran...</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div id="action-buttons" class="mt-6 flex flex-col sm:flex-row gap-4">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex-1 bg-white border border-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl hover:bg-gray-50 transition-colors text-center">
                    Kembali ke Dashboard
                </a>
                <button onclick="showCancelModal()" class="flex-1 bg-white border border-red-300 text-red-600 font-semibold py-3 px-6 rounded-xl hover:bg-red-50 transition-colors">
                    Batalkan Transaksi
                </button>
                <button onclick="checkPaymentStatus()" id="check-status-btn" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors">
                    <span id="check-btn-text">Cek Status Pembayaran</span>
                    <span id="check-btn-loading" class="hidden">
                        <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Mengecek...
                    </span>
                </button>
            </div>

            <!-- Cancel Confirmation Modal -->
            <div id="cancel-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Batalkan Transaksi?</h3>
                        <p class="text-gray-600">Apakah Anda yakin ingin membatalkan transaksi ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="hideCancelModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-colors">
                            Tidak
                        </button>
                        <button onclick="cancelTransaction()" id="cancel-confirm-btn" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors">
                            <span id="cancel-btn-text">Ya, Batalkan</span>
                            <span id="cancel-btn-loading" class="hidden">
                                <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Membatalkan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Payment Status Alert -->
            <div id="status-alert" class="hidden mt-4 p-4 rounded-xl">
                <p id="status-message"></p>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // Auto-refresh VA number if not available
        // Auto-refresh VA number if not available (Only for VA)
        <?php if(!$transaction->va_number && !in_array($transaction->payment_method, ['qris', 'gopay'])): ?>
        setTimeout(() => {
            console.log('Reloading to check for VA number...');
            location.reload();
        }, 5000);
        <?php endif; ?>

        // Show/Hide Cancel Modal
        function showCancelModal() {
            document.getElementById('cancel-modal').classList.remove('hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancel-modal').classList.add('hidden');
        }

        // Cancel Transaction
        async function cancelTransaction() {
            console.log('=== Cancel Transaction Started ===');
            const btn = document.getElementById('cancel-confirm-btn');
            const btnText = document.getElementById('cancel-btn-text');
            const btnLoading = document.getElementById('cancel-btn-loading');

            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            try {
                const url = '/subscriptions/cancel/<?php echo e($transaction->order_id); ?>';
                console.log('Canceling transaction:', url);
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    showToast('Transaksi berhasil dibatalkan', 'success');
                    setTimeout(() => {
                        window.location.href = '<?php echo e(route("subscriptions.history")); ?>';
                    }, 1500);
                } else {
                    throw new Error(data.error || 'Gagal membatalkan transaksi');
                }
            } catch (error) {
                console.error('Error canceling transaction:', error);
                showToast('Gagal membatalkan transaksi: ' + error.message, 'error');
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        }

        // QRIS Countdown Timer
        <?php if($transaction->payment_method === 'gopay' && $transaction->expiry_time): ?>
        function updateQRISCountdown() {
            const countdownEl = document.getElementById('qris-countdown');
            if (!countdownEl) return; // Exit if element doesn't exist
            
            const expiryTime = new Date('<?php echo e($transaction->expiry_time); ?>').getTime();
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance < 0) {
                countdownEl.innerHTML = '<span class="text-red-600">Expired</span>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const daysEl = document.getElementById('days');
            const hoursEl = document.getElementById('hours');
            const minutesEl = document.getElementById('minutes');
            const secondsEl = document.getElementById('seconds');
            
            if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
            if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
            if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
            if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
        }

        // Update countdown every second
        updateQRISCountdown();
        setInterval(updateQRISCountdown, 1000);
        <?php endif; ?>

        // Copy VA Number
        function copyVA() {
            console.log('=== Copy VA Function Called ===');
            const vaElement = document.getElementById('va-number');
            console.log('VA Element:', vaElement);
            
            if (!vaElement) {
                console.error('VA number element not found!');
                showToast('Error: VA number tidak ditemukan', 'error');
                return;
            }
            
            const vaNumber = vaElement.textContent.trim();
            console.log('VA Number to copy:', vaNumber);
            
            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                console.log('Using modern clipboard API...');
                navigator.clipboard.writeText(vaNumber).then(() => {
                    console.log('✓ Copy successful via clipboard API');
                    showToast('✓ Nomor VA berhasil disalin!', 'success');
                }).catch(err => {
                    console.error('Clipboard API failed:', err);
                    // Fallback to old method
                    copyToClipboardFallback(vaNumber);
                });
            } else {
                console.log('Clipboard API not available, using fallback...');
                // Fallback for older browsers
                copyToClipboardFallback(vaNumber);
            }
        }
        
        // Fallback copy method
        function copyToClipboardFallback(text) {
            console.log('Using fallback copy method...');
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '0';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                const successful = document.execCommand('copy');
                console.log('execCommand copy result:', successful);
                if (successful) {
                    showToast('✓ Nomor VA berhasil disalin!', 'success');
                } else {
                    showToast('✗ Gagal menyalin. Silakan copy manual', 'error');
                }
            } catch (err) {
                console.error('Fallback copy failed:', err);
                showToast('✗ Gagal menyalin. Silakan copy manual', 'error');
            }
            document.body.removeChild(textArea);
        }

        // Copy QR URL (Demo)
        function copyQrUrl() {
            const qrUrlInput = document.getElementById('qr-url-input');
            if (!qrUrlInput) {
                console.error('QR URL input not found');
                return;
            }
            
            const url = qrUrlInput.value;
            console.log('Copying QR URL:', url);

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    showToast('✓ URL QR Code berhasil disalin!', 'success');
                }).catch(err => {
                    console.error('Clipboard API failed:', err);
                    copyToClipboardFallback(url);
                });
            } else {
                copyToClipboardFallback(url);
            }
        }

        // Check payment status
        async function checkPaymentStatus() {
            console.log('=== Check Payment Status Started ===');
            const btn = document.getElementById('check-status-btn');
            const btnText = document.getElementById('check-btn-text');
            const btnLoading = document.getElementById('check-btn-loading');
            const statusAlert = document.getElementById('status-alert');
            const statusMessage = document.getElementById('status-message');

            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');

            try {
                const url = '/subscriptions/check-status/<?php echo e($transaction->order_id); ?>';
                console.log('Fetching URL:', url);
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    console.log('Success! Payment status:', data.status, 'Is success:', data.is_success);
                    if (data.is_success) {
                        // Show success state immediately
                        showSuccessState();
                    } else {
                        // Show waiting state if pending
                        document.getElementById('waiting-card').classList.remove('hidden');
                        const paymentContent = document.getElementById('payment-content');
                        if (paymentContent) paymentContent.classList.remove('hidden');

                        console.log('Payment not successful yet, status:', data.status);
                        // Only show alert if explicit status change or error
                        if (data.status !== 'pending') {
                            statusAlert.className = 'mt-4 p-4 rounded-xl bg-yellow-50 border border-yellow-200';
                            statusMessage.className = 'text-yellow-800';
                            statusMessage.textContent = 'Status: ' + data.status + '. Pembayaran belum diterima.';
                            statusAlert.classList.remove('hidden');
                        }
                    }
                } else {
                    console.error('Request failed:', data.error);
                    throw new Error(data.error || 'Unknown error');
                }
            } catch (error) {
                console.error('Error checking status:', error);
                statusAlert.className = 'mt-4 p-4 rounded-xl bg-red-50 border border-red-200';
                statusMessage.className = 'text-red-800';
                statusMessage.textContent = '✗ Gagal mengecek status: ' + error.message;
                statusAlert.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
            }
        }

        // Show success state
        function showSuccessState() {
            console.log('Showing success state...');
            
            // Hide waiting card and payment details
            document.getElementById('waiting-card').classList.add('hidden');
            const paymentContent = document.getElementById('payment-content');
            if (paymentContent) {
                 paymentContent.classList.add('hidden');
            } else {
                 // Fallback for safety
                 const grid = document.querySelector('.grid');
                 if (grid) grid.classList.add('hidden');
            }
            
            // Hide action buttons
            const actionButtons = document.getElementById('action-buttons');
            if (actionButtons) actionButtons.classList.add('hidden');

            document.getElementById('status-alert').classList.add('hidden');
            
            // Show success card
            document.getElementById('success-card').classList.remove('hidden');
            
            // Start countdown
            let countdown = 4;
            const countdownEl = document.getElementById('countdown');
            
            const countdownInterval = setInterval(() => {
                countdown--;
                countdownEl.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    console.log('Redirecting to subscriptions...');
                    window.location.href = '<?php echo e(route("subscriptions.index")); ?>';
                }
            }, 1000);
        }

        // Auto-check status on page load
        window.addEventListener('DOMContentLoaded', () => {
            console.log('Page loaded, checking payment status...');
            checkPaymentStatus();
        });

        // Auto-check status every 3 seconds for faster feedback
        setInterval(() => {
            checkPaymentStatus();
        }, 3000);
    </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH D:\laragon\www\E-Report\resources\views/subscriptions/waiting-payment.blade.php ENDPATH**/ ?>