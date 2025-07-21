<!-- Sidebar -->
<aside class="flex flex-col w-64 h-full bg-white border-r dark:border-darker dark:bg-dark
    transition-all duration-300 fixed z-30 md:z-10 md:relative"
    :class="{ '-ml-64': !isSidebarOpen }">

    {{-- Header Sidebar --}}
    <div class="flex items-center justify-between px-4 pt-2 pb-3 h-14 border-b dark:bg-dark dark:border-darker">
        <img src="{{ asset('images/logo_biru_crop.png') }}" width="120" class="bg-white p-1 rounded">
        <button @click="toggleSidebarMenu" class="text-gray-500 block md:hidden">
            <x-fas-times class="h-5 w-5"/>
        </button>
    </div>

    {{-- Daftar menu sidebar --}}
    <div class="flex-1 flex flex-col overflow-y-auto">
        <nav aria-label="Main" class="flex-1 px-2 py-4 space-y-2">

            {{-- Dashboard --}}
            <x-menu link="/">
                <x-fas-tachometer-alt class="h-5 w-5"/>
                <span class="ml-2 text-sm">Dashboard</span>
            </x-menu>

            {{-- Dropdown Pelaporan --}}
            <div x-data="{ open: false }" class="space-y-1">
                <button @click="open = !open" class="flex items-center w-full p-2 text-gray-500 transition rounded dark:text-light hover:bg-primary dark:hover:bg-primary-dark hover:text-light">
                    <x-fas-file-alt class="h-5 w-5"/>
                    <span class="ml-2 text-sm">Pelaporan</span>
                    <x-fas-angle-down :class="{ 'rotate-180': open }" class="ml-auto transition-transform duration-200 h-4 w-4"/>
                </button>
                <div x-show="open" class="pl-6 space-y-1" x-cloak>
                    <x-menu link="/daftar-pelaporan">
                        <span class="ml-2 text-sm">Daftar Pelaporan</span>
                    </x-menu>
                    <x-menu link="/laporan-masuk">
                        <span class="ml-2 text-sm">Laporan Masuk</span>
                    </x-menu>
                </div>
            </div>

            {{-- Dropdown Kelola --}}
            <div x-data="{ openKelola: false }" class="space-y-1">
                <button @click="openKelola = !openKelola" class="flex items-center w-full p-2 text-gray-500 transition rounded dark:text-light hover:bg-primary dark:hover:bg-primary-dark hover:text-light">
                    <x-fas-cogs class="h-5 w-5"/>
                    <span class="ml-2 text-sm">Kelola</span>
                    <x-fas-angle-down :class="{ 'rotate-180': openKelola }" class="ml-auto transition-transform duration-200 h-4 w-4"/>
                </button>
                <div x-show="openKelola" class="pl-6 space-y-1" x-cloak>
                    <x-menu link="/kelola-kegiatan">
                        <span class="ml-2 text-sm">Kelola Kegiatan</span>
                    </x-menu>
                    <x-menu link="/kelola-subkegiatan">
                        <span class="ml-2 text-sm">Kelola Subkegiatan</span>
                    </x-menu>
                    <x-menu link="/kelola-akun">
                        <span class="ml-2 text-sm">Kelola Akun</span>
                    </x-menu>
                </div>
            </div>

            {{-- pembukuan tahunan --}}
            <x-menu link="/pembukuan-tahunan">
                <x-fas-cog class="h-5 w-5"/>
                <span class="ml-2 text-sm">Pembukuan Tahunan</span>
            </x-menu>

        </nav>
    </div>
</aside>
