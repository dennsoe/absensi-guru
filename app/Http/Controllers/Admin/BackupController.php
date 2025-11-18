<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * Display backup management page
     */
    public function index()
    {
        // Get list of backup files
        $backupPath = storage_path('app/backup');

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $files = glob($backupPath . '/*.sql');
        $backups = [];

        foreach ($files as $file) {
            $filename = basename($file);
            $backups[] = [
                'filename' => $filename,
                'path' => $file,
                'size' => $this->formatBytes(filesize($file)),
                'date' => date('d/m/Y', filemtime($file)),
                'time' => date('H:i:s', filemtime($file)),
                'timestamp' => filemtime($file),
            ];
        }

        // Sort by timestamp descending
        usort($backups, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        // Calculate statistics
        $totalBackup = count($backups);
        $todayBackup = collect($backups)->filter(function($backup) {
            return $backup['date'] === date('d/m/Y');
        })->count();

        $totalSizeBytes = array_sum(array_map(function($backup) {
            return filesize($backup['path']);
        }, $backups));
        $totalSize = $this->formatBytes($totalSizeBytes);

        $lastBackupDate = $backups[0]['date'] ?? null;
        if ($lastBackupDate) {
            $lastBackupDate .= ' ' . $backups[0]['time'];
        }

        // Paginate manually
        $perPage = 15;
        $currentPage = request()->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedBackups = array_slice($backups, $offset, $perPage);

        $backupList = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedBackups,
            count($backups),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.backup.index', [
            'backupList' => $backupList,
            'totalBackup' => $totalBackup,
            'todayBackup' => $todayBackup,
            'totalSize' => $totalSize,
            'lastBackupDate' => $lastBackupDate,
        ]);
    }

    /**
     * Trigger backup manually
     */
    public function trigger()
    {
        try {
            // Run backup command
            Artisan::call('backup:database');

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download backup file
     */
    public function download($filename)
    {
        $filePath = storage_path('app/backup/' . $filename);

        if (!file_exists($filePath)) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'File backup tidak ditemukan');
        }

        return response()->download($filePath);
    }

    /**
     * Delete backup file
     */
    public function delete($filename)
    {
        try {
            $filePath = storage_path('app/backup/' . $filename);

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File backup tidak ditemukan'
                ], 404);
            }

            unlink($filePath);

            return response()->json([
                'success' => true,
                'message' => 'Backup berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cleanup old backups (older than 30 days)
     */
    public function cleanup()
    {
        try {
            $backupPath = storage_path('app/backup');
            $files = glob($backupPath . '/*.sql');
            $deleted = 0;
            $cutoffDate = Carbon::now()->subDays(30)->timestamp;

            foreach ($files as $file) {
                if (filemtime($file) < $cutoffDate) {
                    unlink($file);
                    $deleted++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus backup lama',
                'deleted' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format bytes to human readable size
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
