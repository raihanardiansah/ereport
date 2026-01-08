<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\EmailService;
use Illuminate\Console\Command;

class CheckSubscriptionExpiry extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'subscriptions:check-expiry';

    /**
     * The console command description.
     */
    protected $description = 'Check for expiring subscriptions and send reminder emails';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking subscription expiry...');

        // Get active subscriptions expiring in 7 days, 3 days, or 1 day
        $warningDays = [7, 3, 1, 0];
        $sent = 0;

        foreach ($warningDays as $days) {
            $targetDate = now()->addDays($days)->toDateString();
            
            $subscriptions = Subscription::whereDate('expires_at', $targetDate)
                ->where('status', 'active')
                ->with(['school', 'package'])
                ->get();

            foreach ($subscriptions as $subscription) {
                $this->line("Sending expiry warning to: {$subscription->school->name} ({$days} days remaining)");
                EmailService::notifySubscriptionExpiring($subscription, $days);
                $sent++;
            }
        }

        // Also check for expired subscriptions (just expired today or yesterday)
        // Check "expired_at" < now() AND status="active"
        $expiredSubscriptions = Subscription::where('expires_at', '<', now())
            ->where('status', 'active')
            ->with(['school', 'package'])
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            $this->line("Sending expired notice to: {$subscription->school->name}");
            EmailService::notifySubscriptionExpiring($subscription, 0);
            
            // Update subscription status
            $subscription->update(['status' => 'expired']);

            // Update school status
            $subscription->school->update(['subscription_status' => 'expired']);
            
            // Log audit
            \App\Models\AuditLog::create([
                'user_id' => null, // System
                'action' => 'subscription_expired_auto',
                'model_type' => Subscription::class,
                'model_id' => $subscription->id,
                'description' => "Langganan paket {$subscription->package->name} telah berakhir otomatis.",
                'ip_address' => '127.0.0.1',
                'user_agent' => 'System Scheduler',
            ]);

            $sent++;
        }

        $this->info("Completed! Sent {$sent} expiry notification emails.");

        return Command::SUCCESS;
    }
}
