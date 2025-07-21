<?php

namespace App\Livewire\Pages\Pelaporan;

use App\Models\Kegiatan;
use App\Models\Subkegiatan;
use App\Models\User;
use Livewire\Component;

class ModalForm extends Component
{
    public $pptks;
    public $kegiatans;
    public $subkegiatans;

    public function mount()
    {
        $tahun = session('tahun_aktif') ?? now()->year;

        $this->pptks = User::role('pptk')->get();
        $this->kegiatans = Kegiatan::where('tahun', $tahun)->get();
        $this->subkegiatans = Subkegiatan::where('tahun_anggaran', $tahun)->get();
    }

    public function render()
    {
        return view('livewire.pages.pelaporan.modal-form');
    }
}
