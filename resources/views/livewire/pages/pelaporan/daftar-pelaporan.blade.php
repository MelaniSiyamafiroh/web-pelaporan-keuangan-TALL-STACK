<div class="max-w-7xl mx-auto p-6" x-data="{ open: {} }">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-700">Daftar Pelaporan</h2>
        <button wire:click="$set('showModal', true)"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            <i class="fas fa-plus mr-2"></i> Tambah Pelaporan
        </button>
    </div>

    <!-- Filter -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
        <input type="text" wire:model.debounce.500ms="search" placeholder="Cari..." class="form-input w-full" />
        <select wire:model="status" class="form-select w-full">
            <option value="">-- Semua Status --</option>
            <option value="Diajukan">Diajukan</option>
            <option value="Perlu Revisi">Perlu Revisi</option>
            <option value="Disetujui Verifikator">Disetujui Verifikator</option>
            <option value="Disetujui Bendahara">Disetujui Bendahara</option>
            <option value="Disetujui Kepala Dinas">Disetujui Kepala Dinas</option>
        </select>
        <input type="number" wire:model="tahun" placeholder="Tahun" class="form-input w-full" />
        <select wire:model="sort" class="form-select w-full">
            <option value="created_desc">Terbaru</option>
            <option value="created_asc">Terlama</option>
        </select>
    </div>

    <!-- Nested Kegiatan > Subkegiatan > Laporan -->
    @forelse($kegiatans as $kegiatan)
        <div class="mb-4 border border-gray-300 rounded shadow">
            <div class="px-4 py-2 bg-gray-100 font-semibold text-blue-800">
                📁 {{ $kegiatan->nama_kegiatan }}
            </div>

            @php
                $subkegs = $subkegiatans->where('kegiatan_id', $kegiatan->id);
            @endphp

            @foreach($subkegs as $sub)
                <div class="border-t px-4 py-2" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left flex items-center justify-between text-sm font-medium text-gray-700">
                        <span>📌 {{ $sub->nama_subkegiatan }}</span>
                        <svg x-show="!open" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        <svg x-show="open" x-cloak class="h-4 w-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 12H4" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="mt-2">
                        @php
                            $laporansSub = $laporan->where('subkegiatan_id', $sub->id);
                        @endphp

                        @if($laporansSub->isEmpty())
                            <div class="text-sm italic text-gray-500 mt-1">Belum ada pelaporan untuk subkegiatan ini.</div>
                        @else
                            <table class="w-full text-sm text-left mt-2 border rounded">
                                <thead class="bg-blue-50 text-gray-700">
                                    <tr>
                                        <th class="px-2 py-1 border">PPTK</th>
                                        <th class="px-2 py-1 border">Jenis Belanja</th>
                                        <th class="px-2 py-1 border">Rekening</th>
                                        <th class="px-2 py-1 border text-right">Nominal</th>
                                        <th class="px-2 py-1 border">Status</th>
                                        <th class="px-2 py-1 border">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($laporansSub as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-2 py-1 border">{{ $item->pptk->name ?? '-' }}</td>
                                            <td class="px-2 py-1 border">{{ $item->jenis_belanja }}</td>
                                            <td class="px-2 py-1 border">{{ $item->rekening_kegiatan }}</td>
                                            <td class="px-2 py-1 border text-right">Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            <td class="px-2 py-1 border">
                                                <span class="text-xs font-semibold px-2 py-1 rounded-full
                                                    @if($item->status === 'Diajukan') bg-yellow-200 text-yellow-800
                                                    @elseif($item->status === 'Perlu Revisi') bg-red-200 text-red-800
                                                    @elseif(Str::contains($item->status, 'Disetujui')) bg-green-200 text-green-800
                                                    @else bg-gray-200 text-gray-700 @endif">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="px-2 py-1 border">
                                                @include('livewire.pages.pelaporan.daftar-pelaporan-action', ['item' => $item])
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="text-gray-500 italic">Tidak ada kegiatan ditemukan untuk tahun ini.</div>
    @endforelse

    <!-- List Preview Berkas Sudah Diunggah -->
    @if ($pelaporan_id_aktif)
        <div class="mt-4 bg-gray-50 border p-4 rounded">
            <h3 class="text-sm font-semibold mb-2">Berkas Yang Telah Diunggah</h3>
            <ul class="text-sm list-disc ml-5 space-y-1">
                @foreach ($requiredBerkas as $jenis => $berkasList)
                    @foreach ($berkasList as $nama)
                        <li>{{ strtoupper($jenis) }} - {{ $nama }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Modal Tambah/Edit -->
    @include('livewire.pages.pelaporan.modal-form')
</div>
