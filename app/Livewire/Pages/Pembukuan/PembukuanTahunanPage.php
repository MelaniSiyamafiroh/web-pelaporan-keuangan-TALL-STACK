<?php

namespace App\Livewire\Pages\Pembukuan;

use App\Models\Pelaporan;
use App\Models\LaporanTahunan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class PembukuanTahunanPage extends Component
{
    public $laporanSiap;
    public $filePath;
    public $catatan;

    public function mount()
    {
        $this->loadLaporan();
    }

    public function loadLaporan()
    {
        $this->laporanSiap = Pelaporan::with(['pptk', 'subkegiatan.kegiatan'])
            ->where('status', 'disetujui')
            ->whereHas('subkegiatan', function ($q) {
                $q->where('tahun_anggaran', session('tahun_aktif'));
            })
            ->whereNull('laporan_tahunan_id')
            ->get();
    }

    public function bukukan()
    {
        if ($this->laporanSiap->isEmpty()) {
            $this->dispatch('notify', title: 'Tidak ada laporan yang bisa dibukukan.');
            return;
        }

        $tahun = session('tahun_aktif');
        $filename = 'laporan-tahunan-' . $tahun . '-' . Str::random(6) . '.pdf';

        $pdf = Pdf::loadView('exports.laporan-tahunan', [
            'laporanSiap' => $this->laporanSiap,
            'tahun' => $tahun,
        ]);

        Storage::put('laporan_tahunan/' . $filename, $pdf->output());

        $laporan = LaporanTahunan::create([
            'tahun' => $tahun,
            'file_path' => 'laporan_tahunan/' . $filename,
            'catatan' => $this->catatan,
        ]);

        foreach ($this->laporanSiap as $item) {
            $item->update(['laporan_tahunan_id' => $laporan->id]);
        }

        $this->reset('catatan', 'filePath');
        $this->loadLaporan();

        $this->dispatch('notify', title: 'Laporan tahunan berhasil dibukukan.');
    }

    public function render()
    {
        return view('livewire.pages.pembukuan.pembukuan-tahunan-page');
    }
}
