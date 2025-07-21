<div class="flex items-center space-x-2 text-white">
    <label for="tahun" class="text-sm hidden md:block">Tahun:</label>
    <select wire:model="tahun" id="tahun" class="text-black rounded px-2 py-1 text-sm">
        @foreach ($daftarTahun as $th)
            <option value="{{ $th }}">{{ $th }}</option>
        @endforeach
    </select>
</div>
