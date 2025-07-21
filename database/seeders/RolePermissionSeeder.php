<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        Role::where('name', 'admin_utama')->first()?->givePermissionTo([
            'kelola_pengguna',
            'lihat_dashboard',
            'input_anggaran',
            'verifikasi_laporan',
            'lihat_laporan',
            'akses_daftar_pelaporan',
        ]);

        Role::where('name', 'kepala_dinas')->first()?->givePermissionTo([
            'lihat_dashboard',
            'lihat_laporan',
            'akses_daftar_pelaporan',
        ]);

        Role::where('name', 'bendahara')->first()?->givePermissionTo([
            'input_anggaran',
            'lihat_dashboard',
            'lihat_laporan',
            'akses_daftar_pelaporan',
        ]);

        Role::where('name', 'verifikator')->first()?->givePermissionTo([
            'verifikasi_laporan',
            'lihat_dashboard',
            'akses_daftar_pelaporan',
        ]);

        Role::where('name', 'pptk')->first()?->givePermissionTo([
            'input_anggaran',
            'lihat_dashboard',
            'lihat_laporan',
        ]);
    }
}
