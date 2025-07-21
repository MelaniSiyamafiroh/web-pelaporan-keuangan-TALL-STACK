<x-card class="min-h-full">
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Profil User</h2>
    </x-slot>

    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex-1 mb-2">
                <div class="flex flex-col space-y-2">
                    <x-input label="Nama User" model="name" inline="false"/>
                    <x-input label="Email" model="email" inline="false"/>
                    <x-input type="password" label="Password" model="password" inline="false"/>
                </div>
            </div>
            <div class="flex-1">
                <x-dropzone accept="image/*" label="Foto Profil" model="filePhoto" fileurl="{{$photo}}" inline="false">
                    @if($filePhoto)
                        <img src="{{ $filePhoto->temporaryUrl() }}" width="150">
                    @elseif($photo)
                        <img src="/storage/user/{{$photo}}" width="150">
                    @endif
                </x-dropzone>
            </div>
        </div>

        <x-button type="submit" color="primary" class="mt-2">
            <x-fas-save class="h-4 w-4"/>
            <span> Simpan </span>
        </x-button>
    </form>

    <x-alert/>
</x-card>
