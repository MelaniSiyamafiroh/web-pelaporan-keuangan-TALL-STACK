<div x-data>
    <div class="flex gap-2 mt-2">
        <button @click="$dispatch('open-verifikasi', { id: {{ $item->id }} })"
                wire:click="setSelectedId({{ $item->id }})"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm shadow transition">
            <i class="fas fa-check mr-1"></i> Verifikasi
        </button>

        <button @click="$dispatch('open-verifikasi', { id: {{ $item->id }} })"
                wire:click="setSelectedId({{ $item->id }})"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm shadow transition">
            <i class="fas fa-undo mr-1"></i> Revisi
        </button>
    </div>
</div>
