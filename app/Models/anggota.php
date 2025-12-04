<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class anggota extends Model
{
	use HasApiTokens, HasFactory;

	protected $table = 'anggota';
	protected $primaryKey = 'id_anggota';

	// Allow mass assignment
	protected $fillable = [
		'nama',
		'email',
		'kata_sandi',
		'nomor_telepon',
		'alamat',
		'status_keanggotaan',
	];

	// Hide sensitive fields when serializing
	protected $hidden = [
		'kata_sandi',
	];

	public function peminjaman()
	{
		return $this->hasMany(peminjaman::class, 'id_anggota', 'id_anggota');
	}
}

