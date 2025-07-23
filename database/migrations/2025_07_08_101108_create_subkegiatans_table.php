<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subkegiatans', function (Blueprint $table) {
            $table->id();

            // Menggunakan foreignId agar otomatis unsignedBigInteger + index + foreign
            $table->foreignId('kegiatan_id')
                ->constrained('kegiatans')
                ->onDelete('cascade');
             // Hapus subkegiatan jika kegiatan dihapus

            $table->string('nama_subkegiatan', 255)->nullable();
            $table->year('tahun_anggaran');
            $table->string('rekening', 255);
            $table->decimal('jumlah_pagu', 15, 2);

            $table->timestamps(); // created_at & updated_at default Laravel
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subkegiatans');
    }
};
