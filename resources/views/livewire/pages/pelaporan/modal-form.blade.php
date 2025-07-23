<div
    x-data="{ open: @entangle('showModal') }"
    x-show="open"
    x-init="$watch('open', val => document.body.classList.toggle('overflow-hidden', val))"
    class="fixed inset-0 z-50 bg-black/50 flex justify-center items-center overflow-auto"
    style="display: none"
>
    <div
        @click.away="open = false"
        class="bg-white rounded-lg shadow-lg max-w-3xl w-full mx-4 my-8 p-6 relative overflow-y-auto max-h-[90vh]"
    >
        <!-- Header -->
        <h2 class="text-lg font-semibold mb-4">Form Tambah Pelaporan</h2>

        <!-- Form -->
        <form wire:submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- PPTK -->
                <div>
                    <label class="block text-sm mb-1">PPTK</label>
                    <select wire:model.defer="pptk_id" class="w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih PPTK --</option>
                        @foreach($pptks ?? [] as $pptk)
                            <option value="{{ $pptk->id }}">{{ $pptk->name }}</option>
                        @endforeach
                    </select>
                    @error('pptk_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Kegiatan -->
                <div>
                    <label class="block text-sm mb-1">Kegiatan</label>
                    <select wire:model.lazy="kegiatan_id" class="w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach($kegiatans ?? [] as $kegiatan)
                            <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    @error('kegiatan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Subkegiatan -->
                <div>
                    <label class="block text-sm mb-1">Sub Kegiatan</label>
                    <select wire:model.lazy="subkegiatan_id" class="w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih Sub Kegiatan --</option>
                        @foreach($subkegiatans ?? [] as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->nama_subkegiatan }}</option>
                        @endforeach
                    </select>
                    @error('subkegiatan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Jenis Belanja Dropdown -->
                <div>
                    <label class="block text-sm mb-1">Jenis Belanja</label>
                    <select wire:model.lazy="jenis_dipilih" class="w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih Jenis Belanja --</option>
                        @foreach($jenis_belanja_terpilih ?? [] as $belanja)
                            <option value="{{ $belanja }}">{{ strtoupper($belanja) }}</option>
                        @endforeach
                    </select>
                    @error('jenis_dipilih') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Nama Berkas berdasarkan Jenis -->
                <div wire:loading.remove.delay.200ms wire:target="jenis_dipilih">
                @if (!empty($jenis_dipilih) && isset($requiredBerkas[$jenis_dipilih]) && count($requiredBerkas[$jenis_dipilih]) > 0)
                    <label class="block text-sm mb-1">Nama Berkas</label>
                    <select wire:model.lazy="nama_berkas_dipilih" class="w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih Nama Berkas --</option>
                        @foreach ($requiredBerkas[$jenis_dipilih] as $berkas)
                            <option value="{{ $berkas }}">{{ $berkas }}</option>
                        @endforeach
                    </select>
                    @error('nama_berkas_dipilih') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @endif
                </div>

<!-- Upload File PDF -->
@if ($nama_berkas_dipilih)
    <div class="md:col-span-2">
        <label class="block text-sm mb-1">Upload File PDF</label>

        <input
            type="file"
            wire:model="berkas_upload"
            wire:key="upload-file-{{ $jenis_dipilih }}-{{ $nama_berkas_dipilih }}"
            accept="application/pdf"
            class="w-full border rounded-lg"
        >
        @error('berkas_upload')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror

        </div>
@endif


                <!-- Rekening (readonly) -->
                <div wire:loading.remove.delay.200ms wire:target="subkegiatan_id">
                    <label class="block text-sm mb-1">Rekening Kegiatan</label>
                    <input type="text" wire:model="rekening_kegiatan" readonly class="w-full border-gray-300 rounded-lg bg-gray-100">
                    @error('rekening_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Nominal Pagu (readonly) -->
                <div wire:loading.remove.delay.200ms wire:target="subkegiatan_id">
                    <label class="block text-sm mb-1">Nominal Pagu</label>
                    <input type="number" wire:model="nominal_pagu" readonly class="w-full border-gray-300 rounded-lg bg-gray-100">
                    @error('nominal_pagu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Anggaran Sekarang -->
                <div>
                    <label class="block text-sm mb-1">Anggaran Sekarang</label>
                    <input type="number" wire:model.defer="nominal" class="w-full border-gray-300 rounded-lg">
                    @error('nominal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>



                <!-- Catatan Tambahan -->
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1">Catatan</label>
                    <textarea wire:model.defer="catatan" class="w-full border-gray-300 rounded-lg"></textarea>
                    @error('catatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end mt-4 gap-2">
                <button type="button" @click="open = false"
                        class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">
                    Batal
                </button>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
