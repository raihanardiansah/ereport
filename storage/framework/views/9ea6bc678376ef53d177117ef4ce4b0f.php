<?php if (isset($component)) { $__componentOriginalfa710ee477a7171fb238cadd060c5959 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa710ee477a7171fb238cadd060c5959 = $attributes; } ?>
<?php $component = App\View\Components\Layouts\App::resolve(['title' => 'Invoice Detail'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layouts\App::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="<?php echo e(route('subscriptions.history')); ?>" class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Riwayat
            </a>
        </div>

        <!-- Invoice Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" id="invoice-content">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-8 py-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">INVOICE</h1>
                        <p class="text-primary-100"><?php echo e($transaction->order_id); ?></p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-primary-100 mb-1">Tanggal</div>
                        <div class="font-semibold"><?php echo e($transaction->created_at->format('d F Y')); ?></div>
                    </div>
                </div>
            </div>

            <!-- Invoice Body -->
            <div class="p-8">
                <!-- Billing Information -->
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- From -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Diterbitkan Atas Nama</h3>
                        <div class="text-gray-900">
                            <p class="font-bold text-lg mb-1">E-Report System</p>
                            <p class="text-gray-600 text-sm">Platform Manajemen Laporan Sekolah</p>
                            <p class="text-gray-600 text-sm mt-2">support@e-report.com</p>
                        </div>
                    </div>

                    <!-- To -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Untuk</h3>
                        <div class="text-gray-900">
                            <p class="font-bold text-lg mb-1"><?php echo e($transaction->school->name); ?></p>
                            <p class="text-gray-600 text-sm"><?php echo e($transaction->school->address ?? '-'); ?></p>
                            <p class="text-gray-600 text-sm mt-2"><?php echo e($transaction->school->email); ?></p>
                            <?php if($transaction->school->phone): ?>
                                <p class="text-gray-600 text-sm"><?php echo e($transaction->school->phone); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Detail Transaksi</h3>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 text-sm font-semibold text-gray-700">Info Produk</th>
                                    <th class="text-center py-3 text-sm font-semibold text-gray-700">Jumlah</th>
                                    <th class="text-right py-3 text-sm font-semibold text-gray-700">Harga Satuan</th>
                                    <th class="text-right py-3 text-sm font-semibold text-gray-700">Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-100">
                                    <td class="py-4">
                                        <p class="font-semibold text-gray-900"><?php echo e($transaction->package->name); ?></p>
                                        <p class="text-sm text-gray-500">Durasi: <?php echo e($transaction->package->duration_months); ?> bulan</p>
                                    </td>
                                    <td class="text-center py-4 text-gray-900">1</td>
                                    <td class="text-right py-4 text-gray-900">Rp <?php echo e(number_format($transaction->gross_amount, 0, ',', '.')); ?></td>
                                    <td class="text-right py-4 font-semibold text-gray-900">Rp <?php echo e(number_format($transaction->gross_amount, 0, ',', '.')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="flex justify-end mb-8">
                    <div class="w-full md:w-1/2">
                        <div class="bg-gray-50 rounded-xl p-6 space-y-3">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal</span>
                                <span class="font-medium">Rp <?php echo e(number_format($transaction->gross_amount, 0, ',', '.')); ?></span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total Tagihan</span>
                                    <span class="text-primary-600">Rp <?php echo e(number_format($transaction->gross_amount, 0, ',', '.')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Metode Pembayaran</h4>
                            <p class="text-gray-900 font-medium">
                                <?php echo e($transaction->payment_method == 'gopay' ? 'QRIS Gopay' : ($transaction->payment_method ? ucwords(str_replace('_', ' ', $transaction->payment_method)) : 'Virtual Account')); ?>

                            </p>
                            <?php if($transaction->va_number): ?>
                                <p class="text-sm text-gray-600 mt-1">
                                    Bank: <?php echo e(strtoupper($transaction->bank ?? 'BCA')); ?>

                                </p>
                                <p class="text-sm text-gray-600">VA: <?php echo e($transaction->va_number); ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Status Pembayaran</h4>
                            <?php
                                $statusConfig = match($transaction->transaction_status) {
                                    'success', 'settlement' => ['text' => 'Lunas', 'class' => 'bg-green-100 text-green-700'],
                                    'pending' => ['text' => 'Menunggu Pembayaran', 'class' => 'bg-yellow-100 text-yellow-700'],
                                    'expire', 'expired' => ['text' => 'Kadaluarsa', 'class' => 'bg-red-100 text-red-700'],
                                    'cancel', 'deny' => ['text' => 'Dibatalkan', 'class' => 'bg-red-100 text-red-700'],
                                    default => ['text' => ucfirst($transaction->transaction_status), 'class' => 'bg-gray-100 text-gray-700'],
                                };
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($statusConfig['class']); ?>">
                                <?php echo e($statusConfig['text']); ?>

                            </span>
                            <?php if($transaction->settlement_time): ?>
                                <p class="text-sm text-gray-600 mt-2">
                                    Dibayar: <?php echo e($transaction->settlement_time->format('d M Y, H:i')); ?> WIB
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Footer Note -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 text-center">
                        Invoice ini sah dan diproses secara otomatis.<br>
                        Silakan hubungi <span class="text-primary-600 font-medium">support@e-report.com</span> apabila kamu membutuhkan bantuan.
                    </p>
                    <?php if(in_array($transaction->transaction_status, ['success', 'settlement'])): ?>
                        <p class="text-xs text-gray-400 text-center mt-2">
                            Terakhir diperbarui: <?php echo e($transaction->updated_at->format('d F Y H:i')); ?> WIB
                        </p>
                    <?php endif; ?>
                    <div class="text-center mt-4 mb-2">
                        <img src="https://i.ibb.co.com/HpBRhxmR/Logo.png" alt="E-Report Logo" class="inline-block" style="height: 24px;">
                    </div>
                    <p class="text-xs text-gray-400 text-center">
                        © PT. KREASI DIGITAL CREATIVE MINDS INDONESIA all rights reserved
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-center">
            <a href="<?php echo e(route('subscriptions.invoice.download', $transaction->order_id)); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #invoice-content, #invoice-content * {
                visibility: visible;
            }
            #invoice-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
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
<?php /**PATH D:\laragon\www\E-Report\resources\views/subscriptions/invoice-detail.blade.php ENDPATH**/ ?>