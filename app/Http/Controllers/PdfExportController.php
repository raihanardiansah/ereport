<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfExportController extends Controller
{
    /**
     * Generate student report card PDF.
     */
    public function studentReportCard(User $user)
    {
        $authUser = auth()->user();
        
        // Authorization: must be same school
        if (!$authUser->isSuperAdmin() && $authUser->school_id !== $user->school_id) {
            abort(403);
        }

        // Only allow for students
        if (!$user->isSiswa()) {
            return back()->with('error', 'Raport hanya tersedia untuk siswa.');
        }

        // Get student's reports
        $reports = Report::where('school_id', $user->school_id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get reports mentioning this student (by other users)
        $reportsAbout = Report::where('school_id', $user->school_id)
            ->where('content', 'like', '%' . $user->name . '%')
            ->where('user_id', '!=', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_reports' => $reports->count(),
            'reports_about' => $reportsAbout->count(),
            'by_category' => $reportsAbout->groupBy('category')->map->count(),
            'by_classification' => $reportsAbout->groupBy(fn($r) => $r->getClassification() ?? 'netral')->map->count(),
        ];

        $school = $user->school;

        $pdf = Pdf::loadView('pdf.student-report-card', compact('user', 'reports', 'reportsAbout', 'stats', 'school'));
        
        $filename = 'raport_' . str_replace(' ', '_', strtolower($user->name)) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate teacher report card PDF.
     */
    public function teacherReportCard(User $user)
    {
        $authUser = auth()->user();
        
        // Authorization: must be same school
        if (!$authUser->isSuperAdmin() && $authUser->school_id !== $user->school_id) {
            abort(403);
        }

        // Only allow for teachers/staff
        if (!in_array($user->role, ['guru', 'staf_kesiswaan'])) {
            return back()->with('error', 'Raport hanya tersedia untuk guru/staf.');
        }

        // Get reports where teacher is accused
        $reportsAbout = Report::whereHas('accusedUsers', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'reports_about' => $reportsAbout->count(),
            'by_category' => $reportsAbout->groupBy('category')->map->count(),
            'by_classification' => $reportsAbout->groupBy(fn($r) => $r->getClassification() ?? 'netral')->map->count(),
        ];

        $school = $user->school;

        $pdf = Pdf::loadView('pdf.teacher-report-card', compact('user', 'reportsAbout', 'stats', 'school'));
        
        $filename = 'raport_guru_' . str_replace(' ', '_', strtolower($user->name)) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate monthly/semester summary PDF.
     */
    public function monthlySummary(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_id;
        $school = $user->school;

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $type = $request->get('type', 'monthly'); // monthly or semester

        if ($type === 'semester') {
            // Determine semester: 1 (Jan-Jun) or 2 (Jul-Dec)
            $semester = $month <= 6 ? 1 : 2;
            $startDate = Carbon::create($year, $semester === 1 ? 1 : 7, 1)->startOfDay();
            $endDate = Carbon::create($year, $semester === 1 ? 6 : 12, 1)->endOfMonth();
            $periodLabel = "Semester " . $semester . " " . $year;
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            $periodLabel = $startDate->translatedFormat('F Y');
        }

        // Get reports in period
        $reports = Report::where('school_id', $schoolId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $stats = [
            'total' => $reports->count(),
            'by_category' => $reports->groupBy('category')->map->count(),
            'by_status' => $reports->groupBy('status')->map->count(),
            'by_classification' => $reports->groupBy(fn($r) => $r->getClassification() ?? 'netral')->map->count(),
            'completed' => $reports->where('status', 'selesai')->count(),
            'pending' => $reports->whereIn('status', ['dikirim', 'diproses'])->count(),
        ];

        // Top categories
        $topCategories = $reports->groupBy('category')
            ->map(fn($g) => $g->count())
            ->sortDesc()
            ->take(5);

        $pdf = Pdf::loadView('pdf.monthly-summary', compact(
            'school', 'reports', 'stats', 'topCategories', 'periodLabel', 'startDate', 'endDate', 'type'
        ));
        
        $filename = 'ringkasan_' . $type . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate analytics dashboard PDF for meetings.
     */
    public function analyticsDashboard()
    {
        $user = auth()->user();
        $schoolId = $user->school_id;
        $school = $user->school;

        // Get basic stats (similar to AnalyticsController)
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        
        $totalReports = Report::where('school_id', $schoolId)->count();
        $thisMonthReports = Report::where('school_id', $schoolId)
            ->where('created_at', '>=', $startOfMonth)
            ->count();
        $pendingReports = Report::where('school_id', $schoolId)
            ->whereIn('status', ['dikirim', 'diproses'])
            ->count();
        $completedReports = Report::where('school_id', $schoolId)
            ->where('status', 'selesai')
            ->count();
        $totalUsers = User::where('school_id', $schoolId)->count();

        // Reports by category
        $reportsByCategory = Report::where('school_id', $schoolId)
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category');

        // Reports by status
        $reportsByStatus = Report::where('school_id', $schoolId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Classification stats
        $classificationStats = Report::where('school_id', $schoolId)
            ->select(
                DB::raw('COALESCE(manual_classification, ai_classification, "netral") as classification'),
                DB::raw('count(*) as total')
            )
            ->groupBy('classification')
            ->get()
            ->pluck('total', 'classification');

        // Monthly trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $count = Report::where('school_id', $schoolId)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            $monthlyTrend[] = [
                'month' => $date->translatedFormat('M Y'),
                'count' => $count,
            ];
        }

        // Top reporters
        $topReporters = Report::where('reports.school_id', $schoolId)
            ->join('users', 'reports.user_id', '=', 'users.id')
            ->select('users.name', 'users.role', DB::raw('count(*) as total'))
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $stats = compact(
            'totalReports', 'thisMonthReports', 'pendingReports', 'completedReports', 'totalUsers'
        );

        $pdf = Pdf::loadView('pdf.analytics-dashboard', compact(
            'school', 'stats', 'reportsByCategory', 'reportsByStatus', 
            'classificationStats', 'monthlyTrend', 'topReporters'
        ));
        
        $filename = 'dashboard_analytics_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
