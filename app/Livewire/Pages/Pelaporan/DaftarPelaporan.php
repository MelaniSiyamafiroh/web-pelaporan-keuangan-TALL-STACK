<?php

namespace App\Livewire\Pages\Pelaporan;

use App\Models\Kegiatan;
use App\Models\Pelaporan;
use App\Models\Subkegiatan;
use App\Models\User;
use App\Models\BerkasPelaporan;
use App\Models\TemplateBerkasBelanja;
use App\Models\JenisBelanjaPelaporan;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class DaftarPelaporan extends Component
{
    use WithFileUploads;

    // Filter
    public $search = '';
    public $status = '';
    public $tahun;
    public $tahun_input;
    public $sort = 'created_desc';
    public $editingId = null;

    // Form
    public $showModal = false;
    public $pptk_id, $kegiatan_id, $subkegiatan_id;
    public $rekening_kegiatan, $nominal_pagu, $nominal;
    public $file_upload, $catatan = '-';

    // Upload Dinamis
    public $jenis_belanja_terpilih = [];
    public $requiredBerkas = [];
    public $berkas_per_jenis = [];
    public $jenis_dipilih;
    public $nama_berkas_dipilih;
    public $berkas_upload;
    public $pelaporan_id_aktif;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'tahun' => ['except' => ''],
        'sort' => ['except' => 'created_desc'],
    ];

    protected $listeners = ['triggerDelete'];

    public function mount()
    {
        $this->tahun = now()->year;
        $this->jenis_belanja_terpilih = JenisBelanjaPelaporan::pluck('nama')->toArray();
    }

    public function rules()
    {
        $rules = [
            'pptk_id'           => 'required|exists:users,id',
            'kegiatan_id'       => 'required|exists:kegiatans,id',
            'subkegiatan_id'    => 'required|exists:subkegiatans,id',
            'jenis_dipilih'     => 'required|string|max:100',
            'rekening_kegiatan' => 'required|string|max:255',
            'tahun_input'       => 'required|numeric|min:2000|max:2100',
            'nominal_pagu'      => 'required|numeric|min:0',
            'nominal'           => 'required|numeric|min:0|lte:nominal_pagu',
        ];

        if (!$this->editingId) {
            $rules['file_upload'] = 'required|file|mimes:pdf|max:5120';
        }

        return $rules;
    }

    public function render()
    {
        $query = Pelaporan::with(['pptk', 'kegiatan', 'subkegiatan']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('jenis_belanja', 'like', "%{$this->search}%")
                    ->orWhere('rekening_kegiatan', 'like', "%{$this->search}%")
                    ->orWhereHas('kegiatan', fn ($q2) => $q2->where('nama_kegiatan', 'like', "%{$this->search}%"))
                    ->orWhereHas('subkegiatan', fn ($q2) => $q2->where('nama_subkegiatan', 'like', "%{$this->search}%"));
            });
        }

        $query->orderBy('created_at', $this->sort === 'created_asc' ? 'asc' : 'desc');
        $laporan = $query->get();

        $pptks = User::role('pptk')->get();
        $kegiatans = Kegiatan::all();
        $subkegiatans = Subkegiatan::all();

        return view('livewire.pages.pelaporan.daftar-pelaporan', compact(
            'laporan', 'pptks', 'kegiatans', 'subkegiatans'
        ));
    }

    public function updatedSubkegiatanId($value)
    {
        $sub = Subkegiatan::find($value);
        if ($sub) {
            $this->rekening_kegiatan = $sub->rekening;
            $this->nominal_pagu = $sub->jumlah_pagu;
        } else {
            $this->rekening_kegiatan = '';
            $this->nominal_pagu = 0;
        }
    }

    public function updatedJenisDipilih()
    {
        $this->requiredBerkas = [];

        if ($this->jenis_dipilih) {
            $jenis = JenisBelanjaPelaporan::where('nama', $this->jenis_dipilih)->first();

            if ($jenis) {
                $templates = TemplateBerkasBelanja::where('jenis_belanja_id', $jenis->id)->get();

                foreach ($templates as $item) {
                    $this->requiredBerkas[$this->jenis_dipilih][] = $item->nama_berkas;
                }
            }
        }
    }

    public function uploadSatuBerkas()
{
    if (!$this->pelaporan_id_aktif) {
        $this->addError('berkas_upload', 'Pelaporan belum disimpan. Simpan dulu sebelum mengunggah berkas.');
        return;
    }

    $this->validate([
        'berkas_upload' => 'required|file|mimes:pdf|max:5120',
        'jenis_dipilih' => 'required|string',
        'nama_berkas_dipilih' => 'required|string',
    ]);

    $jenis = \App\Models\JenisBelanjaPelaporan::where('nama', $this->jenis_dipilih)->first();

    if (!$jenis) {
        $this->addError('jenis_dipilih', 'Jenis belanja tidak ditemukan.');
        return;
    }

    $template = \App\Models\TemplateBerkasBelanja::where('jenis_belanja_id', $jenis->id)
        ->where('nama_berkas', $this->nama_berkas_dipilih)
        ->first();

    if (!$template) {
        $this->addError('nama_berkas_dipilih', 'Template berkas tidak ditemukan.');
        return;
    }

    $ada = \App\Models\BerkasPelaporan::where('pelaporan_id', $this->pelaporan_id_aktif)
        ->where('template_berkas_id', $template->id)
        ->exists();

    if ($ada) {
        $this->addError('nama_berkas_dipilih', 'Berkas ini sudah diunggah sebelumnya.');
        return;
    }

    $path = $this->berkas_upload->store('pelaporan', 'public');

    \App\Models\BerkasPelaporan::create([
        'pelaporan_id' => $this->pelaporan_id_aktif,
        'template_berkas_id' => $template->id,
        'nama_file' => $this->berkas_upload->getClientOriginalName(),
        'path_file' => $path,
        'size_file' => $this->berkas_upload->getSize(),
    ]);

    $this->reset(['berkas_upload', 'nama_berkas_dipilih']);

    $this->dispatchBrowserEvent('notify', ['title' => 'Berkas berhasil diupload']);
}


    public function submit()
{
    $this->validate($this->rules() + [
        'berkas_upload' => 'required|file|mimes:pdf|max:5120',
        'nama_berkas_dipilih' => 'required|string',
    ]);

    // Simpan pelaporan utama
    $filePath = $this->file_upload?->store('pelaporan', 'public');

    $pelaporan = Pelaporan::create([
        'pptk_id' => $this->pptk_id,
        'kegiatan_id' => $this->kegiatan_id,
        'subkegiatan_id' => $this->subkegiatan_id,
        'jenis_belanja' => $this->jenis_dipilih,
        'rekening_kegiatan' => $this->rekening_kegiatan,
        'tahun' => $this->tahun_input,
        'nominal_pagu' => $this->nominal_pagu,
        'nominal' => $this->nominal,
        'status' => 'Diajukan',
        'file_path' => $filePath,
        'catatan' => $this->catatan,
    ]);

    $this->pelaporan_id_aktif = $pelaporan->id;

    // Simpan berkas tambahan jika dipilih
    if ($this->berkas_upload && $this->nama_berkas_dipilih) {
        $jenis = JenisBelanjaPelaporan::where('nama', $this->jenis_dipilih)->first();
        $template = TemplateBerkasBelanja::where('jenis_belanja_id', $jenis->id)
            ->where('nama_berkas', $this->nama_berkas_dipilih)
            ->first();

        if ($template) {
            $path = $this->berkas_upload->store('pelaporan', 'public');

            BerkasPelaporan::create([
                'pelaporan_id' => $pelaporan->id,
                'template_berkas_id' => $template->id,
                'nama_file' => $this->berkas_upload->getClientOriginalName(),
                'path_file' => $path,
                'size_file' => $this->berkas_upload->getSize(),
            ]);
        }
    }

    $this->dispatch('notify', title: 'Pelaporan dan berkas berhasil disimpan.');
    $this->resetForm();
}


    public function resetForm()
    {
        $this->reset([
            'showModal', 'pptk_id', 'kegiatan_id', 'subkegiatan_id',
            'rekening_kegiatan', 'tahun_input', 'nominal_pagu', 'nominal',
            'file_upload', 'catatan', 'editingId', 'jenis_dipilih',
            'jenis_belanja_terpilih', 'requiredBerkas', 'berkas_per_jenis',
            'nama_berkas_dipilih', 'berkas_upload', 'pelaporan_id_aktif'
        ]);
    }

    public function edit($id)
    {
        $laporan = Pelaporan::findOrFail($id);
        $this->resetErrorBag();
        $this->resetValidation();

        $this->showModal = true;
        $this->editingId = $laporan->id;

        $this->pptk_id = $laporan->pptk_id;
        $this->kegiatan_id = $laporan->kegiatan_id;
        $this->subkegiatan_id = $laporan->subkegiatan_id;
        $this->rekening_kegiatan = $laporan->rekening_kegiatan;
        $this->tahun_input = $laporan->tahun;
        $this->nominal_pagu = $laporan->nominal_pagu;
        $this->nominal = $laporan->nominal;
        $this->catatan = $laporan->catatan;
        $this->jenis_dipilih = $laporan->jenis_belanja;
    }

    public function triggerDelete($id)
    {
        $this->delete($id);
    }

    public function delete($id)
    {
        $laporan = Pelaporan::findOrFail($id);
        $laporan->delete();
        $this->dispatch('notify', title: 'Laporan berhasil dihapus.');
    }
}
