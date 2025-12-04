<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Perubahan: Menggunakan Authenticatable
use Laravel\Sanctum\HasApiTokens; // Tambahkan: Digunakan untuk createToken() dan API Authentication

// Perubahan: Mengubah kelas induk dari Model menjadi Authenticatable
class petugas extends Authenticatable
{
    use HasFactory, HasApiTokens; // Perubahan: Menambahkan HasApiTokens

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'jabatan',
        'role',
        'tgl_bergabung',
        'remember_token',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    protected $casts = [
        'tgl_bergabung' => 'date',
    ];

    public function peminjamanPinjam()
    {
        return $this->hasMany(peminjaman::class, 'id_petugas_pinjam', 'id_petugas');
    }

    public function peminjamanKembali()
    {
        return $this->hasMany(peminjaman::class, 'id_petugas_kembali', 'id_petugas');
    }

    public function denda()
    {
        return $this->hasMany(denda::class, 'id_petugas', 'id_petugas');
    }

    public function laporan()
    {
        return $this->hasMany(laporan::class, 'dibuat_oleh', 'id_petugas');
    }
}