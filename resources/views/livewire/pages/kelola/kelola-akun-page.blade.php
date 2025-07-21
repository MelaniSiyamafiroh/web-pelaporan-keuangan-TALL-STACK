<div class="max-w-7xl mx-auto p-6" x-data="{ openModal: false }">

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
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($users as $index => $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->role }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <button wire:click="$emit('editUser', {{ $user->id }})"
                                class="text-yellow-500 hover:text-yellow-700">
                                <i class="fas fa-edit fa-lg"></i>
                            </button>
                            <button wire:click="destroy({{ $user->id }})"
                                class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash fa-lg"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Modal Form Tambah Akun -->
    <div x-show="openModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        x-transition>
        <div @click.away="openModal = false"
            class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
            <h3 class="text-lg font-semibold mb-4">Tambah Akun</h3>
            <div class="space-y-3">
                <input type="text" wire:model.defer="name" placeholder="Nama"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                <input type="email" wire:model.defer="email" placeholder="Email"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                <input type="text" wire:model.defer="role" placeholder="Role"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
                <input type="password" wire:model.defer="password" placeholder="Password"
                    class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button @click="openModal = false"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded shadow text-sm">Batal</button>
                <button wire:click="store"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">Simpan</button>
            </div>
        </div>
    </div>
</div>
