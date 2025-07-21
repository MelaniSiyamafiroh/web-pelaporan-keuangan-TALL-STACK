<div class="flex gap-2">
    @if ($row->file_path)
        <a href="{{ Storage::url($row->file_path) }}" target="_blank"
            class="text-blue-600 hover:underline text-sm">
            🔽 Unduh
        </a>
    @else
        <span class="text-gray-400 text-sm italic">Tidak ada file</span>
    @endif
</div>
