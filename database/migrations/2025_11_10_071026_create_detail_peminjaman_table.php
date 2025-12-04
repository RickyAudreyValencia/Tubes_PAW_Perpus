<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->foreignId('id_peminjaman')->constrained('peminjaman', 'id_peminjaman')->cascadeOnDelete();
            $table->foreignId('id_item_buku')->constrained('item_buku', 'id_item_buku')->cascadeOnDelete();
            $table->primary(['id_peminjaman', 'id_item_buku']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};