<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\SatuanKerja;

class MigrasiKegiatanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Kegiatan::all() as $kegiatan) {
            $sk = SatuanKerja::where('nama', $kegiatan->satuan_kerja)->first();
            if ($sk) {
                $kegiatan->satuan_kerja_id = $sk->id;
                $kegiatan->save();
            }
        }
    }
}
