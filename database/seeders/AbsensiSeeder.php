<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Guru;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gurus = Guru::all();

        if ($gurus->count() < 2) {
            $this->command->warn('⚠️ Minimal 2 guru diperlukan. Jalankan GuruSeeder terlebih dahulu.');
            return;
        }

        $statusOptions = ['hadir', 'izin', 'sakit', 'alpa'];
        $statusAbsen = ['masuk', 'pulang'];

        // Generate absensi untuk 7 hari terakhir
        for ($day = 7; $day >= 1; $day--) {
            $tanggal = Carbon::today()->subDays($day);

            // Skip weekend
            if ($tanggal->isWeekend()) {
                continue;
            }

            foreach ($gurus as $index => $guru) {
                // 80% hadir, 20% tidak hadir
                $isPresent = rand(1, 100) <= 80;

                if ($isPresent) {
                    // Absen Masuk
                    $jamMasuk = Carbon::parse($tanggal->format('Y-m-d') . ' 07:00:00')
                        ->addMinutes(rand(-10, 30)); // Variasi -10 menit sampai +30 menit

                    $absensiMasuk = Absensi::create([
                        'guru_id' => $guru->id,
                        'tanggal' => $tanggal,
                        'status_absen' => 'masuk',
                        'jam_absen' => $jamMasuk,
                        'status' => $jamMasuk->format('H:i:s') <= '07:15:00' ? 'hadir' : 'terlambat',
                        'keterangan' => $jamMasuk->format('H:i:s') > '07:15:00' ? 'Terlambat' : null,
                        'latitude' => -6.5667812 + (rand(-100, 100) / 100000), // Variasi kecil
                        'longitude' => 107.7442321 + (rand(-100, 100) / 100000),
                        'foto_selfie' => 'absensi/selfie-' . $guru->id . '-' . $tanggal->format('Ymd') . '-masuk.jpg',
                        'lokasi' => 'SMK Negeri Kasomalang',
                        'device_info' => json_encode([
                            'browser' => 'Chrome',
                            'os' => 'Android',
                            'device' => 'Mobile',
                        ]),
                    ]);

                    // Absen Pulang (70% guru absen pulang)
                    if (rand(1, 100) <= 70) {
                        $jamPulang = Carbon::parse($tanggal->format('Y-m-d') . ' 15:30:00')
                            ->addMinutes(rand(-30, 60));

                        Absensi::create([
                            'guru_id' => $guru->id,
                            'tanggal' => $tanggal,
                            'status_absen' => 'pulang',
                            'jam_absen' => $jamPulang,
                            'status' => 'hadir',
                            'keterangan' => null,
                            'latitude' => -6.5667812 + (rand(-100, 100) / 100000),
                            'longitude' => 107.7442321 + (rand(-100, 100) / 100000),
                            'foto_selfie' => 'absensi/selfie-' . $guru->id . '-' . $tanggal->format('Ymd') . '-pulang.jpg',
                            'lokasi' => 'SMK Negeri Kasomalang',
                            'device_info' => json_encode([
                                'browser' => 'Chrome',
                                'os' => 'Android',
                                'device' => 'Mobile',
                            ]),
                        ]);
                    }
                } else {
                    // Tidak hadir (izin/sakit/alpa)
                    $statusTidakHadir = $statusOptions[rand(1, 3)]; // izin, sakit, atau alpa

                    Absensi::create([
                        'guru_id' => $guru->id,
                        'tanggal' => $tanggal,
                        'status_absen' => 'masuk',
                        'jam_absen' => null,
                        'status' => $statusTidakHadir,
                        'keterangan' => $statusTidakHadir === 'sakit' ? 'Sakit' : ($statusTidakHadir === 'izin' ? 'Izin' : 'Tanpa Keterangan'),
                        'latitude' => null,
                        'longitude' => null,
                        'foto_selfie' => null,
                        'lokasi' => null,
                        'device_info' => null,
                    ]);
                }
            }
        }

        $totalAbsensi = Absensi::count();
        $this->command->info("✅ $totalAbsensi record absensi berhasil di-seed (7 hari kerja terakhir)");
    }
}
