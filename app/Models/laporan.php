<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class laporan extends Model
{
	use HasFactory;

	protected $table = 'laporan';
	protected $primaryKey = 'id_laporan';

	// Migration only defines created_at, no updated_at
	public $timestamps = false;

	protected $fillable = [
		'periode',
		'total_peminjaman',
		'total_buku_dipinjam',
		'total_denda',
		'dibuat_oleh',
		'created_at',
	];

	protected $casts = [
		'total_denda' => 'decimal:2',
		'created_at' => 'datetime',
	];

	public function pembuat()
	{
		return $this->belongsTo(petugas::class, 'dibuat_oleh', 'id_petugas');
	}
}