<div class="max-w-6xl mx-auto p-6" x-data="{ openModal: false }">

    <!-- Heading & Search + Tambah -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h2 class="text-xl font-semibold text-gray-700">Kelola Kegiatan</h2>
        <div class="flex flex-col md:flex-row md:items-center gap-2 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" wire:model.debounce.300ms="search" placeholder="Cari kegiatan..."
                       class="form-input w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring focus:ring-blue-200 transition" />
            </div>

            <button @click="openModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">
                <i class="fas fa-plus mr-2"></i>Tambah Kegiatan
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
        <div>
            <select wire:model.defer="satuanKerja" class="form-select w-full mt-1">
                <option value="">-- Semua SKPD --</option>
                @foreach(\App\Models\SatuanKerja::all() as $sk)
                    <option value="{{ $sk->id }}">{{ $sk->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select wire:model.defer="tahun" class="form-select w-full">
                <option value="">-- Semua Tahun --</option>
                @for ($i = date('Y'); $i >= 2020; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div>
            <select wire:model.defer="sort" class="form-select w-full">
                <option value="created_desc">Terbaru</option>
                <option value="created_asc">Terlama</option>
                <option value="nama_asc">Nama A-Z</option>
                <option value="nama_desc">Nama Z-A</option>
            </select>
        </div>
        <div>
            <button wire:click="$refresh"
                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Terapkan Filter
            </button>
        </div>
    </div>

    <!-- Loading -->
    <div wire:loading.flex class="justify-center py-4 text-gray-500">
        Memuat data kegiatan...
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr class="text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Satuan Kerja</th>
                    <th class="px-6 py-3">Nama Kegiatan</th>
                    <th class="px-6 py-3">Tahun</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($kegiatan as $index => $data)
                    <tr wire:key="kegiatan-{{ $data->id }}">
                        <td class="px-6 py-4">{{ $index + 1 + ($kegiatan->currentPage() - 1) * $kegiatan->perPage() }}</td>
                        <td class="px-6 py-4 uppercase">{{ $data->satuanKerja->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $data->nama_kegiatan }}</td>
                        <td class="px-6 py-4">{{ $data->tahun }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button wire:click="edit({{ $data->id }})"
                                        @click="openModal = true"
                                        class="bg-purple-600 hover:bg-purple-700 text-white rounded-full p-2"
                                        title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $data->id }})"
                                        onclick="confirm('Yakin ingin menghapus kegiatan ini?') || event.stopImmediatePropagation()"
                                        class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2"
                                        title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Data kegiatan tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $kegiatan->links() }}
    </div>

    <!-- Modal Tambah/Edit -->
    <div x-show="openModal" @click.away="openModal = false"
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 space-y-4">
            <h3 class="text-lg font-semibold">
                {{ $kegiatanId ? 'Edit Kegiatan' : 'Tambah Kegiatan' }}
            </h3>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan Kerja</label>
                    <select wire:model.defer="formSatuanKerja" class="form-select w-full mt-1">
                        <option value="">-- Pilih --</option>
                        @foreach ($daftarSatuanKerja as $sk)
                            <option value="{{ $sk->id }}">{{ $sk->nama }}</option>
                        @endforeach
                    </select>
                    @error('formSatuanKerja') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                    <input type="text" wire:model.defer="nama_kegiatan" class="form-input w-full mt-1">
                    @error('nama_kegiatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun</label>
                    <input type="number" wire:model.defer="formTahun" class="form-input w-full mt-1">
                    @error('formTahun') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button @click="openModal = false"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">
                    Batal
                </button>
                <button wire:click="save"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
