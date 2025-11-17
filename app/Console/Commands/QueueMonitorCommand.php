<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class QueueMonitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue-monitor {--watch : Watch mode - refresh every 3 seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor queue jobs status (pending, failed, processing)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('watch')) {
            $this->info('🔄 Queue Monitor - Watch Mode (Ctrl+C to exit)');
            $this->newLine();
            
            while (true) {
                $this->displayQueueStatus();
                sleep(3);
                
                // Clear screen (for Unix-like systems)
                if (PHP_OS_FAMILY !== 'Windows') {
                    system('clear');
                }
                
                $this->info('🔄 Queue Monitor - Watch Mode (Ctrl+C to exit)');
                $this->newLine();
            }
        } else {
            $this->displayQueueStatus();
        }
        
        return 0;
    }
    
    /**
     * Display queue status information
     */
    protected function displayQueueStatus()
    {
        // Pending Jobs
        $pendingJobs = DB::table('jobs')->count();
        $this->info("📋 Pending Jobs: {$pendingJobs}");
        
        if ($pendingJobs > 0) {
            $jobs = DB::table('jobs')
                ->select(DB::raw('queue, COUNT(*) as count'))
                ->groupBy('queue')
                ->get();
            
            foreach ($jobs as $job) {
                $this->line("   └─ Queue '{$job->queue}': {$job->count} jobs");
            }
        }
        
        $this->newLine();
        
        // Failed Jobs
        $failedJobs = DB::table('failed_jobs')->count();
        
        if ($failedJobs > 0) {
            $this->warn("❌ Failed Jobs: {$failedJobs}");
            
            $recentFailed = DB::table('failed_jobs')
                ->orderBy('failed_at', 'desc')
                ->limit(5)
                ->get(['id', 'queue', 'failed_at', 'exception']);
            
            $this->newLine();
            $this->table(
                ['ID', 'Queue', 'Failed At', 'Exception Preview'],
                $recentFailed->map(function ($job) {
                    return [
                        $job->id,
                        $job->queue,
                        $job->failed_at,
                        substr($job->exception, 0, 50) . '...'
                    ];
                })
            );
        } else {
            $this->info("✅ Failed Jobs: 0");
        }
        
        $this->newLine();
        
        // Queue Configuration
        $this->info("⚙️  Queue Configuration:");
        $this->line("   └─ Connection: " . config('queue.default'));
        $this->line("   └─ Driver: " . config("queue.connections." . config('queue.default') . ".driver"));
        
        $this->newLine();
        
        // Helpful Commands
        $this->comment("💡 Helpful Commands:");
        $this->line("   php artisan queue:work          - Process jobs");
        $this->line("   php artisan queue:listen        - Listen for jobs");
        $this->line("   php artisan queue:retry all     - Retry all failed jobs");
        $this->line("   php artisan queue:flush         - Delete all failed jobs");
        $this->line("   php artisan queue:restart       - Restart queue workers");
    }
}
