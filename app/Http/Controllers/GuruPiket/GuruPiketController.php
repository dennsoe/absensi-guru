<?php

namespace App\Http\Controllers\GuruPiket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Absensi, Guru, JadwalMengajar, IzinCuti, GuruPengganti};
use Illuminate\Support\Facades\Auth;

class GuruPiketController extends Controller
{
    /**
     * Dashboard Guru Piket
     */
    public function dashboard()
    {
        $data = [
            'guru_hadir' => Absensi::whereDate('tanggal', today())
                                   ->where('status_kehadiran', 'hadir')
                                   ->with('guru')
                                   ->get(),
            'guru_belum_absen' => Guru::whereDoesntHave('absensi', function($q) {
                                       $q->whereDate('tanggal', today());
                                   })
                                   ->where('status', 'aktif')
                                   ->get(),
            'guru_terlambat' => Absensi::whereDate('tanggal', today())
                                       ->where('status_keterlambatan', 'terlambat')
                                       ->with('guru')
                                       ->get(),
            'guru_izin' => IzinCuti::whereDate('tanggal_mulai', '<=', today())
                                   ->whereDate('tanggal_selesai', '>=', today())
                                   ->where('status', 'disetujui')
                                   ->with('guru')
                                   ->get(),
            'total_guru' => Guru::where('status', 'aktif')->count(),
        ];

        return view('piket.dashboard', $data);
    }

    /**
     * Monitoring Absensi Real-time
     */
    public function monitoringAbsensi()
    {
        $absensi = Absensi::whereDate('tanggal', today())
                          ->with(['guru', 'jadwalMengajar.kelas', 'jadwalMengajar.mataPelajaran'])
                          ->latest('waktu_masuk')
                          ->get();

        return view('piket.monitoring', compact('absensi'));
    }

    /**
     * Input Absensi Manual (untuk guru yang tidak bisa scan QR)
     */
    public function inputAbsensiManual()
    {
        $guru = Guru::where('status', 'aktif')
                    ->whereDoesntHave('absensi', function($q) {
                        $q->whereDate('tanggal', today());
                    })
                    ->get();

        $jadwal = JadwalMengajar::where('hari', now()->locale('id')->dayName)
                                ->where('status', 'aktif')
                                ->with(['guru', 'kelas', 'mataPelajaran'])
                                ->get();

        return view('piket.absensi-manual', compact('guru', 'jadwal'));
    }

    public function storeAbsensiManual(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'jadwal_mengajar_id' => 'required|exists:jadwal_mengajar,id',
            'status_kehadiran' => 'required|in:hadir,izin,sakit,alpha,dinas_luar',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $validated['tanggal'] = today();
        $validated['waktu_masuk'] = now()->format('H:i:s');
        $validated['metode_absensi'] = 'manual';
        $validated['dibuat_oleh'] = Auth::id();

        Absensi::create($validated);

        return redirect()->route('piket.monitoring')
                        ->with('success', 'Absensi manual berhasil dicatat.');
    }
}
