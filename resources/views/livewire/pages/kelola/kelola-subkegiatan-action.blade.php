<div class="flex gap-2 justify-center">
    <!-- Tombol Edit -->
    <button
        wire:click.prevent="edit({{ $item->id }})"
        @click="openModal = true"
        class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-full p-2"
        title="Edit Sub Kegiatan"
    >
        <i class="fas fa-edit"></i>
    </button>

    <!-- Tombol Hapus -->
    <button
        wire:click.prevent="destroy({{ $item->id }})"
        onclick="confirm('Apakah Anda yakin ingin menghapus Sub Kegiatan ini?') || event.stopImmediatePropagation()"
        class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2"
        title="Hapus Sub Kegiatan"
    >
        <i class="fas fa-trash-alt"></i>
    </button>
</div>
