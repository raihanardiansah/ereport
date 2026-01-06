<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Report;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Super Admin - List all schools.
     */
    public function schools(Request $request)
    {
        $query = School::withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('subscription_status', $request->status);
        }

        $schools = $query->orderByDesc('created_at')->paginate(15);

        // Stats
        $totalSchools = School::count();
        $activeSchools = School::where('subscription_status', 'active')->count();
        $trialSchools = School::where('subscription_status', 'trial')->count();
        $totalUsers = User::whereNotNull('school_id')->count();

        return view('admin.schools', compact(
            'schools',
            'totalSchools',
            'activeSchools',
            'trialSchools',
            'totalUsers'
        ));
    }

    /**
     * Super Admin - View school details.
     */
    public function schoolDetail(School $school)
    {
        $school->load(['users', 'subscriptions.package']);
        
        $reportsCount = Report::where('school_id', $school->id)->count();
        $recentReports = Report::where('school_id', $school->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Get current/active subscription
        $currentSubscription = $school->subscriptions()
            ->whereIn('status', ['active', 'pending'])
            ->orderByDesc('expires_at')
            ->first();
        
        // Get payment history
        $payments = Payment::whereHas('subscription', function($q) use ($school) {
            $q->where('school_id', $school->id);
        })->orderByDesc('created_at')->take(5)->get();

        return view('admin.school-detail', compact(
            'school', 
            'reportsCount', 
            'recentReports',
            'currentSubscription',
            'payments'
        ));
    }

    /**
     * Super Admin - Toggle school status (suspend/activate).
     */
    public function toggleSchoolStatus(Request $request, School $school)
    {
        $action = $request->input('action');
        
        if ($action === 'suspend') {
            $previousStatus = $school->subscription_status;
            $school->subscription_status = 'suspended';
            $school->settings = array_merge($school->settings ?? [], [
                'suspended_at' => now()->toISOString(),
                'suspended_reason' => $request->input('reason', 'Account suspended by administrator'),
                'previous_status' => $previousStatus,
            ]);
            $school->save();
            
            // Log the action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'school_suspended',
                'description' => "School '{$school->name}' suspended. Reason: " . ($request->input('reason') ?? 'No reason provided'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return back()->with('success', "Sekolah {$school->name} berhasil dinonaktifkan.");
        }
        
        if ($action === 'activate') {
            $previousStatus = $school->settings['previous_status'] ?? 'active';
            $school->subscription_status = $previousStatus !== 'suspended' ? $previousStatus : 'active';
            
            $settings = $school->settings ?? [];
            unset($settings['suspended_at'], $settings['suspended_reason'], $settings['previous_status']);
            $school->settings = $settings;
            $school->save();
            
            // Log the action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'school_activated',
                'description' => "School '{$school->name}' reactivated",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return back()->with('success', "Sekolah {$school->name} berhasil diaktifkan kembali.");
        }
        
        return back()->with('error', 'Aksi tidak valid.');
    }

    /**
     * Super Admin - Manually update school subscription.
     */
    public function updateSchoolSubscription(Request $request, School $school)
    {
        $validated = $request->validate([
            'subscription_status' => ['required', 'in:trial,active,expired,suspended'],
            'trial_ends_at' => ['nullable', 'date'],
        ]);
        
        $school->subscription_status = $validated['subscription_status'];
        
        if (isset($validated['trial_ends_at'])) {
            $school->trial_ends_at = $validated['trial_ends_at'];
        }
        
        $school->save();
        
        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'school_subscription_updated',
            'description' => "School '{$school->name}' subscription updated to: {$validated['subscription_status']}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        return back()->with('success', 'Status langganan berhasil diperbarui.');
    }

    /**
     * Super Admin - Audit logs.
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderByDesc('created_at')->paginate(20);

        $actions = AuditLog::select('action')
            ->distinct()
            ->pluck('action');

        return view('admin.audit-logs', compact('logs', 'actions'));
    }

    /**
     * Super Admin - Backup & Security page.
     */
    public function backup()
    {
        // Get system stats
        $stats = [
            'total_schools' => School::count(),
            'total_users' => User::count(),
            'total_reports' => Report::count(),
            'total_payments' => Payment::count(),
            'database_size' => $this->getDatabaseSize(),
            'last_backup' => null, // Would be from backup system
        ];

        return view('admin.backup', compact('stats'));
    }

    /**
     * Get database size (MySQL).
     */
    private function getDatabaseSize(): string
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT SUM(data_length + index_length) as size
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$database]);
            
            $bytes = $result[0]->size ?? 0;
            return $this->formatBytes($bytes);
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function formatBytes($bytes): string
    {
        if ($bytes === 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }

    /**
     * Super Admin - Platform analytics dashboard.
     */
    public function analytics()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Basic stats
        $stats = [
            'total_schools' => School::count(),
            'active_schools' => School::where('subscription_status', 'active')->count(),
            'trial_schools' => School::where('subscription_status', 'trial')->count(),
            'suspended_schools' => School::where('subscription_status', 'suspended')->count(),
            'total_users' => User::count(),
            'total_reports' => Report::count(),
            'this_month_reports' => Report::where('created_at', '>=', $startOfMonth)->count(),
            'last_month_reports' => Report::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count(),
            'total_cases' => \App\Models\StudentCase::count(),
            'open_cases' => \App\Models\StudentCase::where('status', 'open')->count(),
        ];

        // Reports by category (all schools)
        $reportsByCategory = Report::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category')
            ->toArray();

        // Reports by status (all schools)
        $reportsByStatus = Report::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // 6-month trend
        $reportsTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Report::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $reportsTrend[] = [
                'label' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        // Top schools by reports
        $topSchools = School::withCount('users')
            ->with(['subscriptions' => function($q) {
                $q->where('status', 'active')->latest();
            }])
            ->get()
            ->map(function ($school) {
                $school->reports_count = Report::where('school_id', $school->id)->count();
                $school->cases_count = \App\Models\StudentCase::where('school_id', $school->id)->count();
                return $school;
            })
            ->sortByDesc('reports_count')
            ->take(10);

        // Recent reports across platform
        $recentReports = Report::with(['user', 'school'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.analytics', compact(
            'stats',
            'reportsByCategory',
            'reportsByStatus',
            'reportsTrend',
            'topSchools',
            'recentReports'
        ));
    }

    /**
     * Super Admin - View all student cases across schools.
     */
    public function allStudentCases(Request $request)
    {
        $query = \App\Models\StudentCase::with(['student', 'counselor', 'school']);

        // Filter by school
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('student', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('school', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $cases = $query->latest()->paginate(15);
        $schools = School::orderBy('name')->get();

        // Stats
        $totalCases = \App\Models\StudentCase::count();
        $openCases = \App\Models\StudentCase::where('status', 'open')->count();
        $inProgressCases = \App\Models\StudentCase::where('status', 'in_progress')->count();
        $resolvedCases = \App\Models\StudentCase::whereIn('status', ['resolved', 'closed'])->count();

        return view('admin.student-cases', compact(
            'cases', 
            'schools', 
            'totalCases', 
            'openCases', 
            'inProgressCases', 
            'resolvedCases'
        ));
    }

    /**
     * Super Admin - Export data to CSV.
     */
    public function exportCsv(Request $request, $type)
    {
        $filename = "export_{$type}_" . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            if ($type === 'schools') {
                fputcsv($file, ['ID', 'Nama', 'NPSN', 'Email', 'Telepon', 'Kota', 'Status', 'Jumlah User', 'Jumlah Laporan', 'Terdaftar']);
                $schools = School::withCount('users')->get();
                foreach ($schools as $school) {
                    $reportsCount = Report::where('school_id', $school->id)->count();
                    fputcsv($file, [
                        $school->id,
                        $school->name,
                        $school->npsn,
                        $school->email,
                        $school->phone,
                        $school->city,
                        $school->subscription_status,
                        $school->users_count,
                        $reportsCount,
                        $school->created_at->format('d/m/Y'),
                    ]);
                }
            } elseif ($type === 'reports') {
                fputcsv($file, ['ID', 'Sekolah', 'Judul', 'Kategori', 'Status', 'Klasifikasi', 'Pengirim', 'Tanggal']);
                $reports = Report::with(['user', 'school'])->orderByDesc('created_at')->get();
                foreach ($reports as $report) {
                    fputcsv($file, [
                        $report->id,
                        $report->school->name ?? '-',
                        $report->title,
                        $report->category,
                        $report->status,
                        $report->getClassification(),
                        $report->user->name ?? '-',
                        $report->created_at->format('d/m/Y H:i'),
                    ]);
                }
            } elseif ($type === 'cases') {
                fputcsv($file, ['Nomor Kasus', 'Sekolah', 'Siswa', 'Judul', 'Prioritas', 'Status', 'Konselor', 'Tanggal']);
                $cases = \App\Models\StudentCase::with(['student', 'counselor', 'school'])->orderByDesc('created_at')->get();
                foreach ($cases as $case) {
                    fputcsv($file, [
                        $case->case_number,
                        $case->school->name ?? '-',
                        $case->student->name ?? '-',
                        $case->title,
                        $case->priority_label,
                        $case->status_label,
                        $case->counselor->name ?? '-',
                        $case->created_at->format('d/m/Y'),
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Super Admin - Export platform report to PDF.
     */
    public function exportPdf()
    {
        $stats = [
            'total_schools' => School::count(),
            'active_schools' => School::where('subscription_status', 'active')->count(),
            'trial_schools' => School::where('subscription_status', 'trial')->count(),
            'total_users' => User::count(),
            'total_reports' => Report::count(),
            'total_cases' => \App\Models\StudentCase::count(),
        ];

        $topSchools = School::withCount('users')
            ->get()
            ->map(function ($school) {
                $school->reports_count = Report::where('school_id', $school->id)->count();
                return $school;
            })
            ->sortByDesc('reports_count')
            ->take(10);

        $reportsByCategory = Report::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category')
            ->toArray();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.platform-report', compact(
            'stats',
            'topSchools',
            'reportsByCategory'
        ));

        return $pdf->download('laporan_platform_' . date('Y-m-d') . '.pdf');
    }
}
