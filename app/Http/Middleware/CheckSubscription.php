<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Routes that should be accessible for non-admin users when expired.
     */
    protected array $nonAdminExemptRoutes = [
        'dashboard',
        'dashboard.*',
        'logout',
        'profile.*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Super admin bypass - clear any expired flags
        if ($user && $user->isSuperAdmin()) {
            session()->forget(['subscription_expired', 'subscription_expired_reason']);
            return $next($request);
        }

        // Check subscription status
        if ($user && $user->school) {
            $school = $user->school;
            $isExpired = false;
            $reason = '';
            
            // Check if trial is still active
            if ($school->subscription_status === 'trial') {
                if ($school->trial_ends_at && $school->trial_ends_at->isFuture()) {
                    session()->forget(['subscription_expired', 'subscription_expired_reason']);
                    return $next($request);
                }
                $isExpired = true;
                $reason = 'trial_expired';
            } else {
                // Check if has active subscription
                $activeSubscription = $school->subscriptions()
                    ->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->first();
                
                if ($activeSubscription) {
                    session()->forget(['subscription_expired', 'subscription_expired_reason']);
                    return $next($request);
                }
                $isExpired = true;
                $reason = 'subscription_expired';
            }
            
            // Handle expired subscription based on user role
            if ($isExpired) {
                $isAdmin = $user->role === 'admin_sekolah';
                
                // Admin users: Allow full access but set expired flag for dashboard message
                if ($isAdmin) {
                    session(['subscription_expired' => true, 'subscription_expired_reason' => $reason]);
                    return $next($request);
                }
                
                // Non-admin users: Only allow exempt routes
                foreach ($this->nonAdminExemptRoutes as $pattern) {
                    if ($request->routeIs($pattern)) {
                        session(['subscription_expired' => true, 'subscription_expired_reason' => $reason]);
                        return $next($request);
                    }
                }
                
                // Block non-admin access to non-exempt routes
                session(['subscription_expired' => true, 'subscription_expired_reason' => $reason]);
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Paket langganan telah berakhir. Hubungi admin sekolah Anda untuk membeli paket.');
            }
        }

        return $next($request);
    }
}


