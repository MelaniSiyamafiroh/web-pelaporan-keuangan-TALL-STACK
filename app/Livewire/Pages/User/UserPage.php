<?php

namespace App\Livewire\Pages\User;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\SatuanKerja;
use Spatie\Permission\Models\Role;
use Hash;

class UserPage extends Component
{
    public $idDelete, $isEdit = false;

    public $id, $name, $email, $password, $photo;
    public $satuan_kerja_id, $role;

    public $listSatuanKerja = [];
    public $listRole = [];

    public function mount()
    {
        $this->listSatuanKerja = SatuanKerja::all();
        $this->listRole = Role::pluck('name')->toArray();
    }

    public function render()
    {
        return view('livewire.pages.user.user-page');
    }

    #[On('edit')]
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->isEdit = true;

        $this->fill([
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "satuan_kerja_id" => $user->satuan_kerja_id,
            "photo" => $user->photo,
            "role" => $user->roles->first()?->name,
            "password" => null,
        ]);
        $this->dispatch('open-modal');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . ($this->isEdit ? $this->id : 'NULL'),
            'password' => (!$this->isEdit) ? 'required' : 'nullable',
            'role' => 'required|exists:roles,name',
        ]);

        $user = $this->isEdit ? User::findOrFail($this->id) : new User;
        $user->fill([
            'name' => $this->name,
            'email' => $this->email,
            'satuan_kerja_id' => $this->satuan_kerja_id,
            'photo' => $this->photo,
        ]);

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();
        $user->syncRoles([$this->role]);

        $this->isEdit = false;
        $this->dispatch('refresh')->to(UserTabel::class);
        $this->dispatch('close-modal');
        $this->dispatch('show-message', msg: 'Data berhasil disimpan');
        $this->resetForm();
    }

    #[On('confirm')]
    public function confirm($id)
    {
        $this->idDelete = $id;
    }

    public function delete()
    {
        $user = User::find($this->idDelete);
        if ($user) {
            $user->delete();
            $this->dispatch('refresh')->to(UserTabel::class);
            $this->dispatch('show-message', msg: 'Data berhasil dihapus');
        }
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->reset(['id', 'name', 'email', 'password', 'satuan_kerja_id', 'photo', 'role', 'isEdit']);
    }
}
