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
        Schema::create('jenis_belanja_pelaporan', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pelaporan_id')->constrained()->onDelete('cascade');
    $table->string('jenis_belanja'); // misal: spj_gu, spj_gu_tunai, spj_tenaga_ahli
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_belanja_pelaporans');
    }
};
