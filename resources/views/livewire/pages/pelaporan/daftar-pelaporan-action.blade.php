<div class="flex items-center gap-2">
    <!-- Tombol Edit -->
    <x-button-circle
        color="yellow"
        icon="fas fa-edit"
        title="Edit Laporan"
        wire:click="edit({{ $item->id }})"
    />

    <!-- Tombol Hapus dengan konfirmasi -->
    <div class="flex items-center gap-2">
    <x-button-circle ... />
    <x-confirm wire:click="$emit('triggerDelete', {{ $item->id }})">
        <button type="button">
            <x-button-circle ... />
        </button>
    </x-confirm>
</div>

