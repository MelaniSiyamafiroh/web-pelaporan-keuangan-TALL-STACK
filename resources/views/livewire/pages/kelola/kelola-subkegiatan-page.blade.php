<div class="max-w-6xl mx-auto p-6" x-data="{ openModal: false }">

    <!-- Heading + Search + Tambah -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h2 class="text-xl font-semibold text-gray-700">Daftar Sub Kegiatan</h2>
        <div class="flex flex-col md:flex-row md:items-center gap-2 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" wire:model.debounce.500ms="search" placeholder="Cari Sub Kegiatan..."
                    class="form-input w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring focus:ring-blue-200 transition" />
            </div>

            <button @click="openModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-plus mr-2"></i>Tambah Sub Kegiatan
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div class="flex flex-col md:flex-row md:items-center gap-2 mb-4">
        <div class="flex-1">
            <select wire:model.defer="kegiatan_id" class="form-select w-full">
                <option value="">-- Semua Kegiatan --</option>
                @foreach ($kegiatan as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kegiatan }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1">
            <input type="number" wire:model.defer="tahun_anggaran" placeholder="Tahun Anggaran"
                class="form-input w-full" />
        </div>

        <div class="flex-1">
            <select wire:model.defer="sort" class="form-select w-full">
                <option value="created_desc">Terbaru</option>
                <option value="created_asc">Terlama</option>
                <option value="nama_asc">Nama A-Z</option>
                <option value="nama_desc">Nama Z-A</option>
            </select>
        </div>

        <div>
            <button wire:click="$refresh"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-check mr-1"></i> Terapkan
            </button>
        </div>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr class="text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Kegiatan</th>
                    <th class="px-6 py-4">Sub Kegiatan</th>
                    <th class="px-6 py-4">Tahun Anggaran</th>
                    <th class="px-6 py-4">Rekening</th>
                    <th class="px-6 py-4">Jumlah Pagu</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($subkegiatan as $index => $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">{{ $index + 1 + ($subkegiatan->currentPage() - 1) * $subkegiatan->perPage() }}</td>
                    <td class="px-6 py-4">{{ $item->kegiatan->nama_kegiatan ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $item->nama_subkegiatan }}</td>
                    <td class="px-6 py-4">{{ $item->tahun_anggaran }}</td>
                    <td class="px-6 py-4">{{ $item->rekening ?? '-' }}</td>
                    <td class="px-6 py-4">Rp{{ number_format($item->jumlah_pagu ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center">
                        @include('livewire.pages.kelola.kelola-subkegiatan-action', ['item' => $item])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">Data tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $subkegiatan->links() }}
    </div>

    <!-- Modal Tambah/Edit -->
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">
                {{ $edit_id ? 'Edit Sub Kegiatan' : 'Tambah Sub Kegiatan' }}
            </h3>
            <form wire:submit.prevent="{{ $edit_id ? 'update' : 'store' }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Nama Sub Kegiatan</label>
                    <input type="text" wire:model.defer="nama_subkegiatan" class="form-input w-full">
                    @error('nama_subkegiatan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Kegiatan</label>
                    <select wire:model.defer="kegiatan_id" class="form-select w-full">
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach ($kegiatan as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kegiatan }}</option>
                        @endforeach
                    </select>
                    @error('kegiatan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Tahun Anggaran</label>
                    <input type="number" wire:model.defer="tahun_anggaran" class="form-input w-full">
                    @error('tahun_anggaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Rekening</label>
                    <input type="text" wire:model.defer="rekening" class="form-input w-full">
                    @error('rekening') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium">Jumlah Pagu</label>
                    <input type="number" wire:model.defer="jumlah_pagu" class="form-input w-full" step="0.01">
                    @error('jumlah_pagu') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-300 rounded mr-2">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
