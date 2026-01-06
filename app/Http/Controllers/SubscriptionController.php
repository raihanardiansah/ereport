<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Promotion;
use App\Models\Subscription;
use App\Models\SubscriptionPackage;
use App\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display subscription management page with current status and available packages.
     */
    public function index()
    {
        $school = auth()->user()->school;
        
        // Get active packages ordered by sort_order then price
        // Hide trial packages from users who are not eligible
        $packages = SubscriptionPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get()
            ->filter(function($package) use ($school) {
                // Hide trial packages from users who are not eligible
                $isTrial = $package->is_trial || $package->price == 0;
                if ($isTrial && !$school->isEligibleForTrial()) {
                    return false; // Filter out this package
                }
                return true;
            })
            ->values(); // Reset array keys after filter
        
        // Get current active subscription - get the LATEST one to show upgrades correctly
        $currentSubscription = Subscription::where('school_id', $school->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with(['package' => function($q) {
                // Always get fresh package data, no cache
                $q->select('*');
            }])
            ->orderByDesc('created_at') // Get the most recent subscription
            ->first();

        // Calculate subscription metrics
        $subscriptionInfo = null;
        if ($currentSubscription) {
            $daysRemaining = (int) now()->diffInDays($currentSubscription->expires_at, false);
            $totalDays = (int) $currentSubscription->starts_at->diffInDays($currentSubscription->expires_at);
            $daysUsed = (int) $currentSubscription->starts_at->diffInDays(now());
            $progressPercent = $totalDays > 0 ? min(100, ($daysUsed / $totalDays) * 100) : 0;
            
            $subscriptionInfo = [
                'days_remaining' => max(0, $daysRemaining),
                'days_total' => $totalDays,
                'progress_percent' => round($progressPercent, 1),
                'is_expiring_soon' => $daysRemaining <= 7 && $daysRemaining > 0,
                'is_expired' => $daysRemaining <= 0,
                'is_h1' => $currentSubscription->isExpiringWithinOneDay(),
            ];
        }

        // Trial eligibility info
        $trialEligibility = [
            'is_eligible' => $school->isEligibleForTrial(),
            'has_used' => $school->hasUsedTrial(),
            'days_remaining' => $school->getTrialEligibilityDaysRemaining(),
        ];

        // Subscription history
        $subscriptionHistory = Subscription::where('school_id', $school->id)
            ->with('package')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Payment history - using Transaction model to show recent transactions
        $paymentHistory = \App\Models\Transaction::where('school_id', $school->id)
            ->with('package')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('subscriptions.index', compact(
            'school',
            'packages',
            'currentSubscription',
            'subscriptionInfo',
            'trialEligibility',
            'subscriptionHistory',
            'paymentHistory'
        ));
    }

    /**
     * Show package selection with comparison.
     */
    public function selectPackage()
    {
        $school = auth()->user()->school;
        
        // Get current active subscription
        $currentSubscription = Subscription::where('school_id', $school->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with('package')
            ->orderByDesc('created_at')
            ->first();
        
        // Get all active packages
        $packages = SubscriptionPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get()
            ->filter(function($package) use ($school) {
                // Hide trial packages from users who are not eligible
                $isTrial = $package->is_trial || $package->price == 0;
                if ($isTrial && !$school->isEligibleForTrial()) {
                    return false; // Filter out this package
                }
                return true;
            })
            ->map(function($package) use ($school, $currentSubscription) {
                // Check downgrade status
                $isDowngrade = $currentSubscription && $currentSubscription->isDowngrade($package);
                $canDowngrade = $currentSubscription ? $currentSubscription->canDowngradeTo($package) : true;
                
                // Determine if package is available
                $isAvailable = true;
                $unavailableReason = null;
                
                if ($isDowngrade && !$canDowngrade) {
                    $isAvailable = false;
                    $unavailableReason = $currentSubscription->getDowngradeBlockReason();
                }
                
                // Add metadata to package
                $package->is_available = $isAvailable;
                $package->unavailable_reason = $unavailableReason;
                $package->is_downgrade = $isDowngrade;
                $package->is_upgrade = $currentSubscription && $package->price > $currentSubscription->package->price;
                $package->is_current = $currentSubscription && $package->id == $currentSubscription->package_id;
                
                return $package;
            })
            ->values(); // Reset array keys after filter
        
        // Trial eligibility info
        $trialEligibility = [
            'is_eligible' => $school->isEligibleForTrial(),
            'has_used' => $school->hasUsedTrial(),
            'days_remaining' => $school->getTrialEligibilityDaysRemaining(),
        ];

        return view('subscriptions.packages', compact('packages', 'currentSubscription', 'trialEligibility'));
    }

    /**
     * Show checkout page with promo code support.
     */
    public function checkout(Request $request, SubscriptionPackage $package)
    {
        if (!$package->is_active) {
            return redirect()->route('subscriptions.packages')
                ->with('error', 'Paket ini tidak tersedia.');
        }

        $school = auth()->user()->school;
        $promoDiscount = 0;
        $promoCode = null;
        $appliedPromo = null;

        // Check if promo code was applied
        if ($request->has('promo_code')) {
            $promo = Promotion::where('code', strtoupper($request->promo_code))->first();
            
            if ($promo && $promo->isValid() && $promo->canBeUsedBy($school->id) && $promo->appliesToPackage($package->id)) {
                $promoDiscount = $promo->calculateDiscount($package->price);
                $promoCode = $promo->code;
                $appliedPromo = $promo;
            }
        }

        $finalPrice = $package->price - $promoDiscount;

        return view('subscriptions.checkout', compact(
            'package',
            'school',
            'promoDiscount',
            'promoCode',
            'appliedPromo',
            'finalPrice'
        ));
    }

    /**
     * Validate promo code via AJAX.
     */
    public function validatePromo(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'package_id' => 'required|exists:subscription_packages,id',
        ]);

        $school = auth()->user()->school;
        $promo = Promotion::where('code', strtoupper($validated['code']))->first();

        if (!$promo) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak ditemukan.',
            ]);
        }

        if (!$promo->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo sudah tidak berlaku atau kehabisan kuota.',
            ]);
        }

        if (!$promo->canBeUsedBy($school->id)) {
            return response()->json([
                'valid' => false,
                'message' => 'Anda sudah menggunakan kode promo ini sebelumnya.',
            ]);
        }

        if (!$promo->appliesToPackage($validated['package_id'])) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak berlaku untuk paket ini.',
            ]);
        }

        $package = SubscriptionPackage::find($validated['package_id']);
        $discount = $promo->calculateDiscount($package->price);
        $finalPrice = $package->price - $discount;

        return response()->json([
            'valid' => true,
            'promo' => [
                'code' => $promo->code,
                'name' => $promo->name,
                'type' => $promo->type,
                'formatted_value' => $promo->formatted_value,
            ],
            'discount' => $discount,
            'formatted_discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'final_price' => $finalPrice,
            'formatted_final_price' => 'Rp ' . number_format($finalPrice, 0, ',', '.'),
            'message' => "Promo '{$promo->name}' berhasil diterapkan!",
        ]);
    }

    /**
     * Process subscription payment - Redirect to Midtrans integration.
     */
    public function processPayment(Request $request, SubscriptionPackage $package)
    {
        // Redirect to Midtrans payment flow
        return redirect()->route('payment.checkout', ['package' => $package]);
    }

    /**
     * Show payment instructions.
     */
    public function paymentInstructions(Payment $payment, SubscriptionPackage $package)
    {
        if ($payment->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        return view('subscriptions.payment-instructions', compact('payment', 'package'));
    }

    /**
     * Simulate payment completion (for development).
     */
    public function simulatePayment(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran tidak valid.');
        }

        if ($payment->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $school = auth()->user()->school;
        $package = SubscriptionPackage::find($request->package_id);

        if (!$package) {
            return back()->with('error', 'Paket tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // Create subscription
            $subscription = Subscription::create([
                'school_id' => $school->id,
                'package_id' => $package->id,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addDays($package->duration_days),
                'amount_paid' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_reference' => $payment->invoice_number,
            ]);

            // Update payment
            $payment->update([
                'subscription_id' => $subscription->id,
                'status' => 'paid',
                'paid_at' => now(),
                'gateway_transaction_id' => 'SIM-' . strtoupper(uniqid()),
            ]);

            // Record promo usage if applicable
            $promoData = session('pending_promo_' . $payment->id);
            if ($promoData) {
                $promo = Promotion::find($promoData['promo_id']);
                if ($promo) {
                    $promo->recordUsage($school->id, $subscription->id, $promoData['discount']);
                }
                session()->forget('pending_promo_' . $payment->id);
            }

            // Update school subscription status
            $school->update([
                'subscription_status' => 'active',
                'trial_ends_at' => null,
            ]);

            // Log the action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'subscription_activated',
                'model_type' => Subscription::class,
                'model_id' => $subscription->id,
                'description' => "Berlangganan paket {$package->name} hingga " . $subscription->expires_at->format('d M Y'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('subscriptions.index')
                ->with('success', 'Pembayaran berhasil! Langganan Anda telah aktif hingga ' . $subscription->expires_at->format('d M Y') . '.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengaktifkan langganan: ' . $e->getMessage());
        }
    }

    /**
     * Show payment history.
     */
    public function paymentHistory()
    {
        $transactions = \App\Models\Transaction::where('school_id', auth()->user()->school_id)
            ->with('package')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('subscriptions.history', compact('transactions'));
    }

    /**
     * View invoice detail.
     */
    public function viewInvoice($orderId)
    {
        $transaction = \App\Models\Transaction::where('order_id', $orderId)
            ->where('school_id', auth()->user()->school_id)
            ->with(['package', 'school'])
            ->firstOrFail();

        return view('subscriptions.invoice-detail', compact('transaction'));
    }

    /**
     * Download invoice as PDF.
     */
    public function downloadInvoicePdf($orderId)
    {
        $transaction = \App\Models\Transaction::where('order_id', $orderId)
            ->where('school_id', auth()->user()->school_id)
            ->with(['package', 'school'])
            ->firstOrFail();

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('subscriptions.invoice-pdf', compact('transaction'));
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'portrait');
        
        // Download the PDF
        return $pdf->download('invoice-' . $transaction->order_id . '.pdf');
    }

    /**
     * Download invoice (legacy - for Payment model).
     */
    public function downloadInvoice(Payment $payment)
    {
        if ($payment->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        // In production, generate PDF invoice
        return response()->json([
            'invoice_number' => $payment->invoice_number,
            'amount' => $payment->formatted_amount,
            'status' => $payment->status,
            'date' => $payment->created_at->format('d/m/Y'),
        ]);
    }

    /**
     * Cancel/unsubscribe from current active subscription
     */
    public function unsubscribe(Request $request)
    {
        // Validate reCAPTCHA
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$recaptchaToken) {
            return response()->json([
                'success' => false,
                'error' => 'Silakan centang reCAPTCHA.'
            ], 422);
        }

        try {
            $recaptcha = new \ReCaptcha\ReCaptcha(config('services.recaptcha.secret_key'));
            $resp = $recaptcha->verify($recaptchaToken, $request->ip());
            
            if (!$resp->isSuccess()) {
                // In local development, log the error but don't block
                if (config('app.env') === 'local') {
                    \Log::warning('reCAPTCHA verification failed in local environment (unsubscribe)', [
                        'errors' => $resp->getErrorCodes()
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.'
                    ], 422);
                }
            }
        } catch (\Exception $e) {
            \Log::error('reCAPTCHA verification error (unsubscribe): ' . $e->getMessage());
            
            if (config('app.env') !== 'local') {
                return response()->json([
                    'success' => false,
                    'error' => 'Tidak dapat memverifikasi reCAPTCHA. Silakan coba lagi.'
                ], 500);
            }
        }

        // Validate password
        $password = $request->input('password');
        if (!$password) {
            return response()->json([
                'success' => false,
                'error' => 'Password wajib diisi untuk konfirmasi.'
            ], 422);
        }

        // Verify password
        if (!\Hash::check($password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'error' => 'Password yang Anda masukkan salah.'
            ], 422);
        }

        try {
            $school = auth()->user()->school;
            
            // Find active subscription with package relationship
            $subscription = Subscription::with('package')
                ->where('school_id', $school->id)
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->first();
            
            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'error' => 'Tidak ada langganan aktif yang ditemukan'
                ], 404);
            }
            
            // Log before update
            \Log::info('Unsubscribe - Before Update', [
                'subscription_id' => $subscription->id,
                'school_id' => $school->id,
                'old_status' => $subscription->status,
                'old_expires_at' => $subscription->expires_at->toDateTimeString(),
                'now' => now()->toDateTimeString()
            ]);
            
            // Cancel the subscription immediately by setting expires_at to now
            $subscription->update([
                'status' => 'cancelled',
                'expires_at' => now() // Set to now so it's immediately expired
            ]);
            
            // Refresh to get updated values
            $subscription->refresh();
            
            // Log after update
            \Log::info('Unsubscribe - After Update', [
                'subscription_id' => $subscription->id,
                'new_status' => $subscription->status,
                'new_expires_at' => $subscription->expires_at->toDateTimeString(),
                'is_future' => $subscription->expires_at->isFuture() ? 'YES' : 'NO'
            ]);
            
            // Update school subscription status
            $school->update([
                'subscription_status' => 'expired'
            ]);
            
            // Create audit log
            $packageName = $subscription->package ? $subscription->package->name : 'Unknown Package';
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'subscription_cancelled',
                'model_type' => Subscription::class,
                'model_id' => $subscription->id,
                'description' => "Membatalkan langganan paket {$packageName}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Clear any subscription-related session data
            session()->forget(['subscription_expired', 'subscription_expired_reason']);
            
            return response()->json([
                'success' => true,
                'message' => 'Langganan berhasil diberhentikan'
            ]);
        } catch (\Exception $e) {
            \Log::error('Unsubscribe error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memberhentikan langganan: ' . $e->getMessage()
            ], 500);
        }
    }
}

