<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tingkat = ['X', 'XI', 'XII'];
        $jurusan = [
            'TKJ' => 'Teknik Komputer dan Jaringan',
            'RPL' => 'Rekayasa Perangkat Lunak',
            'MM' => 'Multimedia',
            'OTKP' => 'Otomatisasi Tata Kelola Perkantoran',
        ];

        foreach ($tingkat as $t) {
            foreach ($jurusan as $kode => $namaJurusan) {
                for ($rombel = 1; $rombel <= 2; $rombel++) {
                    Kelas::create([
                        'kode_kelas' => "$t-$kode-$rombel",
                        'nama_kelas' => "$t $kode $rombel",
                        'tingkat' => $t,
                        'jurusan' => $namaJurusan,
                        'tahun_ajaran' => '2024/2025',
                        'semester' => 'Ganjil',
                        'wali_kelas_id' => null, // Will be assigned later
                    ]);
                }
            }
        }

        $this->command->info('✅ 24 Kelas berhasil di-seed (3 tingkat x 4 jurusan x 2 rombel)');
    }
}
