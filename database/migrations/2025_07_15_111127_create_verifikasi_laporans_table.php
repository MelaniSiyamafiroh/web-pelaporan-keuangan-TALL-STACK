<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi_laporans', function (Blueprint $table) {
            $table->id();

            // Relasi ke pelaporan
            $table->foreignId('pelaporan_id')
                ->constrained('pelaporans')
                ->onDelete('cascade');

            // Relasi ke user yang memverifikasi
            $table->foreignId('verifikator_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Menandai level peran saat verifikasi dilakukan
            $table->enum('role_verifikator', ['verifikator', 'bendahara', 'kepala_dinas']);

            // Informasi proses verifikasi
            $table->date('tanggal_verifikasi')->nullable();
            $table->text('catatan')->nullable();

            // Status hasil verifikasi (untuk level ini)
            $table->enum('status', ['Disetujui', 'Revisi'])->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_laporans');
    }
};
