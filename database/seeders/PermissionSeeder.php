<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'kelola_pengguna',
            'lihat_dashboard',
            'input_anggaran',
            'verifikasi_laporan',
            'lihat_laporan',
            'akses_daftar_pelaporan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
