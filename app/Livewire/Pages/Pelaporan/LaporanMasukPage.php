<?php

namespace App\Livewire\Pages\Pelaporan;

use Livewire\Component;
use App\Models\Pelaporan;
use App\Models\VerifikasiLaporan;
use App\Models\Kegiatan;
use App\Models\Subkegiatan;
use Illuminate\Support\Facades\Auth;

class LaporanMasukPage extends Component
{
    public $search = '';
    public $pptkFilter = '';
    public $tahunFilter = '';
    public $sort = 'created_desc';

    public $selectedId = null;
    public $catatan = '';
    public $ceklistDokumen = [];
    public $selectedLaporan;

    public function mount()
    {
        if (!$this->tahunFilter) {
            $this->tahunFilter = session('tahun_aktif') ?? date('Y');
        }
    }

    public function applyFilter()
    {
        // Kosong karena Livewire reactive
    }

    public function setSelectedId($id)
    {
        $this->selectedId = $id;
    }

    public function loadLaporan($id)
    {
        $this->selectedLaporan = Pelaporan::with(['user', 'subkegiatan.kegiatan', 'berkas'])->findOrFail($id);
        $this->ceklistDokumen = [];
        $this->catatan = '';
    }

    public function getDaftarBerkasWajibProperty()
    {
        $jenis = strtolower($this->selectedLaporan->jenis_belanja ?? '');
        return config("berkas_wajib.{$jenis}", []);
    }

    public function verifikasi($id = null)
    {
        $laporanId = $id ?? $this->selectedLaporan->id;
        $user = Auth::user();
        $laporan = Pelaporan::findOrFail($laporanId);

        VerifikasiLaporan::create([
            'dpa_skpd_id' => $laporan->id,
            'verifikator_id' => $user->id,
            'tanggal_verifikasi' => now(),
            'catatan' => '-',
            'status' => 'Disetujui',
        ]);

        if ($user->hasRole('verifikator')) {
            $laporan->status = 'Disetujui Verifikator';
        } elseif ($user->hasRole('bendahara')) {
            $laporan->status = 'Disetujui Bendahara';
        } elseif ($user->hasRole('kepala_dinas')) {
            $laporan->status = 'Disetujui Kepala Dinas';
        }

        $laporan->save();

        $this->reset(['selectedLaporan', 'selectedId', 'catatan', 'ceklistDokumen']);
        session()->flash('success', 'Laporan berhasil diverifikasi.');
    }

    public function revisi($id = null)
    {
        $this->validate([
            'catatan' => 'required|string',
        ]);

        $laporanId = $id ?? $this->selectedLaporan->id;
        $user = Auth::user();
        $laporan = Pelaporan::findOrFail($laporanId);

        VerifikasiLaporan::create([
            'dpa_skpd_id' => $laporan->id,
            'verifikator_id' => $user->id,
            'tanggal_verifikasi' => now(),
            'catatan' => $this->catatan,
            'status' => 'Revisi',
        ]);

        $laporan->status = 'Perlu Revisi';
        $laporan->catatan = $this->catatan;
        $laporan->save();

        $this->reset(['selectedLaporan', 'selectedId', 'catatan', 'ceklistDokumen']);
        session()->flash('success', 'Laporan dikembalikan untuk revisi.');
    }

    public function render()
    {
        $kegiatans = Kegiatan::where('tahun', $this->tahunFilter)
            ->orderBy('nama_kegiatan')
            ->get();

        $subkegiatans = Subkegiatan::where('tahun_anggaran', $this->tahunFilter)
            ->orderBy('nama_subkegiatan')
            ->get();

        $laporanQuery = Pelaporan::with(['pptk', 'kegiatan', 'subkegiatan', 'berkas'])
            ->whereHas('subkegiatan', function ($q) {
                $q->where('tahun_anggaran', $this->tahunFilter);
            });

        if ($this->search) {
            $laporanQuery->where(function ($q) {
                $q->where('jenis_belanja', 'like', '%' . $this->search . '%')
                  ->orWhere('rekening_kegiatan', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->pptkFilter) {
            $laporanQuery->where('pptk_id', $this->pptkFilter);
        }

        // Sorting
        match ($this->sort) {
            'created_asc' => $laporanQuery->orderBy('created_at', 'asc'),
            'nama_asc'    => $laporanQuery->orderBy('jenis_belanja', 'asc'),
            'nama_desc'   => $laporanQuery->orderBy('jenis_belanja', 'desc'),
            default       => $laporanQuery->orderBy('created_at', 'desc'),
        };

        $laporanMasuk = $laporanQuery->get();

        return view('livewire.pages.pelaporan.laporan-masuk-page', [
            'laporanMasuk' => $laporanMasuk,
            'kegiatans' => $kegiatans,
            'subkegiatans' => $subkegiatans,
        ]);
    }
}
