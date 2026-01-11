<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Models\School;
use App\Models\SchoolStatistic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggregateSchoolStatistics extends Command
{
    protected $signature = 'statistics:aggregate {--period= : Period in YYYY-MM format, defaults to last month}';

    protected $description = 'Aggregate monthly statistics for all schools';

    public function handle()
    {
        $period = $this->option('period') ?? now()->subMonth()->format('Y-m');
        
        $this->info("Aggregating statistics for period: {$period}");

        $schools = School::whereIn('subscription_status', ['active', 'trial'])->get();
        $bar = $this->output->createProgressBar($schools->count());

        foreach ($schools as $school) {
            $this->aggregateForSchool($school, $period);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Statistics aggregation completed!');

        return Command::SUCCESS;
    }

    protected function aggregateForSchool(School $school, string $period): void
    {
        $startDate = "{$period}-01";
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of month

        $reports = Report::where('school_id', $school->id)
            ->whereBetween('created_at', [$startDate, "{$endDate} 23:59:59"])
            ->get();

        $totalReports = $reports->count();
        $resolvedReports = $reports->where('status', 'selesai')->count();
        $escalatedReports = $reports->where('escalation_level', '>', 0)->count();
        $anonymousReports = $reports->where('is_anonymous', true)->count();

        // Calculate average resolution time
        $avgResolutionHours = Report::where('school_id', $school->id)
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, "{$endDate} 23:59:59"])
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->value('avg_hours') ?? 0;

        // Count sentiments
        $sentiments = $reports->groupBy(function ($report) {
            return $report->manual_classification ?? $report->ai_classification ?? 'netral';
        });

        SchoolStatistic::updateOrCreate(
            [
                'school_id' => $school->id,
                'period' => $period,
            ],
            [
                'total_reports' => $totalReports,
                'resolved_reports' => $resolvedReports,
                'escalated_reports' => $escalatedReports,
                'avg_resolution_hours' => $avgResolutionHours,
                'positive_count' => $sentiments->get('positif', collect())->count(),
                'negative_count' => $sentiments->get('negatif', collect())->count(),
                'neutral_count' => $sentiments->get('netral', collect())->count(),
                'anonymous_reports' => $anonymousReports,
            ]
        );
    }
}
