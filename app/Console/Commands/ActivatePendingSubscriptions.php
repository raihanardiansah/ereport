<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\AuditLog;
use Illuminate\Console\Command;

class ActivatePendingSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:activate-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate pending subscriptions whose start date has arrived';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for pending subscriptions to activate...');
        
        // Find pending subscriptions whose start date has arrived
        $pendingSubscriptions = Subscription::where('status', 'pending')
            ->where('starts_at', '<=', now())
            ->with(['school', 'package'])
            ->get();
        
        if ($pendingSubscriptions->isEmpty()) {
            $this->info('No pending subscriptions to activate.');
            return 0;
        }
        
        $activated = 0;
        
        foreach ($pendingSubscriptions as $subscription) {
            // Activate the subscription
            $subscription->update(['status' => 'active']);
            
            // Update school subscription status
            $subscription->school->update([
                'subscription_status' => 'active',
                'trial_ends_at' => null,
            ]);
            
            // Log the activation
            AuditLog::create([
                'user_id' => null, // System action
                'action' => 'subscription_auto_activated',
                'model_type' => Subscription::class,
                'model_id' => $subscription->id,
                'description' => "Paket {$subscription->package->name} untuk {$subscription->school->name} diaktifkan otomatis hingga " . $subscription->expires_at->format('d M Y'),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'System Command',
            ]);
            
            $activated++;
            $this->info("✓ Activated subscription #{$subscription->id} for {$subscription->school->name}");
        }
        
        $this->info("Successfully activated {$activated} subscription(s).");
        return 0;
    }
}
