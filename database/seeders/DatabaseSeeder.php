<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            SatuanKerjaSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            JenisBelanjaPelaporanSeeder::class,
            TemplateBerkasBelanjaSeeder::class,
            DummyTahunSeeder::class,
        ]);
    }
}
