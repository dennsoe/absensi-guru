<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotifikasiPreference extends Model
{
    use HasFactory;

    protected $table = 'notifikasi_preferences';

    protected $fillable = [
        'user_id',
        'notif_web',
        'notif_email',
        'notif_whatsapp',
        'notif_push',
        'notif_jadwal_mengajar',
        'notif_absensi_berhasil',
        'notif_izin_cuti',
        'notif_approval',
        'notif_peringatan',
        'notif_broadcast',
        'quiet_time_start',
        'quiet_time_end',
    ];

    protected $casts = [
        'notif_web' => 'boolean',
        'notif_email' => 'boolean',
        'notif_whatsapp' => 'boolean',
        'notif_push' => 'boolean',
        'notif_jadwal_mengajar' => 'boolean',
        'notif_absensi_berhasil' => 'boolean',
        'notif_izin_cuti' => 'boolean',
        'notif_approval' => 'boolean',
        'notif_peringatan' => 'boolean',
        'notif_broadcast' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helpers
     */
    public function isQuietTime()
    {
        if (!$this->quiet_time_start || !$this->quiet_time_end) {
            return false;
        }

        $now = now()->format('H:i:s');
        return $now >= $this->quiet_time_start && $now <= $this->quiet_time_end;
    }

    public function canReceiveNotification($channel, $event)
    {
        // Check quiet time
        if ($this->isQuietTime()) {
            return false;
        }

        // Check channel preference
        $channelKey = 'notif_' . $channel;
        if (!$this->{$channelKey}) {
            return false;
        }

        // Check event preference
        $eventKey = 'notif_' . $event;
        if (isset($this->{$eventKey}) && !$this->{$eventKey}) {
            return false;
        }

        return true;
    }
}
