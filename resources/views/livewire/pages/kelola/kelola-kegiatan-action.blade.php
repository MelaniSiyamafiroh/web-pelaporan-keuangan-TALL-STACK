<div class="flex justify-center gap-2">

    <!-- Tombol Edit -->
    <button
        wire:click="edit({{ $row->id }})"
        @click="$dispatch('open-modal')"
        class="bg-purple-600 hover:bg-purple-700 text-white rounded-full p-2"
        title="Edit Kegiatan">
        <i class="fas fa-edit"></i>
    </button>

    <!-- Tombol Hapus -->
    <button
        wire:click="delete({{ $row->id }})"
        onclick="confirm('Yakin ingin menghapus kegiatan ini?') || event.stopImmediatePropagation()"
        class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2"
        title="Hapus Kegiatan">
        <i class="fas fa-trash-alt"></i>
    </button>

</div>
