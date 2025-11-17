<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\JadwalMengajar;
use App\Models\Libur;
use Carbon\Carbon;

class AbsensiService
{
    /**
     * Determine attendance status based on time
     */
    public function determineStatus(?Carbon $checkIn = null): string
    {
        $checkIn = $checkIn ?? Carbon::now();
        $time = $checkIn->format('H:i:s');

        $tepat = config('absensi.jam_masuk_akhir', '07:00:00');
        $terlambat = config('absensi.jam_terlambat_akhir', '08:00:00');

        if ($time <= $tepat) {
            return 'hadir';
        }

        if ($time <= $terlambat) {
            return 'terlambat';
        }

        return 'alpha';
    }

    /**
     * Calculate late minutes
     */
    public function calculateLateMinutes(Carbon $checkIn): int
    {
        $jamMasuk = Carbon::parse($checkIn->format('Y-m-d') . ' ' . config('absensi.jam_masuk_akhir', '07:00:00'));

        if ($checkIn->lte($jamMasuk)) {
            return 0;
        }

        return $checkIn->diffInMinutes($jamMasuk);
    }

    /**
     * Check if today is a working day
     */
    public function isWorkingDay(?Carbon $date = null): bool
    {
        $date = $date ?? Carbon::now();

        // Check weekend
        if ($date->isWeekend()) {
            return false;
        }

        // Check holidays
        $isHoliday = Libur::where('tanggal', $date->format('Y-m-d'))
            ->where('is_active', true)
            ->exists();

        return !$isHoliday;
    }

    /**
     * Validate attendance submission
     */
    public function validateAttendance(Guru $guru, Carbon $date): array
    {
        // Check if already submitted
        $existing = Absensi::where('guru_id', $guru->id)
            ->whereDate('tanggal', $date)
            ->first();

        if ($existing) {
            return [
                'valid' => false,
                'message' => 'Anda sudah melakukan absensi hari ini',
            ];
        }

        // Check if working day
        if (!$this->isWorkingDay($date)) {
            return [
                'valid' => false,
                'message' => 'Hari ini bukan hari kerja',
            ];
        }

        // Check time range
        $now = Carbon::now();
        $minTime = Carbon::parse($date->format('Y-m-d') . ' ' . config('absensi.jam_masuk_awal', '05:00:00'));
        $maxTime = Carbon::parse($date->format('Y-m-d') . ' ' . config('absensi.jam_pulang_akhir', '17:00:00'));

        if ($now->lt($minTime) || $now->gt($maxTime)) {
            return [
                'valid' => false,
                'message' => 'Absensi hanya dapat dilakukan pada jam kerja',
            ];
        }

        return [
            'valid' => true,
            'message' => 'Validasi berhasil',
        ];
    }

    /**
     * Get teaching schedule for today
     */
    public function getTodaySchedule(Guru $guru, ?Carbon $date = null): array
    {
        $date = $date ?? Carbon::now();
        $dayName = $date->locale('id')->dayName;

        return JadwalMengajar::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('hari', $dayName)
            ->where('is_active', true)
            ->orderBy('jam_mulai')
            ->get()
            ->toArray();
    }

    /**
     * Create attendance record
     */
    public function createAttendance(array $data): Absensi
    {
        $checkIn = isset($data['check_in']) ? Carbon::parse($data['check_in']) : Carbon::now();

        $attendanceData = [
            'guru_id' => $data['guru_id'],
            'tanggal' => $checkIn->format('Y-m-d'),
            'check_in' => $checkIn,
            'status' => $this->determineStatus($checkIn),
            'koordinat_check_in' => $data['koordinat_check_in'] ?? null,
            'foto_check_in' => $data['foto_check_in'] ?? null,
            'keterangan' => $data['keterangan'] ?? null,
        ];

        // Calculate late minutes if late
        if ($attendanceData['status'] === 'terlambat') {
            $attendanceData['menit_keterlambatan'] = $this->calculateLateMinutes($checkIn);
        }

        return Absensi::create($attendanceData);
    }

    /**
     * Process check-out
     */
    public function processCheckOut(Absensi $absensi, array $data): Absensi
    {
        $checkOut = isset($data['check_out']) ? Carbon::parse($data['check_out']) : Carbon::now();

        $absensi->update([
            'check_out' => $checkOut,
            'koordinat_check_out' => $data['koordinat_check_out'] ?? null,
            'foto_check_out' => $data['foto_check_out'] ?? null,
            'durasi_kerja' => $absensi->check_in->diffInMinutes($checkOut),
        ]);

        return $absensi->fresh();
    }

    /**
     * Get attendance statistics for a guru
     */
    public function getStatistics(Guru $guru, int $month, int $year): array
    {
        $absensi = Absensi::where('guru_id', $guru->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        return [
            'total' => $absensi->count(),
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'terlambat' => $absensi->where('status', 'terlambat')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'total_menit_terlambat' => $absensi->sum('menit_keterlambatan'),
        ];
    }
}
