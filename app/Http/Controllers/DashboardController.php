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

        // Analytics Data (Only for Admins/Management)
        $reportTrends = [];
        $categoryStats = [];
        $pendingUsersCount = 0;

        if ($user->hasAnyRole(['super_admin', 'admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])) {
            $reportTrends = $this->getReportTrends($user);
            $categoryStats = $this->getCategoryStats($user);
        }

        if ($user->hasAnyRole(['admin_sekolah'])) {
            $pendingUsersCount = User::where('school_id', $user->school_id)
                ->where('is_approved', false)
                ->count();
        }

        return view('dashboard.index', compact('stats', 'recentReports', 'reportTrends', 'categoryStats', 'pendingUsersCount'));
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
     * Get report trends for the last 6 months.
     */
    protected function getReportTrends(User $user): array
    {
        $query = Report::query();

        // Filter based on role
        if ($user->isSuperAdmin()) {
            // SuperAdmin sees all
        } elseif ($user->school_id) {
            // All school users (Admin, Staff, Teacher, Student) see School-Wide Trends
            $query->where('school_id', $user->school_id);
        } else {
            // Fallback
            $query->where('user_id', $user->id);
        }

        $data = $query->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as count")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $labels = [];
        $counts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $labels[] = $date->translatedFormat('F Y'); // e.g. "Januari 2026"
            $counts[] = $data[$monthKey] ?? 0;
        }

        return ['labels' => $labels, 'data' => $counts];
    }

    /**
     * Get report category distribution.
     */
    protected function getCategoryStats(User $user): array
    {
        $query = Report::query();

        // Filter based on role
        if ($user->isSuperAdmin()) {
            // SuperAdmin sees all
        } elseif ($user->school_id) {
            // All school users (Admin, Staff, Teacher, Student) see School-Wide Stats
            $query->where('school_id', $user->school_id);
        } else {
            // Fallback for users without school (shouldn't happen)
            $query->where('user_id', $user->id);
        }

        $allCategories = $query->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();
        
        $total = $allCategories->sum('count');
        if($total == 0) return [];

        // Take top 5
        $stats = $allCategories->take(5)->map(function($item) use ($total) {
            return [
                'label' => ucfirst($item->category),
                'count' => $item->count,
                'percentage' => round(($item->count / $total) * 100, 1),
                'color' => $this->getCategoryColor($item->category)
            ];
        });

        // Group the rest as "Lainnya"
        $otherCount = $allCategories->slice(5)->sum('count');
        if ($otherCount > 0) {
            $stats->push([
                'label' => 'Lainnya',
                'count' => $otherCount,
                'percentage' => round(($otherCount / $total) * 100, 1),
                'color' => 'gray'
            ]);
        }

        return $stats->toArray();
    }

    private function getCategoryColor($category)
    {
        return match(strtolower($category)) {
            'perilaku', 'bullying', 'keamanan' => 'red',
            'akademik', 'prestasi' => 'blue',
            'fasilitas', 'kebersihan', 'teknologi' => 'orange',
            'kehealthan', 'kantin' => 'green',
            default => 'gray'
        };
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
