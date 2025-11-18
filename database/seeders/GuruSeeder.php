<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruData = [
            [
                'user' => [
                    'username' => 'guru001',
                    'nama' => 'Ahmad Fauzi, S.Pd',
                    'email' => 'ahmad.fauzi@smknekas.sch.id',
                    'password' => Hash::make('password123'),
                    'role' => 'guru',
                    'status' => 'aktif',
                    'is_active' => true,
                ],
                'guru' => [
                    'nip' => '198501012010011001',
                    'nama' => 'Ahmad Fauzi, S.Pd',
                    'email' => 'ahmad.fauzi@smknekas.sch.id',
                    'jenis_kelamin' => 'L',
                    'tanggal_lahir' => '1985-01-01',
                    'alamat' => 'Jl. Raya Kasomalang No. 123, Subang',
                    'no_hp' => '081234567801',
                    'status_kepegawaian' => 'PNS',
                ]
            ],
            [
                'user' => [
                    'username' => 'guru002',
                    'nama' => 'Siti Nurjanah, S.Pd',
                    'email' => 'siti.nurjanah@smknekas.sch.id',
                    'password' => Hash::make('password123'),
                    'role' => 'guru',
                    'status' => 'aktif',
                    'is_active' => true,
                ],
                'guru' => [
                    'nip' => '198602152010012002',
                    'nama' => 'Siti Nurjanah, S.Pd',
                    'email' => 'siti.nurjanah@smknekas.sch.id',
                    'jenis_kelamin' => 'P',
                    'tanggal_lahir' => '1986-02-15',
                    'alamat' => 'Jl. Kasomalang Timur No. 45, Subang',
                    'no_hp' => '081234567802',
                    'status_kepegawaian' => 'PNS',
                ]
            ],
            [
                'user' => [
                    'username' => 'guru003',
                    'nama' => 'Budi Santoso, S.Kom',
                    'email' => 'budi.santoso@smknekas.sch.id',
                    'password' => Hash::make('password123'),
                    'role' => 'guru',
                    'status' => 'aktif',
                    'is_active' => true,
                ],
                'guru' => [
                    'nip' => '198703202011011003',
                    'nama' => 'Budi Santoso, S.Kom',
                    'email' => 'budi.santoso@smknekas.sch.id',
                    'jenis_kelamin' => 'L',
                    'tanggal_lahir' => '1987-03-20',
                    'alamat' => 'Jl. Industri No. 67, Subang',
                    'no_hp' => '081234567803',
                    'status_kepegawaian' => 'PNS',
                ]
            ],
            [
                'user' => [
                    'username' => 'guru004',
                    'nama' => 'Rina Wati, S.Pd',
                    'email' => 'rina.wati@smknekas.sch.id',
                    'password' => Hash::make('password123'),
                    'role' => 'guru',
                    'status' => 'aktif',
                    'is_active' => true,
                ],
                'guru' => [
                    'nip' => '198804102012012004',
                    'nama' => 'Rina Wati, S.Pd',
                    'email' => 'rina.wati@smknekas.sch.id',
                    'jenis_kelamin' => 'P',
                    'tanggal_lahir' => '1988-04-10',
                    'alamat' => 'Jl. Ciasem No. 89, Subang',
                    'no_hp' => '081234567804',
                    'status_kepegawaian' => 'PPPK',
                ]
            ],
            [
                'user' => [
                    'username' => 'guru005',
                    'nama' => 'Dedi Kurniawan, S.T',
                    'email' => 'dedi.kurniawan@smknekas.sch.id',
                    'password' => Hash::make('password123'),
                    'role' => 'guru',
                    'status' => 'aktif',
                    'is_active' => true,
                ],
                'guru' => [
                    'nip' => '198905252013011005',
                    'nama' => 'Dedi Kurniawan, S.T',
                    'email' => 'dedi.kurniawan@smknekas.sch.id',
                    'jenis_kelamin' => 'L',
                    'tanggal_lahir' => '1989-05-25',
                    'alamat' => 'Jl. Pamanukan No. 12, Subang',
                    'no_hp' => '081234567805',
                    'status_kepegawaian' => 'GTT',
                ]
            ],
        ];

        foreach ($guruData as $data) {
            $user = User::create($data['user']);

            $data['guru']['user_id'] = $user->id;
            Guru::create($data['guru']);
        }

        $this->command->info('✅ 5 Guru berhasil di-seed');
    }
}
