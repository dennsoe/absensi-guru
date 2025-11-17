<?php

namespace App\Listeners;

use App\Events\AbsensiCreated;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendAbsensiNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected NotificationService $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(AbsensiCreated $event): void
    {
        try {
            $absensi = $event->absensi;
            $guru = $absensi->guru;

            // Send notification to guru
            if ($guru && $guru->user) {
                $status = ucfirst($absensi->status);
                $message = "Absensi berhasil dicatat dengan status: {$status}";

                if ($absensi->status === 'terlambat') {
                    $message .= " ({$absensi->menit_keterlambatan} menit keterlambatan)";
                }

                $this->notificationService->send(
                    $guru->user,
                    'Absensi Tercatat',
                    $message,
                    'success',
                    '/guru/absensi'
                );
            }

            // Notify admin if late or alpha
            if (in_array($absensi->status, ['terlambat', 'alpha'])) {
                $this->notificationService->sendByRole(
                    'admin',
                    'Absensi: ' . ucfirst($absensi->status),
                    "{$guru->nama} - {$absensi->status} pada " . $absensi->tanggal->format('d/m/Y'),
                    'warning',
                    '/admin/absensi/' . $absensi->id
                );
            }

            Log::info("SendAbsensiNotification: Notification sent for absensi ID {$absensi->id}");

        } catch (\Exception $e) {
            Log::error('SendAbsensiNotification failed: ' . $e->getMessage());
        }
    }
}
