<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class WebhookController extends Controller
{
    /**
     * Handle Midtrans webhook notification
     */
    public function handle(Request $request)
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status ?? 'accept';

            // Find transaction
            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

            // Update transaction with notification data
            $transaction->update([
                'payment_type' => $notification->payment_type ?? null,
                'transaction_id' => $notification->transaction_id ?? null,
                'transaction_time' => $notification->transaction_time ?? null,
                'midtrans_response' => json_encode($notification),
            ]);

            // Update VA details if available
            if (isset($notification->va_numbers) && count($notification->va_numbers) > 0) {
                $vaNumber = $notification->va_numbers[0];
                $transaction->update([
                    'va_number' => $vaNumber->va_number,
                    'bank' => $vaNumber->bank,
                ]);
            } elseif (isset($notification->permata_va_number)) {
                $transaction->update([
                    'va_number' => $notification->permata_va_number,
                    'bank' => 'permata',
                ]);
            }

            // Handle transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->activateSubscription($transaction);
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->activateSubscription($transaction);
            } elseif ($transactionStatus == 'pending') {
                $transaction->update(['transaction_status' => 'pending']);
            } elseif ($transactionStatus == 'deny') {
                $transaction->update(['transaction_status' => 'failed']);
            } elseif ($transactionStatus == 'expire') {
                $transaction->update(['transaction_status' => 'expired']);
            } elseif ($transactionStatus == 'cancel') {
                $transaction->update(['transaction_status' => 'cancelled']);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Activate subscription after successful payment
     */
    private function activateSubscription(Transaction $transaction)
    {
        $transaction->update([
            'transaction_status' => 'success',
            'settlement_time' => now(),
        ]);

        // Get package details
        $package = $transaction->package;
        $schoolId = $transaction->school_id;

        // Find current active subscription
        $currentSubscription = Subscription::where('school_id', $schoolId)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->orderByDesc('created_at')
            ->first();

        $startsAt = now();
        $status = 'active';

        // Check if this is a renewal (same package) or upgrade/switch
        if ($currentSubscription) {
            if ($currentSubscription->package_id == $package->id) {
                // Renewal: Queue it after current expires
                $startsAt = $currentSubscription->expires_at;
                $status = 'pending';
            } else {
                // Upgrade/Switch: Expire current one, start new one immediately
                $currentSubscription->update([
                    'status' => 'expired',
                    'expires_at' => now(),
                ]);
            }
        }

        // Create new subscription record (History preserved)
        Subscription::create([
            'school_id' => $schoolId,
            'package_id' => $transaction->package_id,
            'status' => $status,
            'starts_at' => $startsAt,
            'expires_at' => $startsAt->copy()->addDays($package->duration_days),
            'amount_paid' => $transaction->gross_amount,
            'payment_method' => $transaction->payment_method,
            'payment_reference' => $transaction->order_id,
        ]);

        // Update school subscription status only if active immediately
        if ($status === 'active') {
            $transaction->school->update([
                'subscription_status' => 'active',
            ]);
        }

        // TODO: Send email notification to school
        // TODO: Send notification to admin
    }
}
