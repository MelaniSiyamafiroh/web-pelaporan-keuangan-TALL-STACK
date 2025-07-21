<x-card class="min-h-full">
    {{-- Judul halaman dan tombol tambah --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Data User</h2>
        <div class="flex space-x-2">
            <x-button onclick="isModalOpen = true" color="primary">
                <x-fas-plus-circle class="h-4 w-4"/>
                <span> Tambah </span>
            </x-button>
        </div>
    </x-slot>

    {{-- Menerapkan komponen tabel --}}
    <div class="w-full overflow-x-auto">
        <livewire:pages.user.user-tabel />
    </div>

    {{-- Menampilkan form pada modal --}}
    <form wire:submit.prevent="save">
        <x-modal class="md:w-1/2">
            <x-slot name="header">
                <h3>{{($isEdit) ? "Edit" : "Tambah"}} Data User</h3>
            </x-slot>
            <div class="flex flex-col space-y-2">
                <x-input label="Nama User" model="name"/>
                <x-input label="Email" model="email"/>
                <x-input type="password" label="Password" model="password"/>
            </div>
        </x-modal>
    </form>

    {{-- Menerapkan komponen confirm dan alert --}}
    <x-confirm>Yakin akan menghapus data?</x-confirm>
    <x-alert/>
</x-card>
