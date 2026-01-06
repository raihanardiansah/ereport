<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Services\EmailService;
use Illuminate\Console\Command;

class SendWeeklyDigest extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'email:weekly-digest {--school= : Specific school ID to send to}';

    /**
     * The console command description.
     */
    protected $description = 'Send weekly digest emails to all school admins';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting weekly digest emails...');

        $query = School::query();
        
        if ($schoolId = $this->option('school')) {
            $query->where('id', $schoolId);
        }

        $schools = $query->where('subscription_status', '!=', 'expired')->get();
        $sent = 0;

        foreach ($schools as $school) {
            $this->line("Sending digest to: {$school->name}");
            EmailService::sendWeeklyDigest($school);
            $sent++;
        }

        $this->info("Completed! Sent {$sent} digest emails.");

        return Command::SUCCESS;
    }
}
