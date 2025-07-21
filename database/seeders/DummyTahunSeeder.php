<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\Subkegiatan;
use App\Models\Pelaporan;
use App\Models\User;
use App\Models\SatuanKerja;

class DummyTahunSeeder extends Seeder
{
    public function run(): void
    {
        $tahunList = [2023, 2024, 2025];

        foreach ($tahunList as $tahun) {
            $satuanKerjas = SatuanKerja::all();

            foreach ($satuanKerjas as $sk) {
                // Buat kegiatan
                $kegiatan = Kegiatan::create([
                    'nama_kegiatan' => "Kegiatan {$sk->nama} $tahun",
                    'tahun' => $tahun,
                    'satuan_kerja' => $sk->nama,
                ]);

                // Buat subkegiatan
                $subkegiatan = Subkegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'nama_subkegiatan' => "Subkegiatan {$sk->nama} $tahun",
                    'tahun_anggaran' => $tahun,
                    'rekening' => rand(100, 999),
                    'jumlah_pagu' => rand(5, 20) * 1000000,
                ]);

                // Cari user PPTK yang satuan_kerja_id-nya cocok
                $pptk = User::where('satuan_kerja_id', $sk->id)
                    ->whereHas('roles', function ($q) {
                        $q->where('name', 'like', 'pptk%');
                    })
                    ->first();

                if ($pptk) {
                    Pelaporan::create([
                        'subkegiatan_id' => $subkegiatan->id,
                        'kegiatan_id' => '1' ,
                        'pptk_id' => $pptk->id,
                        'status' => 'diajukan',
                        'tahun' => 2024,
                        'rekening_kegiatan' => '5.1.2.03.01',
                        'nominal_pagu' => 50000000, // tambahkan ini
                        'nominal' => 0, // ← nominal realisasi awal (bisa 0 dulu)
                    ]);
                }
            }
        }
    }
}
