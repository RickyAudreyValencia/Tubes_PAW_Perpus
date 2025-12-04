<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\petugas; // Use the model classes present in the project (case-insensitive)
use App\Models\anggota;

class AuthController extends Controller
{
    /**
     * Menangani login untuk Petugas atau Anggota.
     * Endpoint ini dipanggil oleh frontend POST /api/login.
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $credentials = $request->only('email', 'password');

        // 1. Coba login sebagai PETUGAS (lookup manual)
        $p = petugas::where('email', $request->email)->first();
        if ($p && Hash::check($request->password, $p->kata_sandi)) {
            $token = $p->createToken('petugas-token', ['petugas'])->plainTextToken;
            // ensure role property exists in returned user object
            $p->role = $p->role ?? 'petugas';
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $p,
                'role' => 'petugas'
            ], 200);
        }

        // 2. Coba login sebagai ANGGOTA (lookup manual)
        $a = anggota::where('email', $request->email)->first();
        if ($a && Hash::check($request->password, $a->kata_sandi)) {
            $token = $a->createToken('anggota-token', ['anggota'])->plainTextToken;
            $a->role = 'anggota';
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $a,
                'role' => 'anggota'
            ], 200);
        }

        // Gagal total
        return response()->json(['message' => 'Kredensial tidak valid'], 401);
    }

    /**
     * Mencabut token aktif saat ini (Logout API).
     * Endpoint ini dipanggil oleh frontend POST /api/logout.
     * Memerlukan middleware auth:sanctum
     */
    public function logout(Request $request)
    {
        // Mencabut token yang digunakan untuk request saat ini
        // Laravel Sanctum/Auth secara otomatis tahu user mana yang sedang login
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout. Token telah dicabut.'], 200);
    }
}