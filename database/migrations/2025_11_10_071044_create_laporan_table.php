<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->string('periode', 20);
            $table->integer('total_peminjaman');
            $table->integer('total_buku_dipinjam');
            $table->decimal('total_denda', 10, 2);
            $table->foreignId('dibuat_oleh')->constrained('petugas', 'id_petugas');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};