<?php

namespace App\Livewire\Pages\User;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $photo;
    public $filePhoto;

    public function mount()
    {
        $user = Auth::user();
        $this->fill([
            "name" => $user->name,
            "email" => $user->email,
            "password" => null,
            "photo" => $user->photo,
        ]);
    }

    public function render()
    {
        return view('livewire.pages.user.user-profile');
    }

    public function save()
    {
        $userId = Auth::id();

        // Validasi email unik tapi abaikan milik sendiri
        $this->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
        ]);

        // Upload foto profil jika ada
        if ($this->filePhoto) {
            $namafile = time() . "_" . preg_replace('/[^a-zA-Z0-9\._-]/', '', $this->filePhoto->getClientOriginalName());
            $this->filePhoto->storeAs('public/user', $namafile);
        } else {
            $namafile = null;
        }

        // Update data user
        $user = User::find($userId);
        $user->name = $this->name;
        $user->email = $this->email;
        if ($this->password) {
            $user->password = Hash::make($this->password);
        }
        if ($namafile) {
            $user->photo = $namafile;
        }
        $user->save();

        // Refresh navbar/avatar dan tampilkan pesan
        $this->dispatch('refresh')->to('layout.navigation');
        $this->dispatch('show-message', msg: 'Profil berhasil diperbarui');
    }
}
