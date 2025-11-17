<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratPeringatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surat_peringatan';

    protected $fillable = [
        'guru_id',
        'jenis',
        'tanggal_surat',
        'nomor_surat',
        'alasan',
        'jumlah_pelanggaran',
        'periode_mulai',
        'periode_selesai',
        'file_pdf',
        'status',
        'catatan',
        'dibuat_oleh',
        'tanggal_diterima',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'tanggal_diterima' => 'datetime',
        'jumlah_pelanggaran' => 'integer',
    ];

    /**
     * Relationships
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function pembuatSurat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Scopes
     */
    public function scopeTerbit($query)
    {
        return $query->where('status', 'terbit');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    /**
     * Accessors
     */
    public function getFilePdfUrlAttribute()
    {
        return $this->file_pdf ? asset('storage/' . $this->file_pdf) : null;
    }
}
