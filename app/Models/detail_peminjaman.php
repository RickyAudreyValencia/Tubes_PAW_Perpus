<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_peminjaman extends Model
{
	protected $table = 'detail_peminjaman';
	public $incrementing = false;
	protected $primaryKey = null;
	public $timestamps = false;

	protected $fillable = [
		'id_peminjaman',
		'id_item_buku',
	];

	public function peminjaman()
	{
		return $this->belongsTo(peminjaman::class, 'id_peminjaman', 'id_peminjaman');
	}

	public function itemBuku()
	{
		return $this->belongsTo(item_buku::class, 'id_item_buku', 'id_item_buku');
	}
}