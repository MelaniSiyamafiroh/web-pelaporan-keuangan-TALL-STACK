<?php

namespace App\Livewire\Pages\Kelola;

use App\Models\Kegiatan;
use Livewire\Component;
use Livewire\WithPagination;

class KelolaKegiatanTabel extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sort = 'created_desc';
    public $tahun = '';
    public $satuanKerja = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $data = Kegiatan::with('satuanKerja')
            ->when($this->search, function ($q) {
                $q->where('nama_kegiatan', 'like', '%' . $this->search . '%');
            })
            ->when($this->satuanKerja, function ($q) {
                $q->where('satuan_kerja_id', $this->satuanKerja);
            })
            ->when($this->tahun, function ($q) {
                $q->where('tahun', $this->tahun);
            }, function ($q) {
                $q->where('tahun', session('tahun_aktif'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.pages.kelola.kelola-kegiatan-tabel', [
            'kegiatans' => $data,
        ]);
    }
}
