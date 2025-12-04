<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->foreignId('id_anggota')->constrained('anggota', 'id_anggota')->cascadeOnDelete();
            $table->foreignId('id_petugas_pinjam')->constrained('petugas', 'id_petugas');
            $table->foreignId('id_petugas_kembali')->nullable()->constrained('petugas', 'id_petugas');
            $table->date('tgl_pinjam');
            $table->date('tgl_jatuh_tempo');
            $table->date('tgl_kembali')->nullable();
            $table->string('status', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};