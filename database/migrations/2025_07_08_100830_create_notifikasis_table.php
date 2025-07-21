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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Kolom user_id BIGINT(20) UNSIGNED
            $table->integer('laporan_id'); // Kolom laporan_id INT(11)
            $table->enum('type', ['pelaporan masuk', 'perlu revisi']); // Kolom enum type
            $table->string('pesan', 255); // Kolom pesan VARCHAR(255)
            $table->enum('status', ['terkirim', 'dibaca']); // Kolom enum status
            $table->timestamp('created_at')->useCurrent(); // Timestamp default CURRENT_TIMESTAMP
            $table->timestamp('read_at')->nullable(); // Timestamp bisa NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
