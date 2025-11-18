<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;
    protected $type;

    public function __construct($data, $type)
    {
        $this->data = collect($data);
        $this->type = $type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        switch ($this->type) {
            case 'per_guru':
                return [
                    'Tanggal',
                    'Hari',
                    'Mata Pelajaran',
                    'Kelas',
                    'Jam Masuk',
                    'Status Kehadiran',
                    'Waktu Terlambat (menit)',
                    'Metode Absensi',
                ];

            case 'per_kelas':
                return [
                    'Nama Guru',
                    'Mata Pelajaran',
                    'Total Pertemuan',
                    'Hadir',
                    'Terlambat',
                    'Izin',
                    'Alpha',
                    'Persentase Kehadiran',
                ];

            case 'rekap_bulanan':
                return [
                    'No',
                    'Nama Guru',
                    'NIP',
                    'Total',
                    'Hadir',
                    'Terlambat',
                    'Izin',
                    'Sakit',
                    'Cuti',
                    'Alpha',
                    'Persentase',
                ];

            case 'keterlambatan':
                return [
                    'No',
                    'Nama Guru',
                    'NIP',
                    'Total Terlambat',
                    'Rata-rata Durasi (menit)',
                    'Terlambat Terbanyak',
                ];

            default:
                return [];
        }
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        switch ($this->type) {
            case 'per_guru':
                return [
                    $row->tanggal->format('d/m/Y'),
                    ucfirst($row->tanggal->locale('id')->dayName),
                    $row->jadwalMengajar->mataPelajaran->nama_mapel ?? '-',
                    $row->jadwalMengajar->kelas->nama_kelas ?? '-',
                    $row->jam_masuk ? $row->jam_masuk->format('H:i') : '-',
                    ucfirst($row->status_kehadiran),
                    $row->waktu_terlambat ?? 0,
                    ucfirst($row->metode_absensi ?? '-'),
                ];

            case 'per_kelas':
                return [
                    $row['guru']->nama,
                    $row['mata_pelajaran'],
                    $row['total'],
                    $row['hadir'],
                    $row['terlambat'],
                    $row['izin'],
                    $row['alpha'],
                    $row['persentase'] . '%',
                ];

            case 'rekap_bulanan':
                return [
                    $row['no'],
                    $row['guru']->nama,
                    $row['guru']->nip ?? '-',
                    $row['total'],
                    $row['hadir'],
                    $row['terlambat'],
                    $row['izin'],
                    $row['sakit'],
                    $row['cuti'],
                    $row['alpha'],
                    $row['persentase'] . '%',
                ];

            case 'keterlambatan':
                return [
                    $row['no'],
                    $row['guru']->nama,
                    $row['guru']->nip ?? '-',
                    $row['total_terlambat'],
                    $row['rata_rata_durasi'],
                    $row['tanggal_terbanyak'],
                ];

            default:
                return [];
        }
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (header)
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A90E2'],
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
            ],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return match($this->type) {
            'per_guru' => 'Laporan Per Guru',
            'per_kelas' => 'Laporan Per Kelas',
            'rekap_bulanan' => 'Rekap Bulanan',
            'keterlambatan' => 'Laporan Keterlambatan',
            default => 'Laporan',
        };
    }
}
