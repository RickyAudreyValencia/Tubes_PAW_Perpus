<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_buku', function (Blueprint $table) {
            $table->id('id_item_buku');
            $table->foreignId('id_buku')->constrained('buku', 'id_buku')->cascadeOnDelete();
            $table->string('kode_inventaris', 50);
            $table->string('status', 50);
            $table->date('tgl_pengadaan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_buku');
    }
};