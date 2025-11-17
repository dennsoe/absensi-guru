<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BroadcastMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'broadcast_message';

    protected $fillable = [
        'judul',
        'isi_pesan',
        'tipe',
        'target_role',
        'target_users',
        'status',
        'jadwal_kirim',
        'tanggal_kirim',
        'pengirim_id',
        'total_penerima',
        'total_dibaca',
        'push_notification',
        'email_notification',
    ];

    protected $casts = [
        'target_role' => 'array',
        'target_users' => 'array',
        'jadwal_kirim' => 'datetime',
        'tanggal_kirim' => 'datetime',
        'total_penerima' => 'integer',
        'total_dibaca' => 'integer',
        'push_notification' => 'boolean',
        'email_notification' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    /**
     * Scopes
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Accessors
     */
    public function getPersentaseDibacaAttribute()
    {
        return $this->total_penerima > 0
            ? round(($this->total_dibaca / $this->total_penerima) * 100, 2)
            : 0;
    }
}
