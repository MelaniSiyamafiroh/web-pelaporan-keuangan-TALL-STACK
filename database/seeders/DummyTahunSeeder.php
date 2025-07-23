<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\Subkegiatan;
use App\Models\Pelaporan;
use App\Models\User;
use App\Models\SatuanKerja;
use App\Models\JenisBelanjaPelaporan;

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
                    'nama_kegiatan' => "Kegiatan {$sk->nama} {$tahun}",
                    'tahun' => $tahun,
                    'satuan_kerja_id' => $sk->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Buat subkegiatan
                $subkegiatan = Subkegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'nama_subkegiatan' => "Subkegiatan {$sk->nama} {$tahun}",
                    'tahun_anggaran' => $tahun,
                    'rekening' => '5.1.2.03.01',
                    'jumlah_pagu' => rand(5, 20) * 1_000_000,
                ]);

                // Cari user PPTK
                $pptk = User::where('satuan_kerja_id', $sk->id)
                    ->whereHas('roles', function ($q) {
                        $q->where('name', 'like', 'pptk%');
                    })
                    ->first();

                // Ambil jenis belanja secara acak (jika ada)
                $jenisBelanja = JenisBelanjaPelaporan::inRandomOrder()->first();

                if ($pptk && $jenisBelanja) {
                    Pelaporan::create([
                        'subkegiatan_id'     => $subkegiatan->id,
                        'kegiatan_id'        => $kegiatan->id,
                        'pptk_id'            => $pptk->id,
                        'status'             => 'diajukan',
                        'tahun'              => $tahun,
                        'jenis_belanja_id'   => $jenisBelanja->id,
                        'rekening_kegiatan'  => $subkegiatan->rekening,
                        'nominal_pagu'       => $subkegiatan->jumlah_pagu,
                        'nominal'            => 0,
                    ]);
                }
            }
        }
    }
}
