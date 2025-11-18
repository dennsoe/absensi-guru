<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AutoBackupDatabase;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup-database {--sync : Run backup synchronously instead of queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database backup with mysqldump and gzip compression';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting database backup...');

        if ($this->option('sync')) {
            // Run backup synchronously
            $this->info('Running backup synchronously...');

            try {
                $job = new AutoBackupDatabase();
                $job->handle();

                $this->info('✅ Database backup completed successfully!');
                return 0;
            } catch (\Exception $e) {
                $this->error('❌ Backup failed: ' . $e->getMessage());
                return 1;
            }
        } else {
            // Dispatch to queue
            AutoBackupDatabase::dispatch();
            $this->info('✅ Backup job dispatched to queue.');
            $this->info('   Run "php artisan queue:work" to process the job.');
            return 0;
        }
    }
}
