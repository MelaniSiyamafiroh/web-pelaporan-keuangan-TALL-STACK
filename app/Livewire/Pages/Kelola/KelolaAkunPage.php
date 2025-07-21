<?php

namespace App\Livewire\Pages\Kelola;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KelolaAkunPage extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'created_desc';

    // Form tambah/edit
    public $name;
    public $email;
    public $role;
    public $password;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|string',
        'password' => 'required|min:6'
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
        }

        $users = $query->paginate(10);

        return view('livewire.pages.kelola.kelola-akun-page', [
            'users' => $users,
        ]);
    }

    public function store()
    {
        $validated = $this->validate();

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        $this->resetForm();

        session()->flash('success', 'Akun berhasil ditambahkan.');
        $this->resetPage(); // Kembali ke halaman pertama
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        session()->flash('success', 'Akun berhasil dihapus.');
    }

    public function applyFilter()
    {
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->password = '';
    }
}
