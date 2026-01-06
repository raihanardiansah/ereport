<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Check if subscription is expired - show expired view with role-appropriate content
        if (session('subscription_expired')) {
            return view('dashboard.expired', [
                'reason' => session('subscription_expired_reason', 'subscription_expired'),
                'isAdmin' => $user->role === 'admin_sekolah',
            ]);
        }
        
        $stats = $this->getStats($user);
        $recentReports = $this->getRecentReports($user);

        return view('dashboard.index', compact('stats', 'recentReports'));
    }

    /**
     * Get statistics based on user role.
     */
    protected function getStats(User $user): array
    {
        if ($user->isSuperAdmin()) {
            return [
                'total_reports' => Report::count(),
                'new_reports' => Report::where('created_at', '>=', now()->startOfMonth())->count(),
                'pending_reports' => Report::where('status', 'dikirim')->count(),
                'completed_reports' => Report::where('status', 'selesai')->count(),
                'total_users' => School::count(),
            ];
        }

        $schoolId = $user->school_id;

        if ($user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])) {
            return [
                'total_reports' => Report::where('school_id', $schoolId)->count(),
                'new_reports' => Report::where('school_id', $schoolId)
                    ->where('created_at', '>=', now()->startOfWeek())->count(),
                'pending_reports' => Report::where('school_id', $schoolId)
                    ->where('status', 'dikirim')->count(),
                'completed_reports' => Report::where('school_id', $schoolId)
                    ->where('status', 'selesai')->count(),
                'total_users' => User::where('school_id', $schoolId)->count(),
            ];
        }

        // For teachers and students - show their own reports
        return [
            'total_reports' => Report::where('user_id', $user->id)->count(),
            'new_reports' => Report::where('user_id', $user->id)
                ->where('created_at', '>=', now()->startOfWeek())->count(),
            'pending_reports' => Report::where('user_id', $user->id)
                ->whereIn('status', ['dikirim', 'diproses'])->count(),
            'completed_reports' => Report::where('user_id', $user->id)
                ->where('status', 'selesai')->count(),
            'total_users' => 0,
        ];
    }

    /**
     * Get recent reports based on user role.
     */
    protected function getRecentReports(User $user)
    {
        $query = Report::query()->latest()->limit(5);

        if ($user->isSuperAdmin()) {
            return $query->get();
        }

        if ($user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])) {
            return $query->where('school_id', $user->school_id)->get();
        }

        return $query->where('user_id', $user->id)->get();
    }

    /**
     * Role-specific dashboard redirects.
     */
    public function superAdmin()
    {
        return $this->index();
    }

    public function adminSekolah()
    {
        return $this->index();
    }

    public function manajemenSekolah()
    {
        return $this->index();
    }

    public function stafKesiswaan()
    {
        return $this->index();
    }

    public function guru()
    {
        return $this->index();
    }

    public function siswa()
    {
        return $this->index();
    }
}
