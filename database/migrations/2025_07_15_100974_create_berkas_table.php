<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();

            // Relasi ke pelaporan (satu berkas milik satu pelaporan/subkegiatan)
            $table->foreignId('pelaporan_id')
                ->constrained('pelaporans')
                ->onDelete('cascade');

            // Nama/jenis/kategori berkas (opsional: Surat Pernyataan, Nota, dll)
            $table->string('nama_berkas', 255)->nullable();

            // Path atau lokasi penyimpanan file di storage
            $table->string('file_path', 255);

            // Keterangan tambahan dari PPTK
            $table->text('keterangan')->nullable();

            // Siapa yang mengupload (optional, bisa null jika dihapus)
            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas');
    }
};
