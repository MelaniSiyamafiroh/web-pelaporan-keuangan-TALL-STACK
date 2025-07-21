<div class="flex gap-2">
    <button wire:click="$dispatch('edit-kegiatan', { id: {{ $row->id }} })"
        class="text-yellow-600 hover:text-yellow-800">
        <i class="fas fa-edit"></i> Edit
    </button>

    <button wire:click="$emit('deleteKegiatan', {{ $row->id }})"
        onclick="confirm('Yakin ingin menghapus kegiatan ini?') || event.stopImmediatePropagation()"
        class="text-red-600 hover:text-red-800">
        <i class="fas fa-trash"></i> Hapus
    </button>
</div>
