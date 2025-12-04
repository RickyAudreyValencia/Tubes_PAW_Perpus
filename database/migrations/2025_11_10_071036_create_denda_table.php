<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('denda', function (Blueprint $table) {
            $table->id('id_denda');
            $table->foreignId('id_peminjaman')->constrained('peminjaman', 'id_peminjaman')->cascadeOnDelete();
            $table->foreignId('id_petugas')->constrained('petugas', 'id_petugas');
            $table->decimal('jumlah', 10, 2);
            $table->string('status', 50);
            $table->date('tgl_pembayaran')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};