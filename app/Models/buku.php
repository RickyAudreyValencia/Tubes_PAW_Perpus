<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class buku extends Model
{
	use HasFactory;

	protected $table = 'buku';
	protected $primaryKey = 'id_buku';

	protected $fillable = [
		'id_kategori',
		'judul',
		'stok',
		'penulis',
		'penerbit',
		'tahun_terbit',
		'isbn',
		'deskripsi',
		'gambar_sampul',
	];

	public function kategori()
	{
		return $this->belongsTo(kategori::class, 'id_kategori', 'id_kategori');
	}

	public function itemBuku()
	{
		return $this->hasMany(item_buku::class, 'id_buku', 'id_buku');
	}
}

