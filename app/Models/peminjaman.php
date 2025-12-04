<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
	use HasFactory;

	protected $table = 'peminjaman';
	protected $primaryKey = 'id_peminjaman';

	protected $fillable = [
		'id_anggota',
		'id_petugas_pinjam',
		'id_petugas_kembali',
		'tgl_pinjam',
		'tgl_jatuh_tempo',
		'tgl_kembali',
		'status',
	];

	protected $casts = [
		'tgl_pinjam' => 'date',
		'tgl_jatuh_tempo' => 'date',
		'tgl_kembali' => 'date',
	];

	public function anggota()
	{
		return $this->belongsTo(anggota::class, 'id_anggota', 'id_anggota');
	}

	public function petugasPinjam()
	{
		return $this->belongsTo(petugas::class, 'id_petugas_pinjam', 'id_petugas');
	}

	public function petugasKembali()
	{
		return $this->belongsTo(petugas::class, 'id_petugas_kembali', 'id_petugas');
	}

	public function itemBuku()
	{
		return $this->belongsToMany(item_buku::class, 'detail_peminjaman', 'id_peminjaman', 'id_item_buku', 'id_peminjaman', 'id_item_buku');
	}

	public function denda()
	{
		return $this->hasMany(denda::class, 'id_peminjaman', 'id_peminjaman');
	}
}