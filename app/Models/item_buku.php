<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item_buku extends Model
{
	use HasFactory;

	protected $table = 'item_buku';
	protected $primaryKey = 'id_item_buku';

	protected $fillable = [
		'id_buku',
		'kode_inventaris',
		'status',
		'tgl_pengadaan',
	];

	protected $casts = [
		'tgl_pengadaan' => 'date',
	];

	public function buku()
	{
		return $this->belongsTo(buku::class, 'id_buku', 'id_buku');
	}

	public function peminjaman()
	{
		return $this->belongsToMany(peminjaman::class, 'detail_peminjaman', 'id_item_buku', 'id_peminjaman', 'id_item_buku', 'id_peminjaman');
	}
}