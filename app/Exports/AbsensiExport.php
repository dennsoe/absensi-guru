<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $bulan;
    protected $tahun;
    protected $guruId;

    public function __construct($bulan, $tahun, $guruId = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->guruId = $guruId;
    }

    /**
     * Query data rekap absensi
     */
    public function collection()
    {
        $query = Absensi::select(
                        'guru_id',
                        DB::raw('COUNT(*) as total_absensi'),
                        DB::raw('SUM(CASE WHEN status_kehadiran = "hadir" THEN 1 ELSE 0 END) as hadir'),
                        DB::raw('SUM(CASE WHEN status_kehadiran = "izin" THEN 1 ELSE 0 END) as izin'),
                        DB::raw('SUM(CASE WHEN status_kehadiran = "sakit" THEN 1 ELSE 0 END) as sakit'),
                        DB::raw('SUM(CASE WHEN status_kehadiran = "alpha" THEN 1 ELSE 0 END) as alpha'),
                        DB::raw('SUM(CASE WHEN status_kehadiran = "dinas_luar" THEN 1 ELSE 0 END) as dinas_luar'),
                        DB::raw('SUM(CASE WHEN status_keterlambatan = "terlambat" THEN 1 ELSE 0 END) as terlambat'),
                        DB::raw('SUM(menit_terlambat) as total_menit_terlambat')
                    )
                    ->whereMonth('tanggal', $this->bulan)
                    ->whereYear('tanggal', $this->tahun)
                    ->groupBy('guru_id')
                    ->with('guru');

        if ($this->guruId) {
            $query->where('guru_id', $this->guruId);
        }

        return $query->get();
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'Nama Guru',
            'Total Absensi',
            'Hadir',
            'Izin',
            'Sakit',
            'Alpha',
            'Dinas Luar',
            'Terlambat',
            'Total Menit Terlambat',
            'Persentase Kehadiran',
        ];
    }

    /**
     * Mapping data ke kolom Excel
     */
    public function map($absensi): array
    {
        static $no = 0;
        $no++;

        $persentase = $absensi->total_absensi > 0
                     ? round(($absensi->hadir / $absensi->total_absensi) * 100, 2)
                     : 0;

        return [
            $no,
            $absensi->guru->nip ?? '-',
            $absensi->guru->nama ?? '-',
            $absensi->total_absensi,
            $absensi->hadir,
            $absensi->izin,
            $absensi->sakit,
            $absensi->alpha,
            $absensi->dinas_luar,
            $absensi->terlambat,
            $absensi->total_menit_terlambat,
            $persentase . '%',
        ];
    }

    /**
     * Styling Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
