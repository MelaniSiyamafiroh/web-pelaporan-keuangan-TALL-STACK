<?php

namespace App\Livewire\Pages\Pembukuan;

use App\Models\LaporanTahunan;
use Livewire\Component;
use Livewire\WithPagination;

class PembukuanTahunanTabel extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = LaporanTahunan::with('satuanKerja')
            ->tahunAktif()
            ->when($this->search, function ($query) {
                $query->whereHas('satuanKerja', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.pages.pembukuan.pembukuan-tahunan-tabel', [
            'laporanTahunans' => $data,
        ]);
    }
}
