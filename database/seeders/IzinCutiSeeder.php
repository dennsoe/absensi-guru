<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IzinCuti;
use App\Models\Guru;
use Carbon\Carbon;

class IzinCutiSeeder extends Seeder
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

        $izinData = [
            // Pending Izin
            [
                'guru_id' => $gurus[0]->id,
                'jenis_izin' => 'sakit',
                'tanggal_mulai' => Carbon::today()->addDays(1),
                'tanggal_selesai' => Carbon::today()->addDays(3),
                'durasi_hari' => 3,
                'keterangan' => 'Sakit demam tinggi dan batuk. Sudah periksa ke dokter dan dianjurkan istirahat.',
                'file_dokumen' => null,
                'status_approval' => 'pending',
                'guru_pengganti_id' => null,
            ],
            [
                'guru_id' => $gurus[1]->id,
                'jenis_izin' => 'izin',
                'tanggal_mulai' => Carbon::today()->addDays(2),
                'tanggal_selesai' => Carbon::today()->addDays(2),
                'durasi_hari' => 1,
                'keterangan' => 'Mengurus keperluan keluarga yang mendesak di luar kota.',
                'file_dokumen' => null,
                'status_approval' => 'pending',
                'guru_pengganti_id' => null,
            ],
            // Approved Izin
            [
                'guru_id' => $gurus[0]->id,
                'jenis_izin' => 'cuti',
                'tanggal_mulai' => Carbon::today()->subDays(5),
                'tanggal_selesai' => Carbon::today()->subDays(3),
                'durasi_hari' => 3,
                'keterangan' => 'Cuti tahunan untuk keperluan pribadi.',
                'file_dokumen' => null,
                'status_approval' => 'approved',
                'disetujui_oleh' => 1, // Admin user ID
                'tanggal_disetujui' => Carbon::today()->subDays(6),
                'guru_pengganti_id' => $gurus->count() > 2 ? $gurus[2]->id : null,
            ],
            [
                'guru_id' => $gurus[1]->id,
                'jenis_izin' => 'sakit',
                'tanggal_mulai' => Carbon::today()->subDays(10),
                'tanggal_selesai' => Carbon::today()->subDays(9),
                'durasi_hari' => 2,
                'keterangan' => 'Sakit kepala dan pusing, tidak bisa mengajar.',
                'file_dokumen' => null,
                'status_approval' => 'approved',
                'disetujui_oleh' => 1,
                'tanggal_disetujui' => Carbon::today()->subDays(11),
                'guru_pengganti_id' => $gurus->count() > 3 ? $gurus[3]->id : null,
            ],
            // Rejected Izin
            [
                'guru_id' => $gurus[0]->id,
                'jenis_izin' => 'izin',
                'tanggal_mulai' => Carbon::today()->subDays(15),
                'tanggal_selesai' => Carbon::today()->subDays(14),
                'durasi_hari' => 2,
                'keterangan' => 'Acara keluarga.',
                'file_dokumen' => null,
                'status_approval' => 'rejected',
                'disetujui_oleh' => 1,
                'tanggal_disetujui' => Carbon::today()->subDays(16),
                'alasan_penolakan' => 'Bertepatan dengan jadwal ujian kelas XII. Mohon reschedule ke tanggal lain.',
            ],
            // More Pending for Testing
            [
                'guru_id' => $gurus->count() > 2 ? $gurus[2]->id : $gurus[0]->id,
                'jenis_izin' => 'cuti',
                'tanggal_mulai' => Carbon::today()->addDays(7),
                'tanggal_selesai' => Carbon::today()->addDays(9),
                'durasi_hari' => 3,
                'keterangan' => 'Cuti melahirkan istri.',
                'file_dokumen' => null,
                'status_approval' => 'pending',
                'guru_pengganti_id' => null,
            ],
            [
                'guru_id' => $gurus->count() > 3 ? $gurus[3]->id : $gurus[1]->id,
                'jenis_izin' => 'sakit',
                'tanggal_mulai' => Carbon::today(),
                'tanggal_selesai' => Carbon::today()->addDays(1),
                'durasi_hari' => 2,
                'keterangan' => 'Sakit flu dan demam. Surat dokter terlampir.',
                'file_dokumen' => null,
                'status_approval' => 'pending',
                'guru_pengganti_id' => null,
            ],
        ];

        foreach ($izinData as $data) {
            IzinCuti::create($data);
        }

        $this->command->info('✅ 7 Izin/Cuti berhasil di-seed (3 pending, 2 approved, 1 rejected, 1 today)');
    }
}
