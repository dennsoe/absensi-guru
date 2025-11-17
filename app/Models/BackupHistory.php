<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupHistory extends Model
{
    use HasFactory;

    protected $table = 'backup_history';

    protected $fillable = [
        'backup_type',
        'filename',
        'file_path',
        'file_size',
        'status',
        'started_at',
        'completed_at',
        'duration_seconds',
        'error_message',
        'backup_info',
        'storage_location',
        'triggered_by',
        'is_downloadable',
        'delete_after',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_seconds' => 'integer',
        'backup_info' => 'array',
        'is_downloadable' => 'boolean',
        'delete_after' => 'date',
    ];

    /**
     * Relationships
     */
    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    /**
     * Scopes
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeDownloadable($query)
    {
        return $query->where('is_downloadable', true)
                     ->where('status', 'completed');
    }

    public function scopeShouldBeDeleted($query)
    {
        return $query->whereNotNull('delete_after')
                     ->where('delete_after', '<=', now());
    }

    /**
     * Accessors
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDurationHumanAttribute()
    {
        if (!$this->duration_seconds) {
            return '-';
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return $minutes > 0
            ? "{$minutes}m {$seconds}s"
            : "{$seconds}s";
    }

    public function getDownloadUrlAttribute()
    {
        return $this->is_downloadable && $this->status === 'completed'
            ? route('admin.backup.download', $this->id)
            : null;
    }
}
