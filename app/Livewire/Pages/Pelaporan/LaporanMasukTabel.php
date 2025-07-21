<?php

namespace App\Livewire\Pages\Pelaporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pelaporan;

class LaporanMasukTabel extends Component
{
    use WithPagination;

    public $search = '';
    public $pptk = '';
    public $tahun = '';
    public $sort = 'created_desc';

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $query = Pelaporan::with(['pptk', 'kegiatan', 'subkegiatan']);

        // Filter search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('jenis_belanja', 'like', '%' . $this->search . '%')
                  ->orWhere('rekening_kegiatan', 'like', '%' . $this->search . '%')
                  ->orWhereHas('kegiatan', function ($q) {
                      $q->where('nama_kegiatan', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('subkegiatan', function ($q) {
                      $q->where('nama_subkegiatan', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter PPTK
        if ($this->pptk) {
            $query->where('pptk_id', $this->pptk);
        }

        // Filter tahun
        if ($this->tahun) {
            $query->where('tahun_anggaran', $this->tahun);
        }

        // Sort
        switch ($this->sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nama_asc':
                // sort by kegiatan name ascending
                $query->join('kegiatans', 'pelaporans.kegiatan_id', '=', 'kegiatans.id')
                      ->orderBy('kegiatans.nama_kegiatan', 'asc')
                      ->select('pelaporans.*');
                break;
            case 'nama_desc':
                // sort by kegiatan name descending
                $query->join('kegiatans', 'pelaporans.kegiatan_id', '=', 'kegiatans.id')
                      ->orderBy('kegiatans.nama_kegiatan', 'desc')
                      ->select('pelaporans.*');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $laporan = $query->paginate(10);

        return view('livewire.pages.pelaporan.laporan-masuk-tabel', [
            'laporan' => $laporan,
        ]);
    }

    // Fungsi reset pagination saat filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingPptk() { $this->resetPage(); }
    public function updatingTahun() { $this->resetPage(); }
    public function updatingSort() { $this->resetPage(); }
}
