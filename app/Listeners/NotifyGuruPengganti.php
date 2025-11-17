<?php

namespace App\Listeners;

use App\Events\GuruPenggantiAssigned;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyGuruPengganti implements ShouldQueue
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
    public function handle(GuruPenggantiAssigned $event): void
    {
        try {
            $guruPengganti = $event->guruPengganti;
            $guru = $guruPengganti->guruPengganti; // The substitute teacher
            $guruAsli = $guruPengganti->guru; // Original teacher
            $jadwal = $guruPengganti->jadwal;

            if (!$guru || !$guru->user) {
                return;
            }

            // Send notification to substitute teacher
            $message = "Anda ditugaskan menggantikan {$guruAsli->nama} " .
                      "untuk mengajar {$jadwal->mataPelajaran->nama} " .
                      "di kelas {$jadwal->kelas->nama} " .
                      "pada {$guruPengganti->tanggal->format('d/m/Y')} " .
                      "({$jadwal->jam_mulai} - {$jadwal->jam_selesai})";

            $this->notificationService->send(
                $guru->user,
                'Tugas Guru Pengganti',
                $message,
                'info',
                '/guru/jadwal'
            );

            // Also notify the original teacher
            if ($guruAsli && $guruAsli->user) {
                $this->notificationService->send(
                    $guruAsli->user,
                    'Guru Pengganti Ditugaskan',
                    "{$guru->nama} telah ditugaskan menggantikan Anda pada {$guruPengganti->tanggal->format('d/m/Y')}",
                    'success',
                    '/guru/jadwal'
                );
            }

            Log::info("NotifyGuruPengganti: Notification sent for guru pengganti ID {$guruPengganti->id}");

        } catch (\Exception $e) {
            Log::error('NotifyGuruPengganti failed: ' . $e->getMessage());
        }
    }
}
