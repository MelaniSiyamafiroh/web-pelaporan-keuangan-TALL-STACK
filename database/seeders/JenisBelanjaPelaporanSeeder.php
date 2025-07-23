<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisBelanjaPelaporan;
use App\Models\TemplateBerkasBelanja;

class JenisBelanjaPelaporanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'SPJ GU Non Tunai' => ['A2', 'Surat pesanan', 'BAST', 'Nota', 'Bukti setoran pajak'],
            'SPJ GU Tunai'     => ['A2', 'SPT', 'SPD', 'Tanda terima', 'Notulen', 'Dokumentasi', 'Undangan'],
            'Jasa Tenaga Ahli' => ['A2', 'Tanda terima', 'Daftar laporan harian', 'Upload hasil kerja', 'SPK'],
            'Belanja Jasa THL' => ['A2', 'Tanda terima', 'Absensi', 'SK THL'],
        ];

        foreach ($data as $jenis => $berkasList) {
            // Buat jenis belanja (pastikan kolom yang digunakan adalah 'nama')
            $jenisBelanja = JenisBelanjaPelaporan::firstOrCreate(['nama' => $jenis]);

            // Buat template berkas sesuai jenis belanja
            foreach ($berkasList as $namaBerkas) {
                TemplateBerkasBelanja::firstOrCreate([
                    'jenis_belanja_id' => $jenisBelanja->id,
                    'nama_berkas' => $namaBerkas
                ]);
            }
        }
    }
}
