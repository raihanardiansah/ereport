<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportAuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs for a specific report.
     */
    public function show(Report $report)
    {
        $user = auth()->user();

        // Authorization check
        if (!$user->isSuperAdmin()) {
            if ($user->school_id !== $report->school_id) {
                abort(403);
            }
            // Only admin and manajemen can view audit logs
            if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah'])) {
                abort(403);
            }
        }

        $auditLogs = ReportAuditLog::where('report_id', $report->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('audit.show', compact('report', 'auditLogs'));
    }

    /**
     * Display all audit logs for the school (admin only).
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasAnyRole(['admin_sekolah', 'manajemen_sekolah']) && !$user->isSuperAdmin()) {
            abort(403);
        }

        $query = ReportAuditLog::with(['report', 'user'])
            ->where('school_id', $user->school_id);

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $auditLogs = $query->latest()->paginate(50);

        // Get unique actions for filter
        $actions = ReportAuditLog::where('school_id', $user->school_id)
            ->distinct()
            ->pluck('action');

        // Get users for filter
        $users = \App\Models\User::where('school_id', $user->school_id)
            ->whereIn('role', ['admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])
            ->orderBy('name')
            ->get();

        return view('audit.index', compact('auditLogs', 'actions', 'users'));
    }
}
