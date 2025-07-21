<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->dispatch('$refresh');
    }
};
?>

<div class="w-full text-white px-1 py-0 flex items-center justify-between">

    {{-- Kiri: Dropdown Tahun --}}
    <div class="flex items-center gap-x-10">
        <x-tahun-selector :daftarTahun="range(date('Y') - 1, date('Y') + 5)" />
    </div>

    {{-- Kanan: Dropdown User --}}
    <div class="flex items-center gap-x-4">

        <x-dropdown contentClasses="p-2 bg-white dark:bg-dark">

            {{-- Tombol menu user --}}
            <x-slot name="trigger">
                <div class="flex items-center gap-x-2">
                    {{-- Foto Profil --}}
                    <div class="w-9 h-9 rounded-full border overflow-hidden">
                        @if(Auth::check() && Auth::user()->photo)
                            <img src="{{ asset('storage/user/' . Auth::user()->photo) }}" alt="User Photo" class="object-cover w-full h-full">
                        @else
                            <img src="{{ asset('storage/default/user_default.png') }}" alt="Default User Photo" class="object-cover w-full h-full">
                        @endif
                    </div>

                    {{-- Nama --}}
                    @if(Auth::check())
                        <span class="hidden md:inline text-sm font-medium text-white">
                            {{ Auth::user()->name }}
                        </span>
                    @endif

                    <x-fas-angle-down class="hidden md:inline w-3 h-3 text-white"/>
                </div>
            </x-slot>

            {{-- Menu Dropdown --}}
            <x-slot name="content">
                <x-menu link="/profil">Profil Akun</x-menu>
                <button wire:click="logout" class="w-full text-start">
                    <x-menu link="">Logout</x-menu>
                </button>
            </x-slot>

        </x-dropdown>

    </div>
</div>
