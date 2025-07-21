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
        Schema::create('pagu_anggarans', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun'); // Kolom tahun INT(11)
            $table->decimal('total_pagu', 15, 2); // Kolom total_pagu DECIMAL(15,2)
            $table->timestamp('created_at')->useCurrent(); // Kolom created_at default CURRENT_TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagu_anggarans');
    }
};
