<div class="max-w-7xl mx-auto p-6" x-data="{ openModal: false }" @open-modal.window="openModal = true" @close-modal.window="openModal = false">
    <!-- Heading & Button -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Data Akun</h2>
        <button @click="openModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md">
            <i class="fas fa-plus mr-2"></i>Tambah Akun
        </button>
    </div>

    <!-- Search & Sort Horizontal -->
    <div class="flex flex-col md:flex-row md:items-center mb-4 gap-2">
        <!-- Search -->
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" wire:model.debounce.500ms="search" placeholder="Cari berdasarkan nama..."
                   class="form-input w-full pl-10 pr-4 py-2 rounded border-gray-300 focus:ring focus:ring-blue-200 transition" />
        </div>

        <!-- Sort -->
        <select wire:model.defer="sort"
            class="border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            <option value="created_desc">Terbaru</option>
            <option value="created_asc">Terlama</option>
            <option value="name_asc">A-Z</option>
            <option value="name_desc">Z-A</option>
        </select>

        <!-- Terapkan -->
        <button wire:click="applyFilter"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow text-sm transition">
            Terapkan
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-blue-50">
                <tr class="text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Satuan Kerja</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($users as $index => $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->getRoleNames()->implode(', ') }}</td>
                        <td class="px-6 py-4">{{ $user->satuanKerja->nama ?? '-' }}</td>
                        <td class="px-6 py-4">
    <div class="flex items-center gap-2">
        <button
            wire:click="$emit('edit', {{ $user->id }})"
            class="p-1 rounded-full bg-yellow-400 hover:bg-yellow-500 text-white"
            title="Edit Akun"
        >
            <i class="fas fa-edit fa-sm"></i>
        </button>

        <button
    wire:click="destroy({{ $user->id }})"
    onclick="confirm('Yakin ingin menghapus akun ini?') || event.stopImmediatePropagation()"
    class="p-1 rounded-full bg-red-500 hover:bg-red-600 text-white"
    title="Hapus Akun"
>
    <i class="fas fa-trash fa-sm"></i>
</button>

    </div>
</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Modal Form Tambah/Edit Akun -->
    <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" x-transition>
        <div @click.away="openModal = false" class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
            <h3 class="text-lg font-semibold mb-4" x-text="$wire.editing_id ? 'Edit Akun' : 'Tambah Akun'"></h3>
            <div class="space-y-3">
                <input type="text" wire:model.defer="name" placeholder="Nama"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">

                <input type="email" wire:model.defer="email" placeholder="Email"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">

                <select wire:model.defer="role" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Pilih Role</option>
                    @foreach($allRoles as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>

                <select wire:model.defer="satuan_kerja_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Pilih Satuan Kerja</option>
                    @foreach($allSatuanKerja as $sk)
                        <option value="{{ $sk->id }}">{{ $sk->nama }}</option>
                    @endforeach
                </select>

                <input type="password" wire:model.defer="password" placeholder="Password"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="openModal = false"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded shadow text-sm">Batal</button>

                <button
                    x-show="$wire.editing_id === null"
                    wire:click="store"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
                    Simpan
                </button>

                <button
                    x-show="$wire.editing_id !== null"
                    wire:click="update"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm">
                    Update
                </button>
            </div>
        </div>
    </div>
</div>
