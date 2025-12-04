<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Basic routes for the static landing page and minimal web pages for member
| and officer. API endpoints are defined in routes/api.php already.
|
*/

Route::get('/', function () {
    return view('landing');
});

// MEMBER (ANGGOTA) - Minimal frontend entry points
Route::get('/member', function(){ return view('member.index'); });
// Alias routes used by existing blade templates
Route::get('/register', [AnggotaController::class, 'showRegister']);
Route::post('/register', [AnggotaController::class, 'registerStore']);
Route::get('/login', [AnggotaController::class, 'showLogin']);
Route::post('/login', [AnggotaController::class, 'login']);

Route::get('/member/register', [AnggotaController::class, 'showRegister']);
Route::post('/member/register', [AnggotaController::class, 'registerStore']);
Route::get('/member/login', [AnggotaController::class, 'showLogin']);
Route::post('/member/login', [AnggotaController::class, 'login']);
Route::get('/member/dashboard', function(){ return view('member.dashboard', ['name' => session('anggota_nama') ?? 'Member']); });
Route::get('/member/logout', [AnggotaController::class, 'logout']);

// PETUGAS (OFFICER) - Minimal frontend entry points
Route::get('/petugas/login', [PetugasController::class, 'showLogin']);
Route::post('/petugas/login', [PetugasController::class, 'login']);
Route::get('/petugas/logout', [PetugasController::class, 'logout']);
Route::get('/dashboard', function(){ return view('dashboard'); });
Route::get('/petugas', [PetugasController::class, 'index']);

// Simple pages for officer tools
Route::get('/petugas/books', [BukuController::class, 'index']);
Route::get('/petugas/reports', [LaporanController::class, 'index']);
