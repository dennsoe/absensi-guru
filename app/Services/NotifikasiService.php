<?php

namespace App\Services;

use App\Models\{User, BroadcastMessage};
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotifikasiService
{
    /**
     * Send notification to user(s)
     */
    public function send($users, $title, $message, $type = 'info', $channels = ['app'])
    {
        if (!is_array($users) && !($users instanceof \Illuminate\Support\Collection)) {
            $users = [$users];
        }

        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($users as $user) {
            try {
                if (in_array('app', $channels)) {
                    $this->sendAppNotification($user, $title, $message, $type);
                    $results['success'][] = $user->id;
                }

                if (in_array('email', $channels) && $user->email) {
                    $this->sendEmailNotification($user, $title, $message);
                }

                if (in_array('push', $channels)) {
                    $this->sendPushNotification($user, $title, $message);
                }

            } catch (\Exception $e) {
                Log::error("Failed to send notification to user {$user->id}: " . $e->getMessage());
                $results['failed'][] = $user->id;
            }
        }

        return $results;
    }

    /**
     * Send in-app notification
     */
    private function sendAppNotification($user, $title, $message, $type)
    {
        // TODO: Implement in-app notification storage
        // Could use a notifications table or Laravel's built-in notification system

        return true;
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification($user, $title, $message)
    {
        try {
            Mail::send('emails.notification', [
                'title' => $title,
                'message' => $message,
                'user' => $user,
            ], function($mail) use ($user, $title) {
                $mail->to($user->email)
                     ->subject($title);
            });

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification
     */
    private function sendPushNotification($user, $title, $message)
    {
        // TODO: Implement push notification using Firebase Cloud Messaging or similar
        // For PWA, this would send to service worker

        return true;
    }

    /**
     * Notify about izin/cuti approval
     */
    public function notifyIzinCutiApproval($izinCuti, $approved)
    {
        $status = $approved ? 'disetujui' : 'ditolak';
        $title = "Izin/Cuti {$status}";
        $message = "Permohonan {$izinCuti->jenis} Anda dengan nomor {$izinCuti->nomor_surat} telah {$status}.";

        return $this->send(
            $izinCuti->guru->user,
            $title,
            $message,
            $approved ? 'success' : 'warning',
            ['app', 'email']
        );
    }

    /**
     * Notify guru about missed attendance
     */
    public function notifyMissedAttendance($guru, $jadwal, $tanggal)
    {
        $title = "Reminder: Belum Absen";
        $message = "Anda belum melakukan absensi untuk jadwal {$jadwal->mataPelajaran->nama_mapel} di kelas {$jadwal->kelas->nama_kelas} pada tanggal " . $tanggal->format('d/m/Y');

        return $this->send(
            $guru->user,
            $title,
            $message,
            'warning',
            ['app', 'push']
        );
    }

    /**
     * Notify about approaching schedule
     */
    public function notifyApproachingSchedule($guru, $jadwal, $minutesUntil)
    {
        $title = "Jadwal Akan Dimulai";
        $message = "Jadwal mengajar {$jadwal->mataPelajaran->nama_mapel} di kelas {$jadwal->kelas->nama_kelas} akan dimulai dalam {$minutesUntil} menit. Jangan lupa absen!";

        return $this->send(
            $guru->user,
            $title,
            $message,
            'info',
            ['app', 'push']
        );
    }

    /**
     * Notify admin about critical alerts
     */
    public function notifyAdminAlert($title, $message)
    {
        $admins = User::where('role', 'admin')->get();

        return $this->send(
            $admins,
            $title,
            $message,
            'danger',
            ['app', 'email']
        );
    }

    /**
     * Send broadcast notification
     */
    public function sendBroadcast(BroadcastMessage $broadcast)
    {
        $users = $this->getTargetUsers($broadcast->target);

        $channels = [];
        if ($broadcast->send_notification) {
            $channels[] = 'app';
            $channels[] = 'push';
        }
        if ($broadcast->send_email) {
            $channels[] = 'email';
        }

        return $this->send(
            $users,
            $broadcast->title,
            $broadcast->message,
            $broadcast->priority === 'urgent' ? 'danger' : 'info',
            $channels
        );
    }

    /**
     * Get target users for broadcast
     */
    private function getTargetUsers($target)
    {
        switch ($target) {
            case 'all':
                return User::all();

            case 'admin':
                return User::where('role', 'admin')->get();

            case 'guru':
                return User::where('role', 'guru')->get();

            case 'ketua_kelas':
                return User::where('role', 'ketua_kelas')->get();

            case 'guru_piket':
                return User::whereHas('guruPiket')->get();

            default:
                return collect();
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId)
    {
        // TODO: Implement marking notification as read
        return true;
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount($userId)
    {
        // TODO: Implement getting unread count
        return 0;
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications($userId, $limit = 10)
    {
        // TODO: Implement getting user notifications
        return collect();
    }
}
