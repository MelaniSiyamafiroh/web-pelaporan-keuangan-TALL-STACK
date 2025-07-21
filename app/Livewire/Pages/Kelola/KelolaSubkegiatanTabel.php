<?php

namespace App\Livewire\Pages\Kelola;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\SubKegiatan;

class KelolaSubkegiatanTabel extends DataTableComponent
{
    protected $model = SubKegiatan::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")->sortable(),
            Column::make("Kegiatan")
                ->label(fn ($row) => $row->kegiatan->nama_kegiatan ?? '-') // ✅ tampilkan dari relasi
                ->sortable(),
            Column::make("Nama Subkegiatan", "nama_subkegiatan")->sortable(),
            Column::make("Tahun Anggaran", "tahun_anggaran")->sortable(),
            Column::make("Rekening", "rekening")->sortable(),
            Column::make("Jumlah Pagu", "jumlah_pagu")->sortable(),
            Column::make("Created At", "created_at")->sortable(),
            Column::make("Updated At", "updated_at")->sortable(),
        ];
    }
}
