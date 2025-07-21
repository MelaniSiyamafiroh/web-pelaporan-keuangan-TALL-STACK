<div x-data="{ open: false }"
     x-show="open"
     x-on:open-verifikasi.window="if ($event.detail.id) { $wire.loadLaporan($event.detail.id); open = true }"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

    <div @click.away="open = false"
         class="bg-white rounded-xl shadow-lg max-w-3xl w-full p-6 overflow-y-auto max-h-[90vh]">

        <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">
            Verifikasi / Revisi Laporan
        </h2>

        @if($selectedLaporan)
            {{-- DETAIL LAPORAN --}}
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 mb-4">
                <div><strong>PPTK:</strong> {{ optional($selectedLaporan->user)->name ?? '-' }}</div>
                <div><strong>Kegiatan:</strong> {{ optional(optional($selectedLaporan->subkegiatan)->kegiatan)->nama ?? '-' }}</div>
                <div><strong>Subkegiatan:</strong> {{ optional($selectedLaporan->subkegiatan)->nama ?? '-' }}</div>
                <div><strong>Rekening:</strong> {{ $selectedLaporan->rekening ?? '-' }}</div>
                <div><strong>Tahun:</strong> {{ $selectedLaporan->tahun ?? '-' }}</div>
                <div><strong>Nominal Pagu:</strong> Rp{{ number_format($selectedLaporan->nominal_pagu ?? 0) }}</div>
                <div><strong>Nominal Anggaran:</strong> Rp{{ number_format($selectedLaporan->nominal ?? 0) }}</div>
                <div><strong>Jenis Belanja:</strong> {{ strtoupper($selectedLaporan->jenis_belanja ?? '-') }}</div>
            </div>

            {{-- CEKLIS DOKUMEN --}}
            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">Checklist Dokumen:</label>
                @foreach ($daftarBerkasWajib as $namaBerkas)
                    @php
                        $berkas = optional($selectedLaporan->berkas)->firstWhere('nama_berkas', $namaBerkas);
                    @endphp
                    <label class="flex items-center space-x-2 mb-1">
                        <input type="checkbox"
                               wire:model.defer="ceklistDokumen"
                               value="{{ $namaBerkas }}"
                               class="form-checkbox text-blue-600">
                        <span>{{ $namaBerkas }}</span>

                        @if ($berkas)
                            <a href="{{ Storage::url($berkas->file_path) }}"
                               target="_blank"
                               class="ml-2 text-blue-600 hover:underline text-xs">
                                Preview PDF
                            </a>
                        @else
                            <span class="ml-2 text-red-500 text-xs">Belum diunggah</span>
                        @endif
                    </label>
                @endforeach
                @error('ceklistDokumen') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- CATATAN --}}
            <div class="mb-4">
                <label for="catatan" class="block text-sm font-medium text-gray-600 mb-1">
                    Catatan <span class="text-gray-400 italic">(Opsional jika revisi)</span>
                </label>
                <textarea wire:model.defer="catatan"
                          id="catatan"
                          rows="3"
                          class="form-textarea w-full border-gray-300 rounded focus:ring focus:ring-blue-200"></textarea>

            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex justify-end gap-2 mt-4">
                <button wire:click="verifikasi"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm transition">
                    <i class="fas fa-check mr-1"></i> Verifikasi
                </button>

                <button wire:click="revisi"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow text-sm transition">
                    <i class="fas fa-undo mr-1"></i> Revisi
                </button>

                <button @click="open = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded shadow text-sm transition">
                    Batal
                </button>
            </div>
        @else
            <div class="text-center text-red-500 text-sm italic mt-4">
                Data laporan belum dimuat.
            </div>
        @endif
    </div>
</div>
