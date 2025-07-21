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

    public $search = '';
    public $sort = 'created_desc';
    public $satuanKerja = '';
    public $tahun = '';
    public $perPage = 10;

    // Form input
    public $kegiatanId;
    public $nama_kegiatan;
    public $formSatuanKerja;
    public $formTahun;

    //livewire cek, saat simpan data
    protected $rules = [
        'formSatuanKerja' => 'required|exists:satuan_kerjas,id',
        'nama_kegiatan' => 'required|string|max:255',
        'formTahun' => 'required|integer',
    ];

    //fungsi yang langsung jalan
    public function mount()
    {
        $this->tahun = session('tahun_aktif');
        $this->formTahun = session('tahun_aktif');
    }

    //fungsi yang ambil data kegiatan serta relasi ke satuan kerja
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

        //mengirim kembali ke tampilan
        return view('livewire.pages.kelola.kelola-kegiatan-page', [
            'kegiatan' => $query->paginate($this->perPage), //data kegiatan hasil query+paginate
            'daftarSatuanKerja' => SatuanKerja::all(), //semua data satker
        ]);
    }

    //fungsi save
    public function save()
{
    Log::info('Tombol simpan ditekan');

    $validated = $this->validate();
    Log::info('Validasi berhasil', $validated);

    try {
        $sk = SatuanKerja::findOrFail($this->formSatuanKerja);
        Log::info('Nama SK: ', ['nama' => $sk->nama]);

        //cek jika ada kegiatanId itu berarti update data, jika bukan berarti tambah data
        if ($this->kegiatanId) {
            Log::info('Mode: Edit');
            Kegiatan::findOrFail($this->kegiatanId)->update([
                'satuan_kerja_id' => $sk->id,
                'satuan_kerja' => $sk->nama,
                'nama_kegiatan' => $this->nama_kegiatan,
                'tahun' => $this->formTahun,
            ]);
            session()->flash('success', 'Kegiatan berhasil diperbarui.');
        } else {
            Log::info('Mode: Create');
            Kegiatan::create([
                'satuan_kerja_id' => $sk->id,
                'satuan_kerja' => $sk->nama,
                'nama_kegiatan' => $this->nama_kegiatan,
                'tahun' => $this->formTahun,
            ]);
            session()->flash('success', 'Kegiatan berhasil disimpan.'); //notif berhasil simpan
        }

        $this->resetForm(); //form direset biar kosong lagi
    } catch (\Exception $e) {
        Log::error('Gagal simpan kegiatan: ' . $e->getMessage());
        session()->flash('error', 'Gagal menyimpan kegiatan: ' . $e->getMessage()); //cek log gagal
    }
}

    //fungsi edit
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $this->kegiatanId = $kegiatan->id;
        $this->nama_kegiatan = $kegiatan->nama_kegiatan;
        $this->formSatuanKerja = $kegiatan->satuan_kerja_id;
        $this->formTahun = $kegiatan->tahun;
    }

    //fungsi hapus
    public function delete($id)
    {
        Kegiatan::findOrFail($id)->delete();
        session()->flash('success', 'Kegiatan berhasil dihapus.');
    }

    //mengkosongkan form
    private function resetForm()
    {
        $this->reset(['kegiatanId', 'nama_kegiatan', 'formSatuanKerja', 'formTahun']);
    }
}
