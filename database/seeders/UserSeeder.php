<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SatuanKerja;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID satuan kerja berdasarkan nama (pastikan sudah di-seed sebelumnya)
        $ikp = SatuanKerja::firstWhere('nama', 'IKP')?->id;
        $tki = SatuanKerja::firstWhere('nama', 'TKI')?->id;
        $sekretariat = SatuanKerja::firstWhere('nama', 'Sekretariat')?->id;

        $users = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@gmail.com',
                'password' => 'admin123',
                'role' => 'admin_utama',
                'satuan_kerja_id' => null,
            ],
            [
                'name' => 'Kepala Dinas',
                'email' => 'kepala@gmail.com',
                'password' => 'kepala123',
                'role' => 'kepala_dinas',
                'satuan_kerja_id' => null,
            ],
            [
                'name' => 'Bendahara',
                'email' => 'bendahara@gmail.com',
                'password' => 'bendahara123',
                'role' => 'bendahara',
                'satuan_kerja_id' => null,
            ],
            [
                'name' => 'Verifikator',
                'email' => 'verifikator@gmail.com',
                'password' => 'verifikator123',
                'role' => 'verifikator',
                'satuan_kerja_id' => null,
            ],
            [
                'name' => 'PPTK TKI',
                'email' => 'pptk_tki@gmail.com',
                'password' => 'pptk123',
                'role' => 'pptk',
                'satuan_kerja_id' => $tki,
            ],
            [
                'name' => 'PPTK IKP',
                'email' => 'pptk_ikp@gmail.com',
                'password' => 'pptk123',
                'role' => 'pptk',
                'satuan_kerja_id' => $ikp,
            ],
            [
                'name' => 'PPTK Sekretaris',
                'email' => 'pptk_sekretaris@gmail.com',
                'password' => 'pptk123',
                'role' => 'pptk',
                'satuan_kerja_id' => $sekretariat,
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'satuan_kerja_id' => $data['satuan_kerja_id'],
                    'photo' => null,
                ]
            );

            $user->syncRoles([$data['role']]);
        }
    }
}
