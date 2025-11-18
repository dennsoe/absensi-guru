<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Libur;
use Illuminate\Support\Facades\{Auth, Log};
use Carbon\Carbon;

class KalenderLiburController extends Controller
{
    /**
     * Display listing of holidays
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak.');
        }

        $query = Libur::query();

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter by bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        } else {
            // Default: current year
            $query->whereYear('tanggal', date('Y'));
        }

        $liburList = $query->orderBy('tanggal', 'desc')->paginate(20);

        // Statistics
        $totalLibur = Libur::whereYear('tanggal', date('Y'))->count();
        $liburNasional = Libur::where('jenis', 'nasional')->whereYear('tanggal', date('Y'))->count();
        $liburSekolah = Libur::where('jenis', 'sekolah')->whereYear('tanggal', date('Y'))->count();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $liburBulanIni = Libur::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();

        return view('admin.kalender-libur.index', compact(
            'liburList',
            'totalLibur',
            'liburNasional',
            'liburSekolah',
            'liburBulanIni'
        ));
    }

    /**
     * Store new holiday
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:nasional,sekolah,cuti_bersama',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                return redirect()->route('dashboard')
                    ->with('error', 'Akses ditolak.');
            }

            // Check if holiday already exists on this date
            $exists = Libur::where('tanggal', $request->tanggal)
                ->where('nama', $request->nama)
                ->exists();

            if ($exists) {
                return back()->with('error', 'Hari libur dengan nama dan tanggal yang sama sudah ada.');
            }

            Libur::create([
                'nama' => $request->nama,
                'tanggal' => $request->tanggal,
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('admin.kalender-libur.index')
                ->with('success', 'Berhasil menambahkan hari libur: ' . $request->nama);

        } catch (\Exception $e) {
            Log::error('Error storing libur: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Update holiday
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:200',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:nasional,sekolah,cuti_bersama',
            'keterangan' => 'nullable|string|max:500',
        ]);

        try {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                return redirect()->route('dashboard')
                    ->with('error', 'Akses ditolak.');
            }

            $libur = Libur::findOrFail($id);

            $libur->update([
                'nama' => $request->nama,
                'tanggal' => $request->tanggal,
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('admin.kalender-libur.index')
                ->with('success', 'Berhasil mengupdate hari libur: ' . $request->nama);

        } catch (\Exception $e) {
            Log::error('Error updating libur: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    /**
     * Delete holiday
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                return redirect()->route('dashboard')
                    ->with('error', 'Akses ditolak.');
            }

            $libur = Libur::findOrFail($id);
            $namaLibur = $libur->nama;
            $libur->delete();

            return redirect()->route('admin.kalender-libur.index')
                ->with('success', 'Berhasil menghapus hari libur: ' . $namaLibur);

        } catch (\Exception $e) {
            Log::error('Error deleting libur: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
