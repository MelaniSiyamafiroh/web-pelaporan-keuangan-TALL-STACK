<div class="flex justify-end space-x-1">
    <x-button-circle wireclick="$dispatchTo('pages.pelaporan.daftar-pelaporan', 'edit', { id: {{$value}} })">
        <x-fas-edit class="h-3 w-3 text-white" />
    </x-button-circle>
    <x-button-circle color="red-500"  onclick="isConfirmOpen=true"
        wireclick="$dispatchTo('pages.pelaporan.daftar-pelaporan', 'confirm', { id: {{$value}} })">
        <x-fas-trash class="h-3 w-3 text-white" />
    </x-button-circle>
</div>
