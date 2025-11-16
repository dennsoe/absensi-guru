<?php

namespace App\Http\Controllers\Kurikulum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{JadwalMengajar, Guru, Kelas, MataPelajaran};
use Illuminate\Support\Facades\DB;

class KurikulumController extends Controller
{
    /**
     * Dashboard Kurikulum
     */
    public function dashboard()
    {
        $data = [
            'total_jadwal_aktif' => JadwalMengajar::where('status', 'aktif')->count(),
            'total_kelas' => Kelas::count(),
            'total_mapel' => MataPelajaran::count(),
            'jadwal_konflik' => $this->getJadwalKonflik(),
            'guru_tanpa_jadwal' => Guru::whereDoesntHave('jadwalMengajar', function($q) {
                                       $q->where('status', 'aktif');
                                   })
                                   ->where('status', 'aktif')
                                   ->get(),
        ];

        return view('kurikulum.dashboard', $data);
    }

    /**
     * Deteksi jadwal yang konflik (guru mengajar di waktu sama)
     */
    private function getJadwalKonflik()
    {
        return JadwalMengajar::select('guru_id', 'hari', 'jam_mulai', 'jam_selesai')
                            ->selectRaw('COUNT(*) as jumlah')
                            ->where('status', 'aktif')
                            ->groupBy('guru_id', 'hari', 'jam_mulai', 'jam_selesai')
                            ->having('jumlah', '>', 1)
                            ->with('guru')
                            ->get();
    }

    /**
     * Kelola Jadwal Mengajar
     */
    public function jadwal()
    {
        $jadwal = JadwalMengajar::with(['guru', 'kelas', 'mataPelajaran'])
                                ->latest()
                                ->paginate(50);
        return view('kurikulum.jadwal.index', compact('jadwal'));
    }

    public function createJadwal()
    {
        $guru = Guru::where('status', 'aktif')->get();
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();

        return view('kurikulum.jadwal.create', compact('guru', 'kelas', 'mapel'));
    }

    public function storeJadwal(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'ruangan' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Cek konflik jadwal
        $konflik = JadwalMengajar::where('guru_id', $validated['guru_id'])
                                 ->where('hari', $validated['hari'])
                                 ->where('status', 'aktif')
                                 ->where(function($q) use ($validated) {
                                     $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                                       ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']]);
                                 })
                                 ->exists();

        if ($konflik) {
            return back()->withErrors(['hari' => 'Guru sudah memiliki jadwal di waktu yang sama.'])
                        ->withInput();
        }

        JadwalMengajar::create($validated);

        return redirect()->route('kurikulum.jadwal')
                        ->with('success', 'Jadwal mengajar berhasil ditambahkan.');
    }

    public function editJadwal(JadwalMengajar $jadwal)
    {
        $guru = Guru::where('status', 'aktif')->get();
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();

        return view('kurikulum.jadwal.edit', compact('jadwal', 'guru', 'kelas', 'mapel'));
    }

    public function updateJadwal(Request $request, JadwalMengajar $jadwal)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'ruangan' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jadwal->update($validated);

        return redirect()->route('kurikulum.jadwal')
                        ->with('success', 'Jadwal mengajar berhasil diupdate.');
    }

    public function destroyJadwal(JadwalMengajar $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('kurikulum.jadwal')
                        ->with('success', 'Jadwal mengajar berhasil dihapus.');
    }
}
