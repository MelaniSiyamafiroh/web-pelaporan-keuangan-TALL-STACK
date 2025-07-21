<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin_utama', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kepala_dinas', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'bendahara', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'verifikator', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'pptk', 'guard_name' => 'web']); // 👈 Hanya 1 role untuk semua PPTK
    }
}
