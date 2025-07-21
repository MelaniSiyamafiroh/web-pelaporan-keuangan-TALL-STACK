<?php

namespace App\Livewire\Pages\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UserTabel extends DataTableComponent
{
    protected $model = User::class;

    public string $search = '';
    public string $roleFilter = ''; // opsional

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setSearchEnabled(); // aktifkan fitur pencarian global
    }

    public function builder(): Builder
    {
        $query = User::with('satuanKerja', 'roles');

        if ($this->roleFilter) {
            $query->role($this->roleFilter); // dari spatie
        }

        return $query->latest();
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
                ->label(fn($row) => $row->roles->pluck('name')->implode(', '))
                ->collapseOnMobile(),

            Column::make("Dibuat", "created_at")
                ->sortable()
                ->collapseOnMobile()
                ->deselected(),

            Column::make("Aksi", "id")
                ->view('livewire.pages.user.user-action')
                ->collapseOnMobile(),
        ];
    }
}
