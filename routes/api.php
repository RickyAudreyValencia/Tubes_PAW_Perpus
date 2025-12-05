<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DetailPeminjamanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController; // <-- Import BukuController
use App\Http\Controllers\KategoriController;


/*
|--------------------------------------------------------------------------
| API ANGGOTA (Public Routes - Unprotected)
|--------------------------------------------------------------------------
| Rute-rute ini tidak memerlukan API token karena tujuannya adalah untuk
| membuat akun atau mendapatkan token.
*/

// [PUBLIC] Pendaftaran Anggota (Register)
Route::post('/anggota/create', [AnggotaController::class, 'apiStore']);
// Alias untuk register jika menggunakan endpoint yang berbeda
Route::post('/anggota/register', [AnggotaController::class, 'apiStore']); 





// [PUBLIC] Login Anggota (Mendapatkan Token)
Route::post('/anggota/login', [AnggotaController::class, 'apiLogin']);


/*
|--------------------------------------------------------------------------
| API PETUGAS (Public Routes - Unprotected)
|--------------------------------------------------------------------------
*/


// [PUBLIC] Login Petugas (Mendapatkan Token)
Route::post('/petugas/login', [PetugasController::class, 'apiLogin']);


/*
|--------------------------------------------------------------------------
| API ANGGOTA & AUTH (Protected Routes - Requires Token)
|--------------------------------------------------------------------------
| Rute-rute ini dilindungi oleh middleware 'auth:sanctum'.
| Pengguna harus menyertakan Bearer Token yang sah.
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // [PROTECTED] Logout (Berlaku untuk Anggota dan Petugas)
    Route::post('/logout', function (Request $request) {
        $user = $request->user();
        if ($user) {
            // Mencabut token yang saat ini digunakan
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Berhasil keluar. Token telah dicabut.'], 200);
        }
        return response()->json(['message' => 'Gagal keluar. Pengguna tidak terautentikasi.'], 401);
    })->name('logout');

    // [PROTECTED] Melihat semua anggota (Index)
    Route::get('/anggota', [AnggotaController::class, 'apiIndex']);
    
    // [PROTECTED] Get current user profile
    Route::get('/anggota/me', function (Request $request) {
        return response()->json(['data' => $request->user()]);
    });
    
    // [PROTECTED] Update current user profile (using authenticated user)
    Route::put('/anggota/me', [AnggotaController::class, 'apiUpdateMe']);
    Route::post('/anggota/me', [AnggotaController::class, 'apiUpdateMe']);
    
    // [PROTECTED] Delete current user account
    Route::delete('/anggota/me', [AnggotaController::class, 'apiDeleteMe']);
    
    // [PROTECTED] Update data anggota by ID
    Route::post('/anggota/update/{id}', [AnggotaController::class, 'apiUpdate']);
    Route::put('/anggota/update/{id}', [AnggotaController::class, 'apiUpdate']);
    
    // [PROTECTED] Hapus anggota
    Route::delete('/anggota/delete/{id}', [AnggotaController::class, 'apiDestroy']);

        Route::get('/login', function () {
        return response()->json(['message' => 'Unauthorized'], 401);
    })->name('login');



    /*
    |--------------------------------------------------------------------------
    | API DETAIL PEMINJAMAN 
    |--------------------------------------------------------------------------
    */
    Route::get('/detail-peminjaman', [DetailPeminjamanController::class, 'apiIndex']);
    Route::post('/detail-peminjaman/create', [DetailPeminjamanController::class, 'apiStore']);
    Route::post('/detail-peminjaman/update/{id_peminjaman}/{id_item_buku}', [DetailPeminjamanController::class, 'apiUpdate']);
    Route::delete('/detail-peminjaman/delete/{id}', [DetailPeminjamanController::class, 'apiDestroy']);


    Route::put('/peminjaman/{id}/update-status-pinjam', [PeminjamanController::class, 'apiUpdateStatusToPinjam']);
    Route::put('/peminjaman/{id}/return', [PeminjamanController::class, 'apiReturn']);
    /*
    |--------------------------------------------------------------------------
    | API PETUGAS 
    |--------------------------------------------------------------------------
    */
    Route::get('/petugas', [PetugasController::class, 'apiIndex']);
    Route::post('/petugas/create', [PetugasController::class, 'apiStore']);
    Route::post('/petugas/update/{id}', [PetugasController::class, 'apiUpdate']);
    Route::delete('/petugas/delete/{id}', [PetugasController::class, 'apiDestroy']);


    /*
    |--------------------------------------------------------------------------
    | API PEMINJAMAN 
    |--------------------------------------------------------------------------
    */
    Route::get('/peminjaman', [PeminjamanController::class, 'apiIndex']);
    Route::post('/peminjaman', [PeminjamanController::class, 'apiStore']);
    Route::post('/peminjaman/create', [PeminjamanController::class, 'apiStore']);
    Route::put('/peminjaman/update/{id}', [PeminjamanController::class, 'apiUpdate']);
    Route::delete('/peminjaman/delete/{id}', [PeminjamanController::class, 'apiDestroy']);

    
    /*
    |--------------------------------------------------------------------------
    | API BUKU 
    |--------------------------------------------------------------------------
    */
    
});

// [PROTECTED] Melihat semua buku (Index - Mengembalikan JSON)
Route::get('/buku', [BukuController::class, 'index']); 
// [PROTECTED] Membuat buku baru (Store)
Route::post('/buku/create', [BukuController::class, 'store']);
// [PROTECTED] Mengubah data buku (Update)
Route::post('/buku/update/{id}', [BukuController::class, 'update']);
// [PROTECTED] Menghapus buku (Destroy)
Route::delete('/buku/delete/{id}', [BukuController::class, 'destroy']);


// NOTE: Petugas registration is disabled. System ships with a single admin account.

// [PUBLIC] Login Petugas (Mendapatkan Token)
Route::post('/petugas/login', [PetugasController::class, 'apiLogin']);

// =========================================================================
// !!! PENAMBAHAN: ALIAS UNTUK ENDPOINT LOGIN FRONTEND (POST /api/login) !!!
// =========================================================================
// Route ini dicari oleh frontend Anda (api.post('/login'))
// Ganti menjadi AuthController::login agar route /api/login mencoba login
// ke petugas dan anggota (fallback) — ini memperbolehkan Anggota login
Route::post('/login', [AuthController::class, 'login']); 

// =========================================================================
// !!! PENAMBAHAN: ALIAS UNTUK ENDPOINT REGISTER FRONTEND (POST /api/register) !!!
// =========================================================================
// Map /api/register to Anggota (member) registration endpoint. Petugas (officer)
// registration is disabled — admin account is seeded.
Route::post('/register', [AnggotaController::class, 'apiStore']);

Route::middleware('auth:sanctum')->get('/kategori', [KategoriController::class, 'index']);




/*
|--------------------------------------------------------------------------
| LOGIN PETUGAS (INI DIPINDAHKAN DAN DIBAIKAN KARENA SUDAH ADA API LOGIN DI ATAS)
|--------------------------------------------------------------------------
| Route::post('/petugas/login', [AuthController::class,'login']); 
| Route ini diabaikan karena API login petugas sudah ditangani oleh 
| PetugasController::class, 'apiLogin' di bagian Public Routes.
*/

// Catatan: Jika Anda menggunakan AuthController::class, 'login' untuk web, maka route tersebut harus ada di web.php.