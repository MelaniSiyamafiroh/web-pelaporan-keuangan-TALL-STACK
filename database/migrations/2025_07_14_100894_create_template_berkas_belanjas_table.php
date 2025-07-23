<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_berkas_belanjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_belanja_id')
                  ->constrained('jenis_belanja_pelaporans')
                  ->onDelete('cascade');
            $table->string('nama_berkas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_berkas_belanjas');
    }
};
