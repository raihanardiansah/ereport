<?php

namespace App\Http\Middleware;

use App\Models\Report;
use App\Models\ReportAuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditReportAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only audit if user is authenticated
        if (!auth()->check()) {
            return $response;
        }

        // Check if this is a report view/show request
        if ($request->route('report') instanceof Report) {
            $report = $request->route('report');

            // Only audit critical and high urgency reports
            if (ReportAuditLog::shouldAudit($report)) {
                ReportAuditLog::logAction(
                    $report,
                    auth()->user(),
                    'viewed',
                    'User viewed the report details'
                );
            }
        }

        return $response;
    }
}
