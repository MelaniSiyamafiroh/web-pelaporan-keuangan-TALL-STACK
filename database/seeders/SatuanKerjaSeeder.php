<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SatuanKerja;

class SatuanKerjaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'IKP'],
            ['nama' => 'TKI'],
            ['nama' => 'Sekretariat'],
        ];

        foreach ($data as $item) {
            SatuanKerja::firstOrCreate($item);
        }
    }
}
