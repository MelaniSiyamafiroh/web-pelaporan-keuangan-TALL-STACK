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

    public function mount()
    {
        // Default filter tahun dari sesi aktif jika tidak diset dari luar
        $this->tahun = session('tahun_aktif');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Kegiatan::with('satuanKerja')
            ->when($this->search, fn($q) =>
                $q->where('nama_kegiatan', 'like', '%' . $this->search . '%'))
            ->when($this->satuanKerja, fn($q) =>
                $q->where('satuan_kerja_id', $this->satuanKerja))
            ->when($this->tahun, fn($q) =>
                $q->where('tahun', $this->tahun));

        // Sortir
        switch ($this->sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('nama_kegiatan', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_kegiatan', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $data = $query->paginate($this->perPage);

        return view('livewire.pages.kelola.kelola-kegiatan-tabel', [
            'kegiatans' => $data,
        ]);
    }
}
