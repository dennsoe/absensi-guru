<?php

namespace App\Listeners;

use App\Events\NotificationSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogNotificationSent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event): void
    {
        try {
            $notifikasi = $event->notifikasi;

            // Log notification details
            Log::info('Notification sent', [
                'id' => $notifikasi->id,
                'user_id' => $notifikasi->user_id,
                'type' => $notifikasi->tipe,
                'title' => $notifikasi->judul,
                'sent_at' => now()->toDateTimeString(),
            ]);

            // You can also track analytics here
            // For example, increment notification count in cache or analytics service

        } catch (\Exception $e) {
            Log::error('LogNotificationSent failed: ' . $e->getMessage());
        }
    }
}
