<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Models\User;
use App\Services\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EscalateReports extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reports:escalate';

    /**
     * The console command description.
     */
    protected $description = 'Escalate pending reports that have not been actioned within the SLA timeframe';

    /**
     * Escalation thresholds in hours.
     */
    protected array $thresholds = [
        1 => 5,   // Level 1: Reports pending > 5 hours
        2 => 12,  // Level 2: Reports pending > 12 hours
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting report escalation check...');

        $escalatedCount = 0;

        // Get reports that are still in early status and not fully escalated
        $pendingReports = Report::whereIn('status', ['dikirim', 'diproses'])
            ->where('escalation_level', '<', 2)
            ->get();

        foreach ($pendingReports as $report) {
            $hoursElapsed = now()->diffInHours($report->created_at);
            $currentLevel = $report->escalation_level;

            // Check if report should be escalated to next level
            foreach ($this->thresholds as $level => $hours) {
                if ($currentLevel < $level && $hoursElapsed >= $hours) {
                    $this->escalateReport($report, $level);
                    $escalatedCount++;
                    break; // Only escalate one level at a time
                }
            }
        }

        $this->info("Escalation complete. {$escalatedCount} reports escalated.");
        Log::info("Report escalation job completed", ['escalated_count' => $escalatedCount]);

        return Command::SUCCESS;
    }

    /**
     * Escalate a report to the specified level.
     */
    protected function escalateReport(Report $report, int $level): void
    {
        $report->update([
            'escalation_level' => $level,
            'escalated_at' => now(),
        ]);

        $this->line("  → Escalated Report #{$report->id} to Level {$level}");

        // Get appropriate recipients based on escalation level
        $recipients = $this->getEscalationRecipients($report, $level);

        // Send escalation notification
        foreach ($recipients as $recipient) {
            Notification::create([
                'user_id' => $recipient->id,
                'title' => $this->getEscalationTitle($level),
                'message' => $this->getEscalationMessage($report, $level),
                'type' => 'escalation',
                'data' => [
                    'report_id' => $report->id,
                    'escalation_level' => $level,
                ],
                'is_urgent' => true,
            ]);
        }

        Log::warning("Report escalated", [
            'report_id' => $report->id,
            'school_id' => $report->school_id,
            'level' => $level,
            'hours_pending' => now()->diffInHours($report->created_at),
        ]);
    }

    /**
     * Get recipients for escalation notification based on level.
     */
    protected function getEscalationRecipients(Report $report, int $level): \Illuminate\Support\Collection
    {
        $query = User::where('school_id', $report->school_id);

        if ($level === 1) {
            // Level 1: Notify all staf_kesiswaan and admin_sekolah
            $query->whereHas('roles', function ($q) {
                $q->whereIn('name', ['staf_kesiswaan', 'admin_sekolah']);
            });
        } else {
            // Level 2: Notify manajemen_sekolah (Kepala Sekolah) and admin_sekolah
            $query->whereHas('roles', function ($q) {
                $q->whereIn('name', ['manajemen_sekolah', 'admin_sekolah']);
            });
        }

        return $query->get();
    }

    /**
     * Get escalation notification title.
     */
    protected function getEscalationTitle(int $level): string
    {
        return match ($level) {
            1 => 'Laporan Perlu Penanganan Segera',
            2 => 'URGENT: Laporan Belum Ditangani > 12 Jam',
            default => 'Escalated Report',
        };
    }

    /**
     * Get escalation notification message.
     */
    protected function getEscalationMessage(Report $report, int $level): string
    {
        $hours = now()->diffInHours($report->created_at);
        
        return match ($level) {
            1 => "Laporan \"{$report->title}\" sudah menunggu {$hours} jam tanpa penanganan. Mohon segera ditindaklanjuti.",
            2 => "PENTING: Laporan \"{$report->title}\" sudah {$hours} jam belum ada tindak lanjut. Eskalasi ke Kepala Sekolah.",
            default => "Report #{$report->id} has been escalated.",
        };
    }
}
