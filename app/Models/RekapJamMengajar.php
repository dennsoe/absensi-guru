<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapJamMengajar extends Model
{
    use HasFactory;

    protected $table = 'rekap_jam_mengajar';

    protected $fillable = [
        'guru_id',
        'tahun',
        'bulan',
        'total_jadwal',
        'total_hadir',
        'total_izin',
        'total_sakit',
        'total_alfa',
        'total_terlambat',
        'jam_mengajar_seharusnya',
        'jam_mengajar_terealisasi',
        'persentase_kehadiran',
        'total_tepat_waktu',
        'rata_rata_keterlambatan',
        'status',
        'finalized_at',
        'finalized_by',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'total_jadwal' => 'integer',
        'total_hadir' => 'integer',
        'total_izin' => 'integer',
        'total_sakit' => 'integer',
        'total_alfa' => 'integer',
        'total_terlambat' => 'integer',
        'jam_mengajar_seharusnya' => 'decimal:2',
        'jam_mengajar_terealisasi' => 'decimal:2',
        'persentase_kehadiran' => 'decimal:2',
        'total_tepat_waktu' => 'integer',
        'rata_rata_keterlambatan' => 'decimal:2',
        'finalized_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    /**
     * Scopes
     */
    public function scopeFinal($query)
    {
        return $query->where('status', 'final');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    public function scopeByPeriode($query, $tahun, $bulan)
    {
        return $query->where('tahun', $tahun)
                     ->where('bulan', $bulan);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    /**
     * Accessors
     */
    public function getPeriodeTextAttribute()
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $bulanNama[$this->bulan] . ' ' . $this->tahun;
    }

    public function getPerformaAttribute()
    {
        if ($this->persentase_kehadiran >= 95) {
            return 'Sangat Baik';
        } elseif ($this->persentase_kehadiran >= 85) {
            return 'Baik';
        } elseif ($this->persentase_kehadiran >= 75) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }

    /**
     * Helpers
     */
    public function finalize($userId)
    {
        $this->update([
            'status' => 'final',
            'finalized_at' => now(),
            'finalized_by' => $userId,
        ]);
    }

    public function calculatePersentaseKehadiran()
    {
        if ($this->total_jadwal == 0) {
            return 0;
        }

        return round(($this->total_hadir / $this->total_jadwal) * 100, 2);
    }
}
