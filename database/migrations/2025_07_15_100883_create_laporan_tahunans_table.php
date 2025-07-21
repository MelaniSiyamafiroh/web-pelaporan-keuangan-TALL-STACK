<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_tahunans', function (Blueprint $table) {
            $table->id();

            // Tahun laporan akhir tahun
            $table->year('tahun');

            // Relasi ke satuan kerja (misalnya IKP, TKI, Sekretariat)
            $table->foreignId('satuan_kerja_id')
                ->constrained('satuan_kerjas') // Nama tabel satuan kerja harus disesuaikan
                ->onDelete('cascade');

            // Catatan tambahan dari admin atau kepala dinas
            $table->text('catatan')->nullable();

            // File laporan akhir tahun (misalnya PDF rekap)
            $table->string('file_path', 255)->nullable();

            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_tahunans');
    }
};
