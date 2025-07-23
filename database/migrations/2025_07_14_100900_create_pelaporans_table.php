<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelaporans', function (Blueprint $table) {
            $table->id();


            // Relasi ke PPTK (User)
            $table->foreignId('pptk_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Relasi ke kegiatan
            $table->foreignId('kegiatan_id')
                ->constrained('kegiatans')
                ->onDelete('cascade');

            // Relasi ke subkegiatan
            $table->foreignId('subkegiatan_id')
                ->constrained('subkegiatans')
                ->onDelete('cascade');
            $table->foreignId('jenis_belanja_id')
                ->constrained('jenis_belanja_pelaporans')
                ->onDelete('cascade');
            $table->string('rekening_kegiatan', 100);
            $table->year('tahun')->nullable(); // ✅ TANPA after()

            // Pagu dari subkegiatan saat pelaporan (dibekukan)
            $table->decimal('nominal_pagu', 15, 2);

            // Total nominal dilaporkan
            $table->decimal('nominal', 15, 2);

            // Status hanya disimpan di sini
            $table->enum('status', [
                'Diajukan',
                'Perlu Revisi',
                'Disetujui Verifikator',
                'Disetujui Bendahara',
                'Disetujui Kepala Dinas'
            ])->default('Diajukan');

            $table->foreignId('laporan_tahunan_id')
                ->nullable()
                ->constrained('laporan_tahunans')
                ->onDelete('set null');

            $table->string('file_path', 255)->nullable(); // opsional, jika upload awal berupa bundel
            $table->text('catatan')->nullable(); // Catatan dari verifikator / bendahara / kadis

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaporans');
    }
};
