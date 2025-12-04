<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class denda extends Model
{
	use HasFactory;

	protected $table = 'denda';
	protected $primaryKey = 'id_denda';

	protected $fillable = [
		'id_peminjaman',
		'id_petugas',
		'jumlah',
		'status',
		'tgl_pembayaran',
		'catatan',
	];

	protected $casts = [
		'tgl_pembayaran' => 'date',
		'jumlah' => 'decimal:2',
	];

	public function peminjaman()
	{
		return $this->belongsTo(peminjaman::class, 'id_peminjaman', 'id_peminjaman');
	}

	public function petugas()
	{
		return $this->belongsTo(petugas::class, 'id_petugas', 'id_petugas');
	}
}

