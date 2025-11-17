<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\NotifikasiPreference;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to a user
     */
    public function send(
        User $user,
        string $title,
        string $message,
        string $type = 'info',
        ?string $link = null,
        array $data = []
    ): ?Notifikasi {
        // Check user preferences
        $preference = NotifikasiPreference::firstOrCreate(
            ['user_id' => $user->id]
        );

        // Check if user can receive this type of notification
        $eventType = $this->mapTypeToEvent($type);

        if (!$preference->canReceiveNotification('web', $eventType)) {
            return null;
        }

        // Create notification
        $notifikasi = Notifikasi::create([
            'user_id' => $user->id,
            'judul' => $title,
            'pesan' => $message,
            'tipe' => $type,
            'link' => $link,
            'data' => $data,
        ]);

        // Send via enabled channels
        if ($preference->canReceiveNotification('email', $eventType)) {
            $this->sendEmail($user, $title, $message);
        }

        if ($preference->canReceiveNotification('whatsapp', $eventType)) {
            $this->sendWhatsApp($user, $message);
        }

        if ($preference->canReceiveNotification('push', $eventType)) {
            $this->sendPush($user, $title, $message, $data);
        }

        return $notifikasi;
    }

    /**
     * Send notification to multiple users
     */
    public function sendToMany(
        array $users,
        string $title,
        string $message,
        string $type = 'info',
        ?string $link = null,
        array $data = []
    ): int {
        $count = 0;

        foreach ($users as $user) {
            if ($this->send($user, $title, $message, $type, $link, $data)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Send notification to users by role
     */
    public function sendByRole(
        string $role,
        string $title,
        string $message,
        string $type = 'info',
        ?string $link = null,
        array $data = []
    ): int {
        $users = User::where('role', $role)->get();
        return $this->sendToMany($users, $title, $message, $type, $link, $data);
    }

    /**
     * Send email notification
     */
    protected function sendEmail(User $user, string $title, string $message): void
    {
        try {
            if (!$user->email) {
                return;
            }

            Mail::raw($message, function ($mail) use ($user, $title) {
                $mail->to($user->email)
                    ->subject($title);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage());
        }
    }

    /**
     * Send WhatsApp notification
     */
    protected function sendWhatsApp(User $user, string $message): void
    {
        try {
            if (!$user->no_hp) {
                return;
            }

            // Integration with WhatsApp API would go here
            // For now, just log
            Log::info('WhatsApp notification to ' . $user->no_hp . ': ' . $message);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }

    /**
     * Send push notification
     */
    protected function sendPush(User $user, string $title, string $message, array $data): void
    {
        try {
            // Integration with push notification service would go here
            // For now, just log
            Log::info('Push notification to user ' . $user->id . ': ' . $title);
        } catch (\Exception $e) {
            Log::error('Failed to send push notification: ' . $e->getMessage());
        }
    }

    /**
     * Map notification type to event type for preferences
     */
    protected function mapTypeToEvent(string $type): string
    {
        $mapping = [
            'info' => 'pengumuman',
            'success' => 'pengumuman',
            'warning' => 'peringatan',
            'danger' => 'peringatan',
            'absensi' => 'absensi',
            'izin' => 'izin',
            'cuti' => 'izin',
            'jadwal' => 'jadwal',
        ];

        return $mapping[$type] ?? 'pengumuman';
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notifikasi $notifikasi): void
    {
        $notifikasi->markAsRead();
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): int
    {
        return Notifikasi::where('user_id', $user->id)
            ->whereNull('dibaca_pada')
            ->update([
                'dibaca_pada' => now(),
            ]);
    }

    /**
     * Delete old notifications
     */
    public function deleteOld(int $days = 30): int
    {
        return Notifikasi::where('dibaca_pada', '<', now()->subDays($days))
            ->orWhere(function ($query) use ($days) {
                $query->whereNull('dibaca_pada')
                    ->where('created_at', '<', now()->subDays($days * 2));
            })
            ->delete();
    }

    /**
     * Get unread count for a user
     */
    public function getUnreadCount(User $user): int
    {
        return Notifikasi::where('user_id', $user->id)
            ->whereNull('dibaca_pada')
            ->count();
    }
}
