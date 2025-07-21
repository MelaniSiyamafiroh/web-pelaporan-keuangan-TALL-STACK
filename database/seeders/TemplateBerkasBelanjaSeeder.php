<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TemplateBerkasBelanja;

class TemplateBerkasBelanjaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // SPJ GU
            ['jenis_belanja' => 'spj_gu', 'nama_berkas' => 'A2'],
            ['jenis_belanja' => 'spj_gu', 'nama_berkas' => 'surat pesanan'],
            ['jenis_belanja' => 'spj_gu', 'nama_berkas' => 'BAST'],
            ['jenis_belanja' => 'spj_gu', 'nama_berkas' => 'nota'],
            ['jenis_belanja' => 'spj_gu', 'nama_berkas' => 'bukti setoran pajak'],

            // SPJ GU Tunai
            ['jenis_belanja' => 'spj_gu_tunai', 'nama_berkas' => 'A2'],
            ['jenis_belanja' => 'spj_gu_tunai', 'nama_berkas' => 'SPT'],
            ['jenis_belanja' => 'spj_gu_tunai', 'nama_berkas' => 'SPD'],
            ['jenis_belanja' => 'spj_gu_tunai', 'nama_berkas' => 'Tanda terima'],
            ['jenis_belanja' => 'spj_gu_tunai', 'nama_berkas' => 'Notulensi'],
            ['jenis_belanja' => 'spj_gu_tunai', 'nama_berkas' => 'dokumentasi'],
            ['jenis_belanja' => 'spj_gu_tunai', 'nama_berkas' => 'undangan'],

            // SPJ Tenaga Ahli
            ['jenis_belanja' => 'spj_tenaga_ahli', 'nama_berkas' => 'A2'],
            ['jenis_belanja' => 'spj_tenaga_ahli', 'nama_berkas' => 'tanda terima'],
            ['jenis_belanja' => 'spj_tenaga_ahli', 'nama_berkas' => 'daftar laporan harian'],
            ['jenis_belanja' => 'spj_tenaga_ahli', 'nama_berkas' => 'upload hasil pekerjaan'],
            ['jenis_belanja' => 'spj_tenaga_ahli', 'nama_berkas' => 'SPK'],
        ];

        foreach ($data as $item) {
            TemplateBerkasBelanja::create($item);
        }
    }
}
