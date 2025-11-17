<?php

namespace App\Jobs;

use App\Models\BackupHistory;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AutoBackupDatabase implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = Carbon::now();
        $filename = null;

        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = storage_path('app/backups/');

            // Create backup directory if not exists
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $filepath = $path . $filename;

            // Execute mysqldump
            $command = sprintf(
                'mysqldump -h %s -u %s %s %s > %s',
                escapeshellarg($host),
                escapeshellarg($username),
                $password ? '-p' . escapeshellarg($password) : '',
                escapeshellarg($database),
                escapeshellarg($filepath)
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Mysqldump failed with return code: ' . $returnVar);
            }

            $endTime = Carbon::now();
            $fileSize = filesize($filepath);

            // Create backup history record
            $backup = BackupHistory::create([
                'filename' => $filename,
                'file_path' => 'backups/' . $filename,
                'file_size' => $fileSize,
                'backup_type' => 'auto',
                'status' => 'success',
                'started_at' => $startTime,
                'completed_at' => $endTime,
                'duration' => $endTime->diffInSeconds($startTime),
            ]);

            // Compress backup
            $this->compressBackup($filepath);

            // Delete old backups
            $this->deleteOldBackups();

            Log::info("AutoBackupDatabase: Backup created successfully - {$filename}");

        } catch (\Exception $e) {
            BackupHistory::create([
                'filename' => $filename ?? 'unknown',
                'file_path' => null,
                'file_size' => 0,
                'backup_type' => 'auto',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'started_at' => $startTime,
                'completed_at' => Carbon::now(),
            ]);

            Log::error('AutoBackupDatabase failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Compress backup file
     */
    protected function compressBackup(string $filepath): void
    {
        if (function_exists('gzencode')) {
            $gzFilepath = $filepath . '.gz';
            $data = file_get_contents($filepath);
            file_put_contents($gzFilepath, gzencode($data, 9));
            unlink($filepath); // Delete original SQL file
        }
    }

    /**
     * Delete backups older than retention period
     */
    protected function deleteOldBackups(): void
    {
        $retentionDays = config('backup.retention_days', 30);
        $cutoffDate = Carbon::now()->subDays($retentionDays);

        $oldBackups = BackupHistory::where('created_at', '<', $cutoffDate)
            ->where('auto_delete', true)
            ->get();

        foreach ($oldBackups as $backup) {
            $fullPath = storage_path('app/' . $backup->file_path);

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            if (file_exists($fullPath . '.gz')) {
                unlink($fullPath . '.gz');
            }

            $backup->delete();
        }

        Log::info("AutoBackupDatabase: Deleted {$oldBackups->count()} old backups");
    }
}
