<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_belanja_pelaporans', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // contoh: SPJ GU Non Tunai, Belanja Jasa THL, dst.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_belanja_pelaporans');
    }
};
