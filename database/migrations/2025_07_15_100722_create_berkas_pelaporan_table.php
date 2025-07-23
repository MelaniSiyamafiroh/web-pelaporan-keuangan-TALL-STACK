<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas_pelaporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelaporan_id')->constrained('pelaporans')->onDelete('cascade');
            $table->foreignId('template_berkas_id')->constrained('template_berkas_belanjas')->onDelete('restrict');
            $table->string('nama_file');   // Nama asli dari user
            $table->string('path_file');   // path penyimpanan di storage
            $table->integer('size_file');  // ukuran file dalam byte
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_pelaporan');
    }
};
