<x-card class="min-h-full">
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Pengaturan</h2>
    </x-slot>

    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex-1 mb-2">
                <div class="flex flex-col space-y-2">
                    <x-input label="Nama Usaha" model="name" inline="false"/>
                    <x-input label="Alamat" model="address" inline="false"/>
                    <x-input label="Telepon" model="telephone" inline="false"/>
                    <x-input type="number" min="1" label="Jml. Meja" model="number_of_tables" inline="false"/>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex flex-col space-y-2">
                    <x-dropzone accept="image/*" label="Logo" model="fileLogo" fileurl="logo.png" inline="false">
                        @if($fileLogo)
                            <img src="{{ $fileLogo->temporaryUrl() }}" width="250">
                        @else
                            <img src="/storage/setting/logo.png" width="250">
                        @endif
                    </x-dropzone>
                    <x-dropzone accept="image/*" label="Favicon" model="fileFavicon" fileurl="logo.png" inline="false">
                        @if($fileFavicon)
                            <img src="{{ $fileFavicon->temporaryUrl() }}" width="32">
                        @else
                            <img src="/storage/setting/favicon.png" width="32">
                        @endif
                    </x-dropzone>
                </div>
            </div>
        </div>

        <x-button type="submit" color="primary" class="mt-2">
            <x-fas-save class="h-4 w-4"/>
            <span> Simpan </span>
        </x-button>
    </form>

    <x-alert/>
</x-card>
