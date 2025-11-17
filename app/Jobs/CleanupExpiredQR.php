<?php

namespace App\Jobs;

use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CleanupExpiredQR implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

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
        try {
            $expiryMinutes = config('absensi.qr_expiry_minutes', 5);
            $cutoffTime = Carbon::now()->subMinutes($expiryMinutes);

            // Delete expired QR codes
            $deleted = QrCode::where('created_at', '<', $cutoffTime)
                ->orWhere('expired_at', '<', Carbon::now())
                ->orWhere('is_used', true)
                ->delete();

            Log::info('CleanupExpiredQR: Deleted ' . $deleted . ' expired QR codes');

        } catch (\Exception $e) {
            Log::error('CleanupExpiredQR failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
