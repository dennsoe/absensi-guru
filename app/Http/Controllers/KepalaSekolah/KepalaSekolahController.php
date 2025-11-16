<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Absensi, Guru, IzinCuti, Pelanggaran, Laporan};
use Illuminate\Support\Facades\DB;

class KepalaSekolahController extends Controller
{
    /**
     * Dashboard Kepala Sekolah
     */
    public function dashboard()
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $data = [
            'total_guru' => Guru::count(),
            'statistik_kehadiran' => [
                'hadir' => Absensi::whereMonth('tanggal', $bulanIni)
                                  ->where('status_kehadiran', 'hadir')
                                  ->count(),
                'izin' => Absensi::whereMonth('tanggal', $bulanIni)
                                 ->whereIn('status_kehadiran', ['izin', 'sakit'])
                                 ->count(),
                'alpha' => Absensi::whereMonth('tanggal', $bulanIni)
                                  ->where('status_kehadiran', 'alpha')
                                  ->count(),
                'terlambat' => Absensi::whereMonth('tanggal', $bulanIni)
                                      ->where('status_keterlambatan', 'terlambat')
                                      ->count(),
            ],
            'izin_perlu_approval' => IzinCuti::where('status', 'pending')
                                             ->with('guru')
                                             ->latest()
                                             ->get(),
            'pelanggaran_bulan_ini' => Pelanggaran::whereMonth('tanggal', $bulanIni)
                                                   ->with('guru')
                                                   ->latest()
                                                   ->limit(10)
                                                   ->get(),
            'guru_terlambat_sering' => Absensi::select('guru_id', DB::raw('COUNT(*) as total_terlambat'))
                                              ->whereMonth('tanggal', $bulanIni)
                                              ->where('status_keterlambatan', 'terlambat')
                                              ->groupBy('guru_id')
                                              ->having('total_terlambat', '>=', 3)
                                              ->with('guru')
                                              ->get(),
        ];

        return view('kepsek.dashboard', $data);
    }

    /**
     * Approval Izin/Cuti
     */
    public function izinCuti()
    {
        $izin = IzinCuti::with('guru')->latest()->paginate(20);
        return view('kepsek.izin-cuti.index', compact('izin'));
    }

    public function approveIzin(IzinCuti $izin)
    {
        $izin->update([
            'status' => 'disetujui',
            'disetujui_oleh' => auth()->id(),
            'tanggal_disetujui' => now(),
        ]);

        return back()->with('success', 'Izin/Cuti berhasil disetujui.');
    }

    public function rejectIzin(Request $request, IzinCuti $izin)
    {
        $validated = $request->validate([
            'alasan_ditolak' => 'required|string|max:500',
        ]);

        $izin->update([
            'status' => 'ditolak',
            'disetujui_oleh' => auth()->id(),
            'tanggal_disetujui' => now(),
            'keterangan' => $validated['alasan_ditolak'],
        ]);

        return back()->with('success', 'Izin/Cuti berhasil ditolak.');
    }

    /**
     * Laporan Kedisiplinan
     */
    public function laporanKedisiplinan(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $laporan = Absensi::select(
                        'guru_id',
                        DB::raw('COUNT(*) as total_absensi'),
                        DB::raw('SUM(CASE WHEN status_kehadiran = "hadir" THEN 1 ELSE 0 END) as total_hadir'),
                        DB::raw('SUM(CASE WHEN status_keterlambatan = "terlambat" THEN 1 ELSE 0 END) as total_terlambat'),
                        DB::raw('SUM(CASE WHEN status_kehadiran = "alpha" THEN 1 ELSE 0 END) as total_alpha')
                    )
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->groupBy('guru_id')
                    ->with('guru')
                    ->get();

        return view('kepsek.laporan.kedisiplinan', compact('laporan', 'bulan', 'tahun'));
    }
}
