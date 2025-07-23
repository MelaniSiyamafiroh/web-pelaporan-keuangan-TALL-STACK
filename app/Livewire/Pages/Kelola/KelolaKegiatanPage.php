<?php

namespace App\Livewire\Pages\Kelola;

use App\Models\Kegiatan;
use App\Models\SatuanKerja;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class KelolaKegiatanPage extends Component
{
    use WithPagination;

    // Filter dan Pagination
    public $search = '';
    public $sort = 'created_desc';
    public $satuanKerja = '';
    public $tahun = '';
    public $perPage = 10;

    // Form Input
    public $kegiatanId;
    public $nama_kegiatan;
    public $formSatuanKerja;
    public $formTahun;

    protected $rules = [
        'formSatuanKerja' => 'required|exists:satuan_kerjas,id',
        'nama_kegiatan' => 'required|string|max:255',
        'formTahun' => 'required|integer',
    ];

    public function mount()
    {
        $this->tahun = session('tahun_aktif');
        $this->formTahun = session('tahun_aktif');
    }

    public function render()
    {
        $query = Kegiatan::with('satuanKerja');

        if ($this->satuanKerja) {
            $query->where('satuan_kerja_id', $this->satuanKerja);
        }

        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }

        if ($this->search) {
            $query->where('nama_kegiatan', 'like', '%' . $this->search . '%');
        }

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

        return view('livewire.pages.kelola.kelola-kegiatan-page', [
            'kegiatan' => $query->paginate($this->perPage),
            'daftarSatuanKerja' => SatuanKerja::all(),
        ]);
    }

    public function save()
    {
        $validated = $this->validate();

        try {
            $sk = SatuanKerja::findOrFail($this->formSatuanKerja);

            if ($this->kegiatanId) {
                // Update Kegiatan
                Kegiatan::findOrFail($this->kegiatanId)->update([
                    'satuan_kerja_id' => $sk->id,
                    'nama_kegiatan' => $this->nama_kegiatan,
                    'tahun' => $this->formTahun,
                ]);
                session()->flash('success', 'Kegiatan berhasil diperbarui.');
            } else {
                // Create Kegiatan
                Kegiatan::create([
                    'satuan_kerja_id' => $sk->id,
                    'nama_kegiatan' => $this->nama_kegiatan,
                    'tahun' => $this->formTahun,
                ]);
                session()->flash('success', 'Kegiatan berhasil disimpan.');
            }

            $this->resetForm();
        } catch (\Exception $e) {
            Log::error('Gagal simpan kegiatan: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan kegiatan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->kegiatanId = $kegiatan->id;
        $this->nama_kegiatan = $kegiatan->nama_kegiatan;
        $this->formSatuanKerja = $kegiatan->satuan_kerja_id;
        $this->formTahun = $kegiatan->tahun;
    }

    public function delete($id)
    {
        Kegiatan::findOrFail($id)->delete();
        session()->flash('success', 'Kegiatan berhasil dihapus.');
    }

    private function resetForm()
    {
        $this->reset(['kegiatanId', 'nama_kegiatan', 'formSatuanKerja', 'formTahun']);
    }
}
