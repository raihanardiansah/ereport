<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPackage;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Show checkout page for selected package
     */
    public function checkout(SubscriptionPackage $package)
    {
        $school = auth()->user()->school;
        
        // Check if package is active
        if (!$package->is_active) {
            return redirect()->route('subscriptions.packages')
                ->with('error', 'Paket ini tidak tersedia.');
        }

        // Get current active subscription
        $currentSubscription = Subscription::where('school_id', $school->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('package')
            ->orderByDesc('created_at')
            ->first();

        // Check trial eligibility if this is a trial package
        if ($package->is_trial || $package->price == 0) {
            if (!$school->isEligibleForTrial()) {
                $reason = $school->hasUsedTrial() 
                    ? 'Anda sudah pernah menggunakan paket trial sebelumnya.'
                    : 'Paket trial hanya tersedia untuk sekolah baru dalam 7 hari pertama registrasi.';
                
                return redirect()->route('subscriptions.packages')
                    ->with('error', $reason);
            }
        }

        // Check downgrade prevention
        if ($currentSubscription && $currentSubscription->isDowngrade($package)) {
            if (!$currentSubscription->canDowngradeTo($package)) {
                $reason = $currentSubscription->getDowngradeBlockReason();
                return redirect()->route('subscriptions.packages')
                    ->with('error', $reason);
            }
        }
        
        // Initialize promo variables (for compatibility with old checkout view)
        $promoCode = null;
        $appliedPromo = null;
        $promoDiscount = 0;
        $finalPrice = $package->price;
        
        return view('subscriptions.checkout', compact(
            'package', 
            'school', 
            'promoCode', 
            'appliedPromo', 
            'promoDiscount', 
            'finalPrice',
            'currentSubscription'
        ));
    }

    /**
     * Create transaction and get Snap token
     */
    public function createTransaction(Request $request, SubscriptionPackage $package)
    {
        $request->validate([
            'payment_method' => 'required|in:qris,virtual_account,ewallet',
            'bank_va' => 'nullable|in:bca,bri,bni,permata,cimb,mandiri',
        ]);

        $school = auth()->user()->school;
        
        // Get current active subscription
        $currentSubscription = Subscription::where('school_id', $school->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('package')
            ->orderByDesc('created_at')
            ->first();

        // Validate trial eligibility
        if ($package->is_trial || $package->price == 0) {
            if (!$school->isEligibleForTrial()) {
                $reason = $school->hasUsedTrial() 
                    ? 'Anda sudah pernah menggunakan paket trial sebelumnya.'
                    : 'Paket trial hanya tersedia untuk sekolah baru dalam 7 hari pertama registrasi.';
                
                return response()->json([
                    'success' => false,
                    'error' => $reason
                ], 403);
            }
        }

        // Validate downgrade prevention
        if ($currentSubscription && $currentSubscription->isDowngrade($package)) {
            if (!$currentSubscription->canDowngradeTo($package)) {
                $reason = $currentSubscription->getDowngradeBlockReason();
                return response()->json([
                    'success' => false,
                    'error' => $reason
                ], 403);
            }
        }
        
        // Generate unique order ID
        $orderId = 'INV' . date('YmdHis') . $school->id;
        
        // Check if this is a trial package (price = 0)
        if ($package->price == 0 || $package->is_trial) {
            // For trial packages, skip Midtrans and activate subscription directly
            
            // Create transaction record with settlement status
            $transaction = Transaction::create([
                'school_id' => $school->id,
                'package_id' => $package->id,
                'order_id' => $orderId,
                'gross_amount' => 0,
                'transaction_status' => 'settlement',
                'payment_method' => 'free_trial',
                'bank' => null,
                'va_number' => null,
            ]);
            
            // Create subscription immediately
            $subscription = Subscription::create([
                'school_id' => $school->id,
                'package_id' => $package->id,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addDays($package->duration_days),
                'amount_paid' => 0,
                'payment_method' => 'free_trial',
                'payment_reference' => $orderId,
            ]);
            
            // Update transaction with subscription_id
            $transaction->update(['subscription_id' => $subscription->id]);
            
            // Mark trial as used
            $school->markTrialAsUsed();
            
            // Update school subscription status
            $school->update([
                'subscription_status' => 'active',
                'trial_ends_at' => null,
            ]);
            
            // Log the action
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'trial_subscription_activated',
                'model_type' => Subscription::class,
                'model_id' => $subscription->id,
                'description' => "Mengaktifkan paket trial {$package->name} hingga " . $subscription->expires_at->format('d M Y'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            return response()->json([
                'success' => true,
                'is_trial' => true,
                'order_id' => $orderId,
                'redirect_url' => route('dashboard'),
            ]);
        }
        
        // Create transaction record for paid packages
        $transaction = Transaction::create([
            'school_id' => $school->id,
            'package_id' => $package->id,
            'order_id' => $orderId,
            'gross_amount' => $package->price,
            'transaction_status' => 'pending',
        ]);

        // Prepare transaction details for Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $package->price,
            ],
            'customer_details' => [
                'first_name' => $school->name,
                'email' => $school->email,
                'phone' => $school->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $package->id,
                    'price' => (int) $package->price,
                    'quantity' => 1,
                    'name' => $package->name . ' - ' . $package->duration_months . ' bulan',
                ]
            ],
        ];

        // Get selected bank VA (default to BCA if not specified)
        $selectedBank = $request->input('bank_va', 'bca');
        
        // Map user-friendly bank codes to Midtrans bank codes
        // Only banks officially supported by Midtrans
        $bankMapping = [
            'bca' => 'bca',
            'bri' => 'bri',
            'bni' => 'bni',
            'permata' => 'permata',
            'cimb' => 'cimb',
            'mandiri' => 'echannel', // Mandiri uses echannel (bill payment)
            // Note: Danamon, BSI, SeaBank not directly supported by Midtrans
            // They will fallback to Permata
            'danamon' => 'permata',
            'bsi' => 'permata',
            'seabank' => 'permata',
        ];
        
        $midtransBank = $bankMapping[$selectedBank] ?? 'bca';

        // Set payment type based on selected method
        if ($request->payment_method === 'qris') {
            // QRIS payment using GoPay infrastructure
            $params['payment_type'] = 'gopay';
            $params['gopay'] = [
                'enable_callback' => true,
                'callback_url' => route('subscriptions.waiting', $orderId)
            ];
        } else {
            // Bank transfer (Virtual Account)
            $params['payment_type'] = 'bank_transfer';
            $params['bank_transfer'] = [
                'bank' => $midtransBank,
            ];
        }


        try {
            // Create transaction using Charge API (not Snap)
            $charge = \Midtrans\CoreApi::charge($params);
            
            // Debug: Log Midtrans response for QRIS
            if ($request->payment_method === 'qris') {
                \Log::info('=== QRIS Midtrans Response ===');
                \Log::info('Full charge object: ' . json_encode($charge));
                \Log::info('Payment type: ' . ($charge->payment_type ?? 'N/A'));
                \Log::info('Actions: ' . json_encode($charge->actions ?? 'No actions'));
            }
            
            // Extract VA number from response
            $vaNumber = null;
            $bank = null;
            $qrCodeUrl = null;
            $qrString = null;
            
            // Handle QRIS/GoPay response
            if ($request->payment_method === 'qris' && isset($charge->actions)) {
                \Log::info('Processing QRIS actions...');
                foreach ($charge->actions as $action) {
                    \Log::info('Action name: ' . $action->name);
                    \Log::info('Action URL: ' . ($action->url ?? 'N/A'));
                    
                    if ($action->name === 'generate-qr-code') {
                        $qrCodeUrl = $action->url;
                        \Log::info('✓ QR Code URL found: ' . $qrCodeUrl);
                    } else if ($action->name === 'deeplink-redirect' && isset($action->url)) {
                        // Some Midtrans responses include QR string in deeplink
                        $qrString = $action->url;
                        \Log::info('✓ QR String found: ' . $qrString);
                    }
                }
            }
            
            // Handle VA response
            if (isset($charge->va_numbers) && count($charge->va_numbers) > 0) {
                $vaNumber = $charge->va_numbers[0]->va_number;
                $bank = $charge->va_numbers[0]->bank;
            } elseif (isset($charge->permata_va_number)) {
                $vaNumber = $charge->permata_va_number;
                $bank = 'permata';
            }
            
            // Update transaction with payment details
            $transaction->update([
                'transaction_id' => $charge->transaction_id ?? null,
                'transaction_status' => $charge->transaction_status ?? 'pending',
                'va_number' => $vaNumber,
                'bank' => $bank,
                'qr_code_url' => $qrCodeUrl,
                'qr_string' => $qrString,
                'payment_method' => $charge->payment_type ?? $request->payment_method,
                'expiry_time' => isset($charge->expiry_time) ? date('Y-m-d H:i:s', strtotime($charge->expiry_time)) : null,
            ]);
            
            return response()->json([
                'success' => true,
                'order_id' => $orderId,
                'va_number' => $vaNumber,
                'bank' => $bank,
                'qr_code_url' => $qrCodeUrl,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Midtrans create transaction error: ' . $e->getMessage());
            \Log::error('Midtrans error trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }

    /**
     * Show waiting payment page with VA details
     */
    public function waitingPayment($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)
            ->with(['package', 'school'])
            ->firstOrFail();

        // Check if transaction belongs to current user's school
        if ($transaction->school_id !== auth()->user()->school_id) {
            abort(403, 'Unauthorized access');
        }

        // If transaction already success, redirect to dashboard
        if ($transaction->isSuccess()) {
            return redirect()->route('dashboard')->with('success', 'Pembayaran sudah berhasil!');
        }

        // Get transaction status from Midtrans
        try {
            $status = MidtransTransaction::status($orderId);
            
            // Update transaction with VA details if available
            if (isset($status->va_numbers) && count($status->va_numbers) > 0) {
                $vaNumber = $status->va_numbers[0];
                $transaction->update([
                    'va_number' => $vaNumber->va_number,
                    'bank' => $vaNumber->bank,
                    'payment_method' => $status->payment_type,
                    'expiry_time' => $status->expiry_time ?? null,
                ]);
            } elseif (isset($status->permata_va_number)) {
                $transaction->update([
                    'va_number' => $status->permata_va_number,
                    'bank' => 'permata',
                    'payment_method' => $status->payment_type,
                    'expiry_time' => $status->expiry_time ?? null,
                ]);
            }

            // Update transaction status
            $transaction->update([
                'transaction_status' => $status->transaction_status,
                'transaction_id' => $status->transaction_id ?? null,
            ]);

        } catch (\Exception $e) {
            // If error, continue with existing data
            \Log::error('Midtrans status check error: ' . $e->getMessage());
        }

        // Re-check if transaction success after update
        if ($transaction->refresh()->isSuccess()) {
             return redirect()->route('subscriptions.index')->with('success', 'Pembayaran berhasil!');
        }

        return view('subscriptions.waiting-payment', compact('transaction'));
    }

    /**
     * Check payment status (AJAX)
     */
    public function checkStatus($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

        // Check if transaction belongs to current user's school
        if ($transaction->school_id !== auth()->user()->school_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $status = MidtransTransaction::status($orderId);
            
            // Update transaction status
            $transaction->update([
                'transaction_status' => $status->transaction_status,
            ]);

            // Check if payment is successful
            $isSuccess = in_array($status->transaction_status, ['capture', 'settlement']);
            
            // If payment successful and subscription not created yet, create it
            if ($isSuccess && !$transaction->subscription_id) {
                $school = $transaction->school;
                $package = $transaction->package;
                
                // Check if there's an active subscription
                $currentSubscription = Subscription::where('school_id', $school->id)
                    ->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->orderByDesc('created_at')
                    ->first();
                
                // Determine start and end dates
                $startsAt = now();
                $status = 'active';
                
                // If there's an active subscription
                if ($currentSubscription) {
                    // Check if Renewal (Same Package) or Upgrade (Different Package)
                    if ($currentSubscription->package_id == $package->id) {
                         // Renewal: Queue it after current expires
                        $startsAt = $currentSubscription->expires_at;
                        $status = 'pending'; 
                    } else {
                        // Upgrade: Immediate Activation
                        // Expire the current subscription
                         $currentSubscription->update([
                            'status' => 'expired',
                            'expires_at' => now(),
                        ]);
                        // New subscription uses default startsAt (now) and status (active)
                    }
                }
                
                $expiresAt = $startsAt->copy()->addDays($package->duration_days);
                
                // Create subscription
                $subscription = \App\Models\Subscription::create([
                    'school_id' => $school->id,
                    'package_id' => $package->id,
                    'status' => $status,
                    'starts_at' => $startsAt,
                    'expires_at' => $expiresAt,
                    'amount_paid' => $transaction->gross_amount,
                    'payment_method' => $transaction->payment_method,
                    'payment_reference' => $transaction->order_id,
                ]);
                
                // Update transaction with subscription_id
                $transaction->update(['subscription_id' => $subscription->id]);
                
                // Only update school status if subscription is immediately active
                if ($status === 'active') {
                    $school->update([
                        'subscription_status' => 'active',
                        'trial_ends_at' => null,
                    ]);
                }
                
                // Log the action
                $actionDescription = $status === 'active' 
                    ? "Berlangganan paket {$package->name} hingga " . $subscription->expires_at->format('d M Y')
                    : "Berlangganan paket {$package->name} akan aktif mulai " . $subscription->starts_at->format('d M Y') . " hingga " . $subscription->expires_at->format('d M Y');
                
                \App\Models\AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => $status === 'active' ? 'subscription_activated' : 'subscription_scheduled',
                    'model_type' => \App\Models\Subscription::class,
                    'model_id' => $subscription->id,
                    'description' => $actionDescription,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            return response()->json([
                'success' => true,
                'status' => $status->transaction_status,
                'is_success' => $isSuccess,
            ]);
        } catch (\Exception $e) {
            \Log::error('Check status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel pending transaction
     */
    public function cancelTransaction($orderId)
    {
        $school = auth()->user()->school;
        
        // Find the transaction
        $transaction = Transaction::where('order_id', $orderId)
            ->where('school_id', $school->id)
            ->first();
        
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'error' => 'Transaksi tidak ditemukan'
            ], 404);
        }
        
        // Only allow canceling pending or expired transactions
        if (!in_array($transaction->transaction_status, ['pending', 'expire', 'expired'])) {
            return response()->json([
                'success' => false,
                'error' => 'Transaksi yang sudah berhasil tidak dapat dibatalkan'
            ], 400);
        }
        
        // Delete the transaction
        $transaction->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibatalkan'
        ]);
    }
}
