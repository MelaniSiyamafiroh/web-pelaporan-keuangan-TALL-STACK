<?php

namespace App\Livewire\Components;

use Livewire\Component;

class TahunSelector extends Component
{
    public $tahun;
    public $daftarTahun = [];

    public function mount()
    {
        $this->tahun = session('tahun_aktif', date('Y'));
        $this->daftarTahun = range(date('Y'), 2022);
    }

    public function updatedTahun($value)
    {
        session(['tahun_aktif' => $value]);
        $this->dispatch('tahun-updated', tahun: $value);
    }

    public function render()
    {
        return view('livewire.components.tahun-selector');
    }
}
