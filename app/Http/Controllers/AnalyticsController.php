<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $schoolId = $user->school_id;

        // Get basic stats
        $stats = $this->getBasicStats($schoolId);
        
        // Get reports by category
        $reportsByCategory = $this->getReportsByCategory($schoolId);
        
        // Get reports by status
        $reportsByStatus = $this->getReportsByStatus($schoolId);
        
        // Get reports trend (last 6 months)
        $reportsTrend = $this->getReportsTrend($schoolId);
        
        // Get classification distribution
        $classificationStats = $this->getClassificationStats($schoolId);
        
        // Get top reporters
        $topReporters = $this->getTopReporters($schoolId);

        return view('analytics.index', compact(
            'stats',
            'reportsByCategory',
            'reportsByStatus',
            'reportsTrend',
            'classificationStats',
            'topReporters'
        ));
    }

    /**
     * Get basic statistics.
     */
    private function getBasicStats($schoolId)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $totalReports = Report::where('school_id', $schoolId)->count();
        $thisMonthReports = Report::where('school_id', $schoolId)
            ->where('created_at', '>=', $startOfMonth)
            ->count();
        $lastMonthReports = Report::where('school_id', $schoolId)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $pendingReports = Report::where('school_id', $schoolId)
            ->whereIn('status', ['dikirim', 'diproses'])
            ->count();

        $completedReports = Report::where('school_id', $schoolId)
            ->where('status', 'selesai')
            ->count();

        $totalUsers = User::where('school_id', $schoolId)->count();
        
        $avgResolutionDays = Report::where('school_id', $schoolId)
            ->where('status', 'selesai')
            ->whereNotNull('updated_at')
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->value('avg_days') ?? 0;

        $monthChange = $lastMonthReports > 0 
            ? round((($thisMonthReports - $lastMonthReports) / $lastMonthReports) * 100) 
            : 0;

        return [
            'total_reports' => $totalReports,
            'this_month_reports' => $thisMonthReports,
            'month_change' => $monthChange,
            'pending_reports' => $pendingReports,
            'completed_reports' => $completedReports,
            'total_users' => $totalUsers,
            'avg_resolution_days' => round($avgResolutionDays, 1),
        ];
    }

    /**
     * Get reports grouped by category.
     */
    private function getReportsByCategory($schoolId)
    {
        return Report::where('school_id', $schoolId)
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category')
            ->toArray();
    }

    /**
     * Get reports grouped by status.
     */
    private function getReportsByStatus($schoolId)
    {
        return Report::where('school_id', $schoolId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();
    }

    /**
     * Get reports trend for last 6 months.
     */
    private function getReportsTrend($schoolId)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[$date->format('Y-m')] = [
                'label' => $date->translatedFormat('M Y'),
                'count' => 0,
            ];
        }

        $reports = Report::where('school_id', $schoolId)
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->get();

        foreach ($reports as $report) {
            if (isset($months[$report->month])) {
                $months[$report->month]['count'] = $report->total;
            }
        }

        return $months;
    }

    /**
     * Get classification statistics.
     */
    private function getClassificationStats($schoolId)
    {
        $classifications = Report::where('school_id', $schoolId)
            ->select(
                DB::raw('COALESCE(manual_classification, ai_classification, "netral") as classification'),
                DB::raw('count(*) as total')
            )
            ->groupBy('classification')
            ->get()
            ->pluck('total', 'classification')
            ->toArray();

        return [
            'positif' => $classifications['positif'] ?? 0,
            'negatif' => $classifications['negatif'] ?? 0,
            'netral' => $classifications['netral'] ?? 0,
        ];
    }

    /**
     * Get top reporters (users with most reports).
     */
    private function getTopReporters($schoolId)
    {
        return Report::where('reports.school_id', $schoolId)
            ->join('users', 'reports.user_id', '=', 'users.id')
            ->select('users.name', 'users.role', DB::raw('count(*) as total'))
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    /**
     * Export analytics data.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_id;
        
        $reports = Report::with('user')
            ->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan_analytics_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, ['ID', 'Judul', 'Kategori', 'Status', 'Klasifikasi AI', 'Klasifikasi Manual', 'Pengirim', 'Tanggal']);
            
            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->id,
                    $report->title,
                    $report->category,
                    $report->status,
                    $report->ai_classification,
                    $report->manual_classification,
                    $report->user->name,
                    $report->created_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
