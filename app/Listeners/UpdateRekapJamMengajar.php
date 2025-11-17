<?php

namespace App\Listeners;

use App\Events\AbsensiCreated;
use App\Models\RekapJamMengajar;
use App\Models\JadwalMengajar;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateRekapJamMengajar implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(AbsensiCreated $event): void
    {
        try {
            $absensi = $event->absensi;
            $guru = $absensi->guru;
            $tanggal = $absensi->tanggal;

            $bulan = $tanggal->month;
            $tahun = $tanggal->year;

            // Get or create rekap for this month
            $rekap = RekapJamMengajar::firstOrCreate(
                [
                    'guru_id' => $guru->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ],
                [
                    'total_jam_mengajar' => 0,
                    'total_hadir' => 0,
                    'total_alpha' => 0,
                    'total_izin' => 0,
                    'total_sakit' => 0,
                    'total_terlambat' => 0,
                ]
            );

            // Calculate jam mengajar for this day if hadir
            if (in_array($absensi->status, ['hadir', 'terlambat'])) {
                $dayName = $tanggal->locale('id')->dayName;

                $jadwalHariIni = JadwalMengajar::where('guru_id', $guru->id)
                    ->where('hari', $dayName)
                    ->where('is_active', true)
                    ->get();

                $totalJamHariIni = 0;
                foreach ($jadwalHariIni as $jadwal) {
                    $durasi = \Carbon\Carbon::parse($jadwal->jam_selesai)
                        ->diffInMinutes(\Carbon\Carbon::parse($jadwal->jam_mulai));
                    $totalJamHariIni += $durasi / 60; // Convert to hours
                }

                $rekap->increment('total_jam_mengajar', $totalJamHariIni);
            }

            // Update attendance counts
            switch ($absensi->status) {
                case 'hadir':
                    $rekap->increment('total_hadir');
                    break;
                case 'alpha':
                    $rekap->increment('total_alpha');
                    break;
                case 'izin':
                    $rekap->increment('total_izin');
                    break;
                case 'sakit':
                    $rekap->increment('total_sakit');
                    break;
                case 'terlambat':
                    $rekap->increment('total_terlambat');
                    $rekap->increment('total_hadir');
                    break;
            }

            Log::info("UpdateRekapJamMengajar: Updated rekap for Guru ID {$guru->id}, {$bulan}/{$tahun}");

        } catch (\Exception $e) {
            Log::error('UpdateRekapJamMengajar failed: ' . $e->getMessage());
        }
    }
}
