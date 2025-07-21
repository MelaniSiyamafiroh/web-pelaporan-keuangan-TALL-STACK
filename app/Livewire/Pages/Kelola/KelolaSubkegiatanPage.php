<?php

namespace App\Livewire\Pages\Kelola;

use App\Models\SubKegiatan;
use App\Models\Kegiatan;
use Livewire\Component;
use Livewire\WithPagination;

class KelolaSubkegiatanPage extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'created_desc';

    public $kegiatan_id = '';              // Untuk filter dropdown
    public $filter_tahun_anggaran = '';    // Untuk filter tabel

    public $nama_subkegiatan;
    public $tahun_anggaran;               // Untuk input form
    public $edit_id = null;

    protected $rules = [
        'nama_subkegiatan' => 'required|string|max:255',
        'kegiatan_id' => 'required|exists:kegiatans,id',
        'tahun_anggaran' => 'required|integer',
    ];

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->filter_tahun_anggaran = session('tahun_aktif'); // default filter
        $this->tahun_anggaran = session('tahun_aktif');         // default form input
    }

    public function render()
    {
        $query = SubKegiatan::with('kegiatan');

        if ($this->kegiatan_id) {
            $query->where('kegiatan_id', $this->kegiatan_id);
        }

        if ($this->filter_tahun_anggaran) {
            $query->where('tahun_anggaran', $this->filter_tahun_anggaran);
        }

        if ($this->search) {
            $query->where('nama_subkegiatan', 'like', '%' . $this->search . '%');
        }

        switch ($this->sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('nama_subkegiatan', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_subkegiatan', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return view('livewire.pages.kelola.kelola-subkegiatan-page', [
            'subkegiatan' => $query->paginate(10),
            'kegiatan' => Kegiatan::where('tahun', session('tahun_aktif'))->get(),
        ]);
    }

    public function store()
    {
        $validated = $this->validate();

        try {
            SubKegiatan::create([
                'nama_subkegiatan' => $validated['nama_subkegiatan'],
                'kegiatan_id' => $validated['kegiatan_id'],
                'tahun_anggaran' => $validated['tahun_anggaran'],
            ]);
            session()->flash('success', 'Sub Kegiatan berhasil ditambahkan.');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan Sub Kegiatan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = SubKegiatan::findOrFail($id);
        $this->edit_id = $data->id;
        $this->nama_subkegiatan = $data->nama_subkegiatan;
        $this->kegiatan_id = $data->kegiatan_id;
        $this->tahun_anggaran = $data->tahun_anggaran;
    }

    public function update()
    {
        $validated = $this->validate();

        try {
            $data = SubKegiatan::findOrFail($this->edit_id);
            $data->update([
                'nama_subkegiatan' => $validated['nama_subkegiatan'],
                'kegiatan_id' => $validated['kegiatan_id'],
                'tahun_anggaran' => $validated['tahun_anggaran'],
            ]);
            session()->flash('success', 'Sub Kegiatan berhasil diperbarui.');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui Sub Kegiatan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            SubKegiatan::findOrFail($id)->delete();
            session()->flash('success', 'Sub Kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus Sub Kegiatan: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->edit_id = null;
        $this->nama_subkegiatan = '';
        $this->kegiatan_id = '';
        $this->tahun_anggaran = session('tahun_aktif'); // default input
    }

    // Reset pagination saat filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingIdKegiatan() { $this->resetPage(); }
    public function updatingFilterTahunAnggaran() { $this->resetPage(); }
    public function updatingSort() { $this->resetPage(); }
}
