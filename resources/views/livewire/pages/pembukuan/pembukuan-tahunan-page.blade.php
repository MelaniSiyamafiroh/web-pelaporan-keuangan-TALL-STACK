<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Pembukuan Laporan Tahunan - {{ session('tahun_aktif') }}</h2>

    @if($laporanSiap->isEmpty())
        <div class="text-gray-500 italic">Tidak ada laporan disetujui yang bisa dibukukan.</div>
    @else
        <div class="mb-4">
            <label class="block font-medium text-sm text-gray-700">Catatan Pembukuan (Opsional)</label>
            <textarea wire:model.defer="catatan" class="w-full border rounded px-3 py-2 mt-1 text-sm"></textarea>
        </div>

        <div class="mb-4">
            <button wire:click="bukukan" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                📦 Bukukan {{ $laporanSiap->count() }} Laporan
            </button>
        </div>

        <div class="border rounded-lg overflow-hidden">
            <table class="min-w-full text-sm border">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-3 py-2 border">Subkegiatan</th>
                        <th class="px-3 py-2 border">Kegiatan</th>
                        <th class="px-3 py-2 border">PPTK</th>
                        <th class="px-3 py-2 border">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporanSiap as $laporan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border">{{ $laporan->subkegiatan->nama_subkegiatan }}</td>
                            <td class="px-3 py-2 border">{{ $laporan->subkegiatan->kegiatan->nama_kegiatan }}</td>
                            <td class="px-3 py-2 border">{{ $laporan->pptk->name }}</td>
                            <td class="px-3 py-2 border text-right">{{ number_format($laporan->nominal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
