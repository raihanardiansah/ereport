<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CriticalReportController extends Controller
{
    /**
     * Check for new critical reports since last check
     */
    public function check(Request $request)
    {
        $user = auth()->user();
        
        // Only admins can access this endpoint
        if (!$user->hasAnyRole(['super_admin', 'admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'])) {
            return response()->json(['hasNew' => false, 'reports' => []]);
        }

        // Get timestamp of last check (from query param or default to 1 minute ago)
        $lastCheck = $request->input('since', now()->subMinute()->toDateTimeString());

        $query = Report::where('urgency', 'critical')
            ->where('created_at', '>', $lastCheck)
            ->with(['user', 'school']);

        // Filter by school for non-super admins
        if (!$user->isSuperAdmin()) {
            $query->where('school_id', $user->school_id);
        }

        $criticalReports = $query->latest()->get();

        $reports = $criticalReports->map(function ($report) {
            return [
                'id' => $report->id,
                'title' => $report->title,
                'category' => ucfirst($report->category),
                'reporter' => $report->user->name ?? 'Unknown',
                'created_at' => $report->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'hasNew' => $reports->count() > 0,
            'reports' => $reports,
            'count' => $reports->count()
        ]);
    }
}
