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
    Schema::table('kegiatans', function (Blueprint $table) {
        $table->unsignedBigInteger('satuan_kerja_id')->after('id')->nullable();

        // Opsional foreign key:
        $table->foreign('satuan_kerja_id')->references('id')->on('satuan_kerjas')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('kegiatans', function (Blueprint $table) {
        $table->dropForeign(['satuan_kerja_id']);
        $table->dropColumn('satuan_kerja_id');
    });
}

};
