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
                    <select wire:model="kegiatan_id" class="w-full border-gray-300 rounded-lg">
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
                    <select wire:model="subkegiatan_id" class="w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih Sub Kegiatan --</option>
                        @foreach($subkegiatans ?? [] as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->nama_subkegiatan }}</option>
                        @endforeach
                    </select>
                    @error('subkegiatan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Jenis Belanja Checklist -->
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1 font-semibold">Checklist Jenis Belanja yang Diajukan:</label>
                    <div class="space-y-1">
                        @foreach (['spj_gu' => 'SPJ GU', 'spj_gu_tunai' => 'SPJ GU Tunai', 'spj_tenaga_ahli' => 'SPJ Tenaga Ahli'] as $key => $label)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" wire:model="jenis_belanja_terpilih" value="{{ $key }}">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Upload Berkas SPJ Per Item -->
                <div class="md:col-span-2 border p-3 rounded-lg bg-gray-50 mt-4">
                    <h3 class="text-sm font-semibold mb-2">Upload Berkas SPJ (Satu per Form)</h3>

                    <!-- Pilih Jenis Belanja (dropdown) -->
                    <div class="mb-2">
                        <label class="block text-sm">Jenis Belanja</label>
                        <select wire:model="jenis_dipilih" class="w-full border rounded-lg">
                            <option value="">-- Pilih Berkas --</option>
                            @foreach ($requiredBerkas as $jenis => $list)
                                <option value="{{ $jenis }}">{{ strtoupper($jenis) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Nama Berkas -->
                    @if (!empty($jenis_dipilih) && isset($requiredBerkas[$jenis_dipilih]))
                        <div class="mb-2">
                            <label class="block text-sm">Nama Berkas</label>
                            <select wire:model="nama_berkas_dipilih" class="w-full border rounded-lg">
                                <option value="">-- Pilih Nama Berkas --</option>
                                @foreach ($requiredBerkas[$jenis_dipilih] as $berkas)
                                    <option value="{{ $berkas }}">{{ $berkas }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Upload File PDF -->
                    @if ($nama_berkas_dipilih)
                        <div class="mb-2">
                            <label class="block text-sm">Upload File PDF</label>
                            <input type="file" wire:model="berkas_upload" accept="application/pdf" class="w-full border rounded-lg">

                        </div>

                        <button type="button" wire:click="uploadSatuBerkas"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded">
                            Simpan Berkas Ini
                        </button>
                    @endif
                </div>

                <!-- Rekening -->
                <div>
                    <label class="block text-sm mb-1">Rekening Kegiatan</label>
                    <input type="text" wire:model.defer="rekening_kegiatan" class="w-full border-gray-300 rounded-lg">
                    @error('rekening_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Tahun -->
                <div>
                    <label class="block text-sm mb-1">Tahun</label>
                    <select wire:model.defer="tahun_input" class="w-full border-gray-300 rounded-lg">
                        <option value="">-- Pilih Tahun --</option>
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    @error('tahun_input') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Pagu dan Nominal -->
                <div>
                    <label class="block text-sm mb-1">Nominal Pagu</label>
                    <input type="number" wire:model.defer="nominal_pagu" class="w-full border-gray-300 rounded-lg">
                    @error('nominal_pagu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1">Anggaran Sekarang</label>
                    <input type="number" wire:model.defer="nominal" class="w-full border-gray-300 rounded-lg">
                    @error('nominal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Upload File Utama -->
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1">Upload File (PDF, max 5MB)</label>
                    <input type="file" wire:model="file_upload" class="w-full border-gray-300 rounded-lg" accept="application/pdf">
                    @error('file_upload') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Catatan -->
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
