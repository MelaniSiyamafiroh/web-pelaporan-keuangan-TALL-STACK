<?php

namespace App\Livewire\Pages\Kelola;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\SatuanKerja;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaAkunPage extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'created_desc';

    // Form fields
    public $name;
    public $email;
    public $role;
    public $password;
    public $satuan_kerja_id;
    public $editing_id = null;

    public $allRoles = [];
    public $allSatuanKerja = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->editing_id)
            ],
            'role' => 'required|string',
            'satuan_kerja_id' => 'nullable|exists:satuan_kerjas,id',
            'password' => $this->editing_id ? 'nullable|min:6' : 'required|min:6'
        ];
    }

    protected $listeners = ['edit' => 'edit', 'destroy' => 'destroy'];

    public function mount()
    {
        $this->allRoles = Role::pluck('name')->toArray();
        $this->allSatuanKerja = SatuanKerja::all();
    }

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

        return view('livewire.pages.kelola.kelola-akun-page', [
            'users' => $query->paginate(10),
        ]);
    }

    public function store()
    {
        $validated = $this->validate();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        $user->assignRole($validated['role']);

        $this->resetForm();
        session()->flash('success', 'Akun berhasil ditambahkan.');
        $this->resetPage();
        $this->dispatch('closeModal');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editing_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->getRoleNames()->first();
        $this->satuan_kerja_id = $user->satuan_kerja_id;
        $this->password = '';
        $this->dispatch('openModal');
    }

    public function update()
    {
        $validated = $this->validate();
        $user = User::findOrFail($this->editing_id);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->satuan_kerja_id = $validated['satuan_kerja_id'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->syncRoles([$validated['role']]);

        $this->resetForm();
        session()->flash('success', 'Akun berhasil diperbarui.');
        $this->dispatch('closeModal');
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
        $this->satuan_kerja_id = null;
        $this->editing_id = null;
    }
}
