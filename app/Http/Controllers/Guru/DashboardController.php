<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\IzinCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the guru dashboard.
     */
    public function index()
    {
        $guru = auth()->user()->guru;
        $today = Carbon::today();
        $now = Carbon::now();

        // Greeting based on time
        $hour = $now->hour;
        if ($hour < 11) {
            $greeting = 'Pagi';
        } elseif ($hour < 15) {
            $greeting = 'Siang';
        } elseif ($hour < 18) {
            $greeting = 'Sore';
        } else {
            $greeting = 'Malam';
        }

        // Check if already absent today
        $absensi_hari_ini = Absensi::where('guru_id', $guru->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Monthly Statistics
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $hadir = Absensi::where('guru_id', $guru->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereIn('status_kehadiran', ['Hadir', 'Terlambat'])
            ->count();

        $izin = Absensi::where('guru_id', $guru->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereIn('status_kehadiran', ['Izin', 'Sakit', 'Cuti', 'Dinas Luar'])
            ->count();

        $terlambat = Absensi::where('guru_id', $guru->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('status_kehadiran', 'Terlambat')
            ->count();

        $alpha = Absensi::where('guru_id', $guru->id)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('status_kehadiran', 'Alpha')
            ->count();

        $total_hari_kerja = $hadir + $izin + $alpha;
        $persentase = $total_hari_kerja > 0
            ? round(($hadir / $total_hari_kerja) * 100, 1)
            : 0;

        $stats = [
            'hadir' => $hadir,
            'izin' => $izin,
            'terlambat' => $terlambat,
            'alpha' => $alpha,
            'persentase' => $persentase
        ];

        // Today's Schedule
        $dayOfWeek = $today->dayOfWeekIso; // 1 = Monday, 7 = Sunday
        $jadwal_hari_ini = Jadwal::with(['mataPelajaran', 'kelas'])
            ->where('guru_id', $guru->id)
            ->where('hari', $dayOfWeek)
            ->where('status', 'Aktif')
            ->orderBy('jam_mulai')
            ->get();

        // Recent Attendance History (Last 7 days)
        $riwayat_absensi = Absensi::where('guru_id', $guru->id)
            ->whereDate('tanggal', '>=', Carbon::today()->subDays(6))
            ->orderBy('tanggal', 'desc')
            ->limit(7)
            ->get();

        // Unread Notifications Count
        $unread_notifications = 0; // TODO: Implement notifications
        $notifications = []; // TODO: Implement notifications

        return view('guru.dashboard', compact(
            'greeting',
            'absensi_hari_ini',
            'stats',
            'jadwal_hari_ini',
            'riwayat_absensi',
            'unread_notifications',
            'notifications'
        ));
    }
}
