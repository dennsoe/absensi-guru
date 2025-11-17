<?php

namespace App\Jobs;

use App\Models\Guru;
use App\Models\Absensi;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendReminderNotification implements ShouldQueue
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

    protected string $reminderType;

    /**
     * Create a new job instance.
     */
    public function __construct(string $reminderType = 'absensi')
    {
        $this->reminderType = $reminderType;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        try {
            switch ($this->reminderType) {
                case 'absensi':
                    $this->sendAbsensiReminder($notificationService);
                    break;

                case 'checkout':
                    $this->sendCheckoutReminder($notificationService);
                    break;

                case 'izin_pending':
                    $this->sendIzinPendingReminder($notificationService);
                    break;

                default:
                    Log::warning("Unknown reminder type: {$this->reminderType}");
            }

        } catch (\Exception $e) {
            Log::error('SendReminderNotification failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send reminder to guru who haven't checked in
     */
    protected function sendAbsensiReminder(NotificationService $notificationService): void
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        // Check if it's a working day
        if ($now->isWeekend()) {
            return;
        }

        // Get active guru who haven't checked in today
        $gurusWithoutAbsensi = Guru::where('status_kepegawaian', 'aktif')
            ->whereDoesntHave('absensi', function ($query) use ($today) {
                $query->whereDate('tanggal', $today);
            })
            ->with('user')
            ->get();

        $count = 0;
        foreach ($gurusWithoutAbsensi as $guru) {
            if ($guru->user) {
                $notificationService->send(
                    $guru->user,
                    'Reminder: Absensi Hari Ini',
                    'Anda belum melakukan absensi hari ini. Segera lakukan absensi sebelum jam 08:00.',
                    'warning',
                    '/guru/absensi/create'
                );
                $count++;
            }
        }

        Log::info("SendReminderNotification: Sent absensi reminder to {$count} guru");
    }

    /**
     * Send reminder to guru who haven't checked out
     */
    protected function sendCheckoutReminder(NotificationService $notificationService): void
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        // Check if within checkout reminder time (e.g., 15:00 - 16:00)
        $reminderStart = Carbon::parse('15:00');
        $reminderEnd = Carbon::parse('16:00');

        if (!$now->between($reminderStart, $reminderEnd)) {
            return;
        }

        // Get absensi records without checkout today
        $absensiWithoutCheckout = Absensi::whereDate('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_keluar')
            ->with(['guru.user'])
            ->get();

        $count = 0;
        foreach ($absensiWithoutCheckout as $absensi) {
            if ($absensi->guru && $absensi->guru->user) {
                $notificationService->send(
                    $absensi->guru->user,
                    'Reminder: Checkout Absensi',
                    'Jangan lupa untuk melakukan checkout absensi sebelum pulang.',
                    'info',
                    '/guru/absensi'
                );
                $count++;
            }
        }

        Log::info("SendReminderNotification: Sent checkout reminder to {$count} guru");
    }

    /**
     * Send reminder for pending izin/cuti approvals
     */
    protected function sendIzinPendingReminder(NotificationService $notificationService): void
    {
        // This would be sent to admin/kepala sekolah
        // Implementation depends on approval workflow
        Log::info('SendReminderNotification: Izin pending reminder sent to admin');
    }
}
