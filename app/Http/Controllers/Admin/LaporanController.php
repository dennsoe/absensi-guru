<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Absensi, Guru, Kelas, JadwalMengajar};
use App\Services\LaporanService;
use Carbon\Carbon;

class LaporanController extends Controller
{
    protected $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }
    /**
     * Display laporan absensi
     */
    public function index(Request $request)
    {
        // Default periode: bulan ini
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $query = Absensi::with(['guru', 'jadwal.kelas', 'jadwal.mataPelajaran'])
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan);

        // Filter by guru
        if ($request->filled('guru_id')) {
            $query->whereHas('guru', function($q) use ($request) {
                $q->where('id', $request->guru_id);
            });
        }

        // Filter by kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('jadwal', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        // Filter by status kehadiran
        if ($request->filled('status')) {
            $query->where('status_kehadiran', $request->status);
        }

        $absensi_list = $query->orderBy('tanggal', 'desc')
                              ->orderBy('jam_masuk', 'desc')
                              ->paginate(50)
                              ->withQueryString();

        // Statistics
        $stats = [
            'total' => Absensi::whereYear('tanggal', $tahun)
                              ->whereMonth('tanggal', $bulan)
                              ->count(),
            'hadir' => Absensi::whereYear('tanggal', $tahun)
                              ->whereMonth('tanggal', $bulan)
                              ->where('status_kehadiran', 'hadir')
                              ->count(),
            'terlambat' => Absensi::whereYear('tanggal', $tahun)
                                  ->whereMonth('tanggal', $bulan)
                                  ->where('status_kehadiran', 'terlambat')
                                  ->count(),
            'izin' => Absensi::whereYear('tanggal', $tahun)
                             ->whereMonth('tanggal', $bulan)
                             ->whereIn('status_kehadiran', ['izin', 'sakit', 'cuti', 'dinas'])
                             ->count(),
            'alpha' => Absensi::whereYear('tanggal', $tahun)
                              ->whereMonth('tanggal', $bulan)
                              ->where('status_kehadiran', 'alpha')
                              ->count(),
        ];

        $guru_list = Guru::all();
        $kelas_list = Kelas::all();

        return view('admin.laporan.index', compact('absensi_list', 'stats', 'guru_list', 'kelas_list', 'bulan', 'tahun'));
    }

    /**
     * Export laporan per guru
     */
    public function perGuru(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $guru_id = $request->input('guru_id');

        if (!$guru_id) {
            return redirect()->route('admin.laporan.index')->with('error', 'Pilih guru terlebih dahulu.');
        }

        $guru = Guru::findOrFail($guru_id);

        $absensi_list = Absensi::with(['jadwal.kelas', 'jadwal.mataPelajaran'])
            ->whereHas('guru', function($q) use ($guru_id) {
                $q->where('id', $guru_id);
            })
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->get();

        $stats = [
            'total' => $absensi_list->count(),
            'hadir' => $absensi_list->where('status_kehadiran', 'hadir')->count(),
            'terlambat' => $absensi_list->where('status_kehadiran', 'terlambat')->count(),
            'izin' => $absensi_list->whereIn('status_kehadiran', ['izin', 'sakit', 'cuti', 'dinas'])->count(),
            'alpha' => $absensi_list->where('status_kehadiran', 'alpha')->count(),
        ];

        return view('admin.laporan.per-guru', compact('guru', 'absensi_list', 'stats', 'bulan', 'tahun'));
    }

    /**
     * Export laporan per kelas
     */
    public function perKelas(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $kelas_id = $request->input('kelas_id');

        if (!$kelas_id) {
            return redirect()->route('admin.laporan.index')->with('error', 'Pilih kelas terlebih dahulu.');
        }

        $kelas = Kelas::with('waliKelas')->findOrFail($kelas_id);

        $absensi_list = Absensi::with(['guru', 'jadwal.mataPelajaran'])
            ->whereHas('jadwal', function($q) use ($kelas_id) {
                $q->where('kelas_id', $kelas_id);
            })
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->get();

        // Group by guru nama untuk view
        $absensis_by_guru = $absensi_list->groupBy(function($item) {
            return $item->guru->nama;
        });

        // Statistik per guru
        $by_guru = $absensi_list->groupBy(function($item) {
            return $item->guru->id;
        })->map(function($items, $guru_id) {
            $guru = $items->first()->guru;
            return [
                'guru' => $guru,
                'total' => $items->count(),
                'hadir' => $items->where('status_kehadiran', 'hadir')->count(),
                'terlambat' => $items->where('status_kehadiran', 'terlambat')->count(),
                'izin' => $items->whereIn('status_kehadiran', ['izin', 'sakit', 'cuti', 'dinas'])->count(),
                'alpha' => $items->where('status_kehadiran', 'alpha')->count(),
            ];
        });

        return view('admin.laporan.per-kelas', compact('kelas', 'absensi_list', 'absensis_by_guru', 'by_guru', 'bulan', 'tahun'));
    }

    /**
     * Export PDF - Per Guru
     */
    public function exportPdfPerGuru(Request $request)
    {
        $guruId = $request->input('guru_id');
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        if (!$guruId) {
            return redirect()->back()->with('error', 'Pilih guru terlebih dahulu.');
        }

        return $this->laporanService->generateLaporanPerGuru($guruId, $bulan, $tahun, 'pdf');
    }

    /**
     * Export PDF - Per Kelas
     */
    public function exportPdfPerKelas(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        if (!$kelasId) {
            return redirect()->back()->with('error', 'Pilih kelas terlebih dahulu.');
        }

        return $this->laporanService->generateLaporanPerKelas($kelasId, $bulan, $tahun, 'pdf');
    }

    /**
     * Export PDF - Rekap Bulanan
     */
    public function exportPdfRekapBulanan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        return $this->laporanService->generateLaporanRekapBulanan($bulan, $tahun, 'pdf');
    }

    /**
     * Export PDF - Keterlambatan
     */
    public function exportPdfKeterlambatan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        return $this->laporanService->generateLaporanKeterlambatan($bulan, $tahun, 'pdf');
    }

    /**
     * Export Excel - Per Guru
     */
    public function exportExcelPerGuru(Request $request)
    {
        $guruId = $request->input('guru_id');
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        if (!$guruId) {
            return redirect()->back()->with('error', 'Pilih guru terlebih dahulu.');
        }

        return $this->laporanService->generateLaporanPerGuru($guruId, $bulan, $tahun, 'excel');
    }

    /**
     * Export Excel - Per Kelas
     */
    public function exportExcelPerKelas(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        if (!$kelasId) {
            return redirect()->back()->with('error', 'Pilih kelas terlebih dahulu.');
        }

        return $this->laporanService->generateLaporanPerKelas($kelasId, $bulan, $tahun, 'excel');
    }

    /**
     * Export Excel - Rekap Bulanan
     */
    public function exportExcelRekapBulanan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        return $this->laporanService->generateLaporanRekapBulanan($bulan, $tahun, 'excel');
    }

    /**
     * Export Excel - Keterlambatan
     */
    public function exportExcelKeterlambatan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        return $this->laporanService->generateLaporanKeterlambatan($bulan, $tahun, 'excel');
    }

    /**
     * View Laporan Keterlambatan
     */
    public function keterlambatan(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', now()->format('Y-m-d'));
        $guruId = $request->input('guru_id');

        $query = Absensi::with(['guru', 'jadwal.kelas', 'jadwal.mataPelajaran'])
            ->where('status_kehadiran', 'terlambat')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);

        if ($guruId) {
            $query->where('guru_id', $guruId);
        }

        $keterlambatan_list = $query->orderBy('tanggal', 'desc')
                                   ->orderBy('jam_masuk', 'desc')
                                   ->paginate(50)
                                   ->withQueryString();

        // Statistics
        $total_terlambat = $keterlambatan_list->total();
        $jumlah_guru = $query->distinct('guru_id')->count('guru_id');
        $rata_rata_terlambat = $keterlambatan_list->avg('waktu_terlambat_menit') ?? 0;
        $terlambat_parah = $keterlambatan_list->where('waktu_terlambat_menit', '>', 30)->count();

        // Top guru terlambat
        $top_guru_terlambat = Absensi::selectRaw('guru_id, COUNT(*) as jumlah_terlambat, SUM(waktu_terlambat_menit) as total_durasi, AVG(waktu_terlambat_menit) as rata_rata_durasi')
            ->where('status_kehadiran', 'terlambat')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->groupBy('guru_id')
            ->orderByDesc('jumlah_terlambat')
            ->limit(10)
            ->with('guru')
            ->get()
            ->map(function($item) {
                return (object) [
                    'nama' => $item->guru->nama,
                    'nip' => $item->guru->nip,
                    'jumlah_terlambat' => $item->jumlah_terlambat,
                    'total_durasi' => $item->total_durasi,
                    'rata_rata_durasi' => $item->rata_rata_durasi,
                ];
            });

        $guru_list = Guru::all();

        return view('admin.laporan.keterlambatan', compact(
            'keterlambatan_list',
            'total_terlambat',
            'jumlah_guru',
            'rata_rata_terlambat',
            'terlambat_parah',
            'top_guru_terlambat',
            'guru_list'
        ));
    }
}
