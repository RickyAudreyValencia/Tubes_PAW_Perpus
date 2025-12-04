<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id('id_buku');
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->cascadeOnDelete();
            $table->string('judul');
            $table->string('penulis')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('tahun_terbit', 4)->nullable();
            $table->string('isbn', 20)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_sampul')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};