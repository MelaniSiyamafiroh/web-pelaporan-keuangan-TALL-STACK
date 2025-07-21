<?php

namespace App\Livewire\Pages\Kelola;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KelolaAkunTabel extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'created_desc';

    // Form fields
    public $name;
    public $email;
    public $role;
    public $password;

    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'role'     => 'required|string',
        'password' => 'required|min:6',
    ];

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        switch ($this->sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $users = $query->paginate(10);

        return view('livewire.pages.kelola.kelola-akun-action', [
            'users' => $users,
        ]);
    }

    public function applyFilter()
    {
        // Reset pagination saat filter diterapkan
        $this->resetPage();
    }

    public function store()
    {
        $this->validate();

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'role'     => $this->role,
            'password' => Hash::make($this->password),
        ]);

        $this->resetForm();
        session()->flash('success', 'Akun berhasil ditambahkan.');
        $this->dispatch('closeModal'); // optional: jika pakai event untuk close modal
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Akun berhasil dihapus.');
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->password = '';
    }
}
