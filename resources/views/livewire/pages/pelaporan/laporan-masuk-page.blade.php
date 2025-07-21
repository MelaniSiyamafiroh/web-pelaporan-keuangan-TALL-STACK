<div class="max-w-7xl mx-auto p-6" x-data="{ openSub: {}, openBerkas: {} }">
    <h2 class="text-xl font-semibold text-gray-700 mb-6">Laporan Masuk</h2>

    {{-- Filter & Search --}}
    <div class="flex flex-col md:flex-row md:items-center mb-4 gap-2">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" wire:model.debounce.500ms="search" placeholder="Cari laporan..."
                class="form-input w-full pl-10 pr-4 py-2 rounded border-gray-300 focus:ring focus:ring-blue-200 transition" />
        </div>

        <select wire:model.defer="pptkFilter" class="border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            <option value="">Semua PPTK</option>
            <option value="2">IKP</option>
            <option value="3">TKI</option>
            <option value="4">Sekretariat</option>
        </select>

        <select wire:model.defer="tahunFilter" class="border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            <option value="">Tahun</option>
            @for ($i = date('Y'); $i >= 2020; $i--)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>

        <select wire:model.defer="sort" class="border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200">
            <option value="created_desc">Terbaru</option>
            <option value="created_asc">Terlama</option>
            <option value="nama_asc">Nama A-Z</option>
            <option value="nama_desc">Nama Z-A</option>
        </select>

        <button wire:click="applyFilter"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow text-sm transition">
            Terapkan
        </button>
    </div>

    {{-- Tampilan Nested --}}
    @forelse($kegiatans as $kegiatan)
        <div class="mb-4 border border-gray-300 rounded shadow">
            <div class="px-4 py-2 bg-gray-100 font-semibold text-blue-800">
                📁 {{ $kegiatan->nama_kegiatan }} ({{ $kegiatan->kode_kegiatan }})
            </div>

            @php
                $subkegs = $subkegiatans->where('kegiatan_id', $kegiatan->id);
            @endphp

            @foreach($subkegs as $sub)
                <div class="border-t px-4 py-2 bg-gray-50" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between text-sm font-medium text-gray-700">
                        <span>📌 {{ $sub->nama_subkegiatan }} ({{ $sub->kode_subkegiatan }})</span>
                        <svg x-show="!open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <svg x-show="open" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="mt-2">
                        @php
                            $laporans = $laporanMasuk->where('subkegiatan_id', $sub->id);
                        @endphp

                        @forelse($laporans as $lap)
                            <div class="border mt-2 rounded">
                                <div class="flex items-center justify-between bg-white px-4 py-2 cursor-pointer"
                                     @click="openBerkas[{{ $lap->id }}] = !openBerkas[{{ $lap->id }}]">
                                    <div>
                                        <p class="text-sm font-semibold">{{ $lap->jenis_belanja }} - {{ $lap->rekening_kegiatan }}</p>
                                        <p class="text-xs text-gray-500">
                                            PPTK: {{ $lap->pptk->name ?? '-' }} |
                                            Rp{{ number_format($lap->nominal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full
                                        @if($lap->status === 'Diajukan') bg-yellow-200 text-yellow-800
                                        @elseif($lap->status === 'Perlu Revisi') bg-red-200 text-red-800
                                        @elseif(Str::contains($lap->status, 'Disetujui')) bg-green-200 text-green-800
                                        @else bg-gray-200 text-gray-700 @endif">
                                        {{ $lap->status }}
                                    </span>
                                </div>

                                {{-- Dropdown Berkas --}}
                                <div x-show="openBerkas[{{ $lap->id }}]" x-collapse class="px-4 py-2 bg-gray-50">
                                    @php
                                        $files = $lap->berkas ?? collect();
                                    @endphp

                                    @if($files->isEmpty())
                                        <div class="text-sm italic text-gray-500">Tidak ada berkas di laporan ini.</div>
                                    @else
                                        <ul class="list-disc ml-4 text-sm text-blue-700">
                                            @foreach($files as $file)
                                                <li>
                                                    <a href="{{ Storage::url($file->path) }}" target="_blank" class="hover:underline">
                                                        {{ $file->nama_file }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    {{-- Tombol aksi --}}
                                    <div class="mt-2">
                                        @include('livewire.pages.pelaporan.laporan-masuk-action', ['item' => $lap])
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm italic text-gray-500 mt-1">Belum ada laporan untuk subkegiatan ini.</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="text-gray-500 italic">Tidak ada kegiatan ditemukan.</div>
    @endforelse

    @include('livewire.pages.pelaporan.modal-verifikasi')

</div>
