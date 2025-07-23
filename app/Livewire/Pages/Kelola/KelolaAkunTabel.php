<?php

namespace App\Livewire\Pages\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class KelolaAkunTabel extends DataTableComponent
{
    protected $model = User::class;

    public string $search = '';
    public string $roleFilter = ''; // untuk dropdown filter role

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchEnabled(); // aktifkan pencarian global
        $this->setPaginationEnabled(); // aktifkan pagination
    }

    public function builder(): Builder
    {
        $query = User::query()->with(['satuanKerja', 'roles']);

        // Jika filtering berdasarkan role
        if ($this->roleFilter) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        return $query->latest(); // default sorting by created_at desc
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->dispatch('$refresh');
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "name")
                ->sortable()
                ->searchable(),

            Column::make("Email", "email")
                ->collapseOnMobile()
                ->searchable(),

            Column::make("Satuan Kerja")
                ->label(fn($row) => $row->satuanKerja->nama ?? '-')
                ->collapseOnMobile(),

            Column::make("Role")
                ->label(fn($row) => $row->roles->pluck('name')->implode(', ') ?: '-')
                ->collapseOnMobile(),

            Column::make("Dibuat", "created_at")
                ->sortable()
                ->collapseOnMobile()
                ->deselected(), // sembunyikan secara default

            Column::make("Aksi", "id")
                ->label(fn($row) => view('livewire.pages.kelola.kelola-akun-action', ['value' => $row->id]))
                ->collapseOnMobile(),
        ];
    }
}
