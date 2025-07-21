<?php

namespace App\Livewire\Pages\Pelaporan;

use App\Models\Kegiatan;
use App\Models\Pelaporan;
use App\Models\Subkegiatan;
use App\Models\User;
use App\Models\SatuanKerja;
use App\Models\BerkasPelaporan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\TemplateBerkasBelanja;



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
    public $jenis_belanja, $rekening_kegiatan, $nominal_pagu, $nominal;
    public $file_upload, $catatan = '-';

    // 🆕 Tambahan untuk validasi & simpan berkas dinamis
    public $requiredBerkas = ['RAB', 'SPJ', 'Foto'];
    public $berkas = [];
    public $jenis_belanja_terpilih = []; // [spj_gu, spj_gu_tunai]
    public $berkas_per_jenis = []; // nested: ['spj_gu' => ['A2' => File, ...]]
    public $jenis_dipilih;       // untuk dropdown upload
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
        $this->tahun = session('tahun_aktif') ?? now()->year;
    }

    public function rules()
    {
        $rules = [
            'pptk_id'           => 'required|exists:users,id',
            'kegiatan_id'       => 'required|exists:kegiatans,id',
            'subkegiatan_id'    => 'required|exists:subkegiatans,id',
            'jenis_belanja'     => 'required|string|max:100',
            'rekening_kegiatan' => 'required|string|max:255',
            'tahun_input'       => 'required|numeric|min:2000|max:2100',
            'nominal_pagu'      => 'required|numeric|min:0',
            'nominal'           => 'required|numeric|min:0|lte:nominal_pagu',
        ];

        // Hanya wajib upload file jika tambah baru
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
        $kegiatans = Kegiatan::where('tahun', $this->tahun)->get();
        $subkegiatans = Subkegiatan::where('tahun_anggaran', $this->tahun)->get();

        $this->requiredBerkas = []; // reset dulu

$templates = TemplateBerkasBelanja::whereIn('jenis_belanja', $this->jenis_belanja_terpilih)->get();

foreach ($templates as $template) {
    // ✅ ini penting: gunakan [] untuk menjadikan array
    $this->requiredBerkas[$template->jenis_belanja][] = $template->nama_berkas;
}


        return view('livewire.pages.pelaporan.daftar-pelaporan', compact(
            'laporan', 'pptks', 'kegiatans', 'subkegiatans'
        ));
    }

    public function submit()
{
    $this->validate($this->rules());

    if ($this->editingId) {
        // proses update seperti biasa...
    } else {
        // ✅ Validasi file dinamis
        foreach ($this->requiredBerkas as $jenis => $list) {
            foreach ($list as $nama) {
                $this->validate([
                    'berkas_per_jenis.' . $jenis . '.' . $nama => 'required|file|mimes:pdf|max:5120',
                ]);
            }
        }

        // ✅ Simpan pelaporan utama
        $filePath = $this->file_upload->store('pelaporan', 'public');

        $pelaporan = Pelaporan::create([
            'pptk_id'           => $this->pptk_id,
            'kegiatan_id'       => $this->kegiatan_id,
            'subkegiatan_id'    => $this->subkegiatan_id,
            'jenis_belanja'     => implode(',', $this->jenis_belanja_terpilih), // bisa disimpan sebagai string atau dibuat tabel pivot nanti
            'rekening_kegiatan' => $this->rekening_kegiatan,
            'tahun'             => $this->tahun_input,
            'nominal_pagu'      => $this->nominal_pagu,
            'nominal'           => $this->nominal,
            'status'            => 'Diajukan',
            'file_path'         => $filePath,
            'catatan'           => $this->catatan,
        ]);

        // ✅ Simpan berkas ke tabel berkas_pelaporan
        foreach ($this->berkas_per_jenis as $jenis => $files) {
            foreach ($files as $nama => $file) {
                $path = $file->store('pelaporan', 'public');

                BerkasPelaporan::create([
                    'pelaporan_id' => $pelaporan->id,
                    'jenis_belanja' => $jenis,
                    'nama_berkas' => $nama,
                    'file_path' => $path,
                ]);
            }
        }

        $this->dispatch('notify', title: 'Pelaporan berhasil disimpan.');
        $this->pelaporan_id_aktif = $pelaporan->id;

    }

    $this->resetForm();
}


    public function resetForm()
    {
        $this->reset([
            'showModal',
            'pptk_id',
            'kegiatan_id',
            'subkegiatan_id',
            'jenis_belanja',
            'rekening_kegiatan',
            'tahun_input',
            'nominal_pagu',
            'nominal',
            'file_upload',
            'catatan',
            'editingId',
            'berkas',
        ]);
    }

    public function edit($id)
    {
        $laporan = Pelaporan::findOrFail($id);

        $this->resetErrorBag();
        $this->resetValidation();

        $this->showModal = true;

        $this->pptk_id           = $laporan->pptk_id;
        $this->kegiatan_id       = $laporan->kegiatan_id;
        $this->subkegiatan_id    = $laporan->subkegiatan_id;
        $this->jenis_belanja     = $laporan->jenis_belanja;
        $this->rekening_kegiatan = $laporan->rekening_kegiatan;
        $this->tahun_input       = $laporan->tahun;
        $this->nominal_pagu      = $laporan->nominal_pagu;
        $this->nominal           = $laporan->nominal;
        $this->catatan           = $laporan->catatan;

        $this->editingId = $laporan->id;
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

   public function updatedJenisBelanjaTerpilih()
{
    $templates = TemplateBerkasBelanja::whereIn('jenis_belanja', $this->jenis_belanja_terpilih)->get();

    $this->requiredBerkas = [];
    foreach ($templates as $item) {
        $this->requiredBerkas[$item->jenis_belanja][] = $item->nama_berkas;
    }
}



public function loadTemplateBerkas()
{
    $this->requiredBerkas = [];

    if (!empty($this->jenis_belanja_terpilih)) {
        $templates = TemplateBerkasBelanja::whereIn('jenis_belanja', $this->jenis_belanja_terpilih)->get();

        foreach ($templates as $template) {
            $this->requiredBerkas[$template->jenis_belanja][] = $template->nama_berkas;
        }
    }
}

public function uploadSatuBerkas()
{
    $this->validate([
        'berkas_upload' => 'required|file|mimes:pdf|max:5120',
        'jenis_dipilih' => 'required|string',
        'nama_berkas_dipilih' => 'required|string',
    ]);

    $path = $this->berkas_upload->store('pelaporan', 'public');

    BerkasPelaporan::create([
        'pelaporan_id' => $this->pelaporan_id_aktif, // ID pelaporan sedang aktif
        'jenis_belanja' => $this->jenis_dipilih,
        'nama_berkas' => $this->nama_berkas_dipilih,
        'file_path' => $path,
    ]);

    // Reset input
    $this->reset(['berkas_upload', 'nama_berkas_dipilih']);

    $this->dispatchBrowserEvent('notify', ['title' => 'Berkas berhasil diupload']);
}

public function user()
{
    return $this->belongsTo(User::class, 'pptk_id');
}



}
