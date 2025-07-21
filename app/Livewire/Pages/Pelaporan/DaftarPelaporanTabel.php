<?php

namespace App\Livewire\Pages\Pelaporan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pelaporan;
use Illuminate\Support\Facades\Auth;

class DaftarPelaporanTabel extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPptk = '';
    public $filterStatus = '';
    public $filterTahun = '';
    public $sort = 'created_desc';
    public $berkas = []; // format: ['A2' => file, 'Surat Pesanan' => file, ...]


    public $modalOpen = false;
    public $editingId = null;
    public $deleteId;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterPptk' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterTahun' => ['except' => ''],
        'sort' => ['except' => 'created_desc'],
    ];

    protected $listeners = [
        'refreshTable' => '$refresh',
        'triggerDelete' => 'triggerDelete',
    ];

    public function mount()
    {
        if (!$this->filterTahun && session()->has('tahun_aktif')) {
            $this->filterTahun = session('tahun_aktif');
        }
    }

    // Reset pagination saat filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterPptk() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterTahun() { $this->resetPage(); }
    public function updatingSort() { $this->resetPage(); }

    public function openModal()
    {
        $this->editingId = null;
        $this->modalOpen = true;
    }

    public function edit($id)
    {
        $this->editingId = $id;
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
    }

    // Trigger dari tombol konfirmasi
    public function triggerDelete($id)
    {
        $this->deleteId = $id;
        $this->delete();
    }

    // Eksekusi penghapusan
    public function delete()
    {
        $laporan = Pelaporan::find($this->deleteId);

        if ($laporan) {
            $laporan->delete();
            session()->flash('message', 'Laporan berhasil dihapus.');
        } else {
            session()->flash('error', 'Laporan tidak ditemukan.');
        }

        $this->emitSelf('refreshTable');
    }

    public function render()
    {
        $query = Pelaporan::with(['pptk.roles', 'kegiatan', 'subkegiatan']);
        $user = Auth::user();

        if (!$user->hasAnyRole(['admin', 'bendahara', 'verifikator', 'kepala_dinas'])) {
            $query->where('pptk_id', $user->id);
        }

        if ($this->filterPptk) {
            $query->where('pptk_id', $this->filterPptk);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterTahun) {
            $query->where('tahun', $this->filterTahun);
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('jenis_belanja', 'like', $searchTerm)
                  ->orWhere('rekening_kegiatan', 'like', $searchTerm)
                  ->orWhere('tahun', 'like', $searchTerm)
                  ->orWhere('status', 'like', $searchTerm)
                  ->orWhere('catatan', 'like', $searchTerm)
                  ->orWhereHas('pptk.roles', function ($qr) use ($searchTerm) {
                      $qr->where('name', 'like', $searchTerm);
                  })
                  ->orWhereHas('kegiatan', function ($qr) use ($searchTerm) {
                      $qr->where('nama_kegiatan', 'like', $searchTerm);
                  })
                  ->orWhereHas('subkegiatan', function ($qr) use ($searchTerm) {
                      $qr->where('nama_subkegiatan', 'like', $searchTerm);
                  });
            });
        }

        switch ($this->sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $laporan = $query->paginate(10);

        return view('livewire.pages.pelaporan.daftar-pelaporan-tabel', [
            'laporan' => $laporan,
            'modalOpen' => $this->modalOpen,
            'editingId' => $this->editingId,
        ]);
    }

    public function updatedJenisBelanja()
{
    $jenis = $this->jenis_belanja;
    $this->berkas = [];

    if ($jenis === 'SPJ GU') {
        $this->requiredBerkas = ['A2', 'Surat Pesanan', 'BAST', 'Nota', 'Bukti Setoran Pajak'];
    } elseif ($jenis === 'SPJ GU Tunai') {
        $this->requiredBerkas = ['A2', 'SPT', 'SPD', 'Tanda Terima', 'Notulensi', 'Dokumentasi', 'Undangan'];
    } elseif ($jenis === 'Belanja Tenaga Ahli') {
        $this->requiredBerkas = ['A2', 'Tanda Terima', 'Daftar Laporan Harian', 'Upload Hasil Pekerjaan', 'SPK'];
    } else {
        $this->requiredBerkas = [];
    }
}

}
