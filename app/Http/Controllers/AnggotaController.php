<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\anggota;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AnggotaController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        $anggota = anggota::latest()->paginate(10);
        return view('anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('anggota.create');
    }


    public function edit($id)
    {
        $a = anggota::findOrFail($id);
        return view('anggota.edit', compact('a'));
    }

    public function update(Request $request, $id)
    {
        $a = anggota::findOrFail($id);

        $this->validate($request, [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:anggota,email,' . $a->id_anggota . ',id_anggota',
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'status_keanggotaan' => $request->status_keanggotaan,
        ];

        if ($request->filled('kata_sandi')) {
            $data['kata_sandi'] = bcrypt($request->kata_sandi);
        }

        $a->update($data);

        return redirect()->route('anggota.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id)
    {
        $a = anggota::findOrFail($id);
        $a->delete();

        return redirect()->route('anggota.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    /* API wrappers (hotel-style names) */
    public function apiIndex()
    {
        $data = anggota::latest()->get();
        return response()->json(['data' => $data]);
    }

    public function apiCreate()
    {
        // Return any metadata needed to create an anggota (e.g., allowed statuses)
        return response()->json([
            'message' => 'Endpoint to show data needed for creating anggota',
            'defaults' => [
                'status_keanggotaan' => 'aktif',
            ],
        ]);
    }

    public function apiShow($id)
    {
        $a = anggota::findOrFail($id);
        return response()->json(['data' => $a]);
    }

    /**
     * API login for anggota, returns a JSON token on success
     */
    public function apiLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $a = anggota::where('email', $request->email)->first();
        if (!$a || !Hash::check($request->password, $a->kata_sandi)) {
            return response()->json(['message' => 'Email atau kata sandi salah'], 401);
        }

        // create personal access token
        $token = $a->createToken('api-token')->plainTextToken;

        // include role property inside the user object and return both 'user' and 'role' fields
        $a->role = 'anggota';

        return response()->json([
            'message' => 'Login berhasil',
            'data' => $a,
            'user' => $a,
            'token' => $token,
            'role' => 'anggota'
        ]);
    }

    public function apiStore(Request $request)
    {
        // Simplified registration: require only name (or nama), email, password (+ confirmation)
        $this->validate($request, [
            'name' => 'sometimes|required_without:nama|string|max:255',
            'nama' => 'sometimes|required_without:name|string|max:255',
            'email' => 'required|email|unique:anggota,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // map `name` to `nama` for database field
        $nama = $request->filled('nama') ? $request->nama : $request->name;

        $created = anggota::create([
            'nama' => $nama,
            'email' => $request->email,
            'kata_sandi' => bcrypt($request->password),
            // Optional fields left blank if not provided
            'nomor_telepon' => $request->nomor_telepon ?? null,
            'alamat' => $request->alamat ?? null,
            'status_keanggotaan' => $request->status_keanggotaan ?? 'aktif',
        ]);

        // // generate API token for the new anggota using Sanctum and return it (plain)
        // $token = null;
        // try {
        //     $token = $created->createToken('api-token')->plainTextToken;
        // } catch (\Exception $e) {
        //     // if token creation fails, continue but return created data
        // }

        // Create token and return created object with role so frontend receives role immediately
        $token = null;
        try {
            $token = $created->createToken('api-token')->plainTextToken;
        } catch (\Exception $e) {
            // token creation may fail in some environments; still return created data
        }

        // ensure frontend gets consistent payload including role and user
        $created->role = 'anggota';

        return response()->json([
            'message' => 'Anggota dibuat. Silakan login untuk mendapatkan token.',
            'data' => $created,
            'token' => $token,
            'role' => 'anggota',
            'user' => $created
        ], 201);
    }

    /**
     * Web store (register) - handle a web register form submission.
     */
    public function store(Request $request)
    {
        // Accept either 'nama' or 'name' field from the form
        $this->validate($request, [
            'name' => 'sometimes|required_without:nama|string|max:255',
            'nama' => 'sometimes|required_without:name|string|max:255',
            'email' => 'required|email|unique:anggota,email',
            'password' => 'required|string|min:6|same:password_confirmation',
        ]);

        $nama = $request->filled('nama') ? $request->nama : $request->name;

        $created = anggota::create([
            'nama' => $nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'status_keanggotaan' => $request->status_keanggotaan ?? 'aktif',
        ]);

        return redirect()->route('anggota.index')->with(['success' => 'Akun anggota berhasil dibuat! Silakan login.']);
    }

    /**
     * Show register page for web users (uses views/Register/index.blade.php)
     */
    public function showRegister()
    {
        $registers = anggota::latest()->paginate(10);
        return view('Register.index', compact('registers'));
    }

    /**
     * Register endpoint that mirrors API store but returns web redirects
     */
    public function registerStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:anggota,email',
            'password' => 'required|string|min:6|same:password_confirmation',
            'agree' => 'sometimes|accepted',
        ]);

        $created = anggota::create([
            'nama' => $request->name,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->password),
            'status_keanggotaan' => 'aktif',
        ]);

        return redirect(url('login'))->with(['success' => 'Pendaftaran berhasil! Silakan login.']);
    }

    /**
     * Show login page for web users (uses views/Login/index.blade.php)
     */
    public function showLogin()
    {
        return view('Login.index');
    }

    /**
     * Handle login form submission for anggota
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $a = anggota::where('email', $request->email)->first();
        if (!$a || !Hash::check($request->password, $a->kata_sandi)) {
            return redirect()->back()->withInput()->with(['error' => 'Email atau kata sandi salah']);
        }

        // Set session to mark anggota as logged in
        Session::put('anggota_id', $a->id_anggota);
        Session::put('anggota_nama', $a->nama);
        // support remember (optional) - if needed implement cookie

        return redirect('/')->with(['success' => 'Login berhasil']);
    }

    /**
     * Logout anggota
     */
    public function logout()
    {
        Session::forget('anggota_id');
        Session::forget('anggota_nama');
        return redirect('/login')->with(['success' => 'Berhasil keluar']);
    }

    public function apiUpdate(Request $request, $id)
    {
        $a = anggota::findOrFail($id);

        $this->validate($request, [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:anggota,email,' . $a->id_anggota . ',id_anggota',
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
            'alamat' => $request->alamat,
            'status_keanggotaan' => $request->status_keanggotaan,
        ];

        if ($request->filled('kata_sandi')) {
            $data['kata_sandi'] = bcrypt($request->kata_sandi);
        }

        $a->update($data);

        return response()->json(['message' => 'Anggota diupdate', 'data' => $a]);
    }

    /**
     * Update current authenticated user's profile (PUT/POST /anggota/me)
     */
    public function apiUpdateMe(Request $request)
    {
        $a = $request->user(); // Get authenticated user

        if (!$a) {
            return response()->json(['message' => 'User tidak terautentikasi'], 401);
        }

        $this->validate($request, [
            'nama' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:anggota,email,' . $a->id_anggota . ',id_anggota',
            'nomor_telepon' => 'sometimes|nullable|string',
            'alamat' => 'sometimes|nullable|string',
            'kata_sandi' => 'sometimes|nullable|string|min:6',
        ]);

        $data = [];

        if ($request->filled('nama')) {
            $data['nama'] = $request->nama;
        }
        if ($request->filled('email')) {
            $data['email'] = $request->email;
        }
        if ($request->filled('nomor_telepon')) {
            $data['nomor_telepon'] = $request->nomor_telepon;
        }
        if ($request->filled('alamat')) {
            $data['alamat'] = $request->alamat;
        }
        if ($request->filled('kata_sandi')) {
            $data['kata_sandi'] = bcrypt($request->kata_sandi);
        }

        $a->update($data);

        return response()->json(['message' => 'Profil diupdate', 'data' => $a]);
    }

    /**
     * Delete current authenticated user's account (DELETE /anggota/me)
     */
    public function apiDeleteMe(Request $request)
    {
        $a = $request->user(); // Get authenticated user

        if (!$a) {
            return response()->json(['message' => 'User tidak terautentikasi'], 401);
        }

        $id = $a->id_anggota;
        $a->delete();

        return response()->json(['message' => 'Akun berhasil dihapus']);
    }

    public function apiDestroy($id)
    {
        $a = anggota::findOrFail($id);
        $a->delete();

        return response()->json(['message' => 'Anggota dihapus']);
    }

    public function apiLogout(Request $request)
    {
        // Mendapatkan anggota yang sedang terautentikasi (berdasarkan token Bearer)
        $anggota = $request->user();

        // Mencabut token yang saat ini digunakan (token yang dikirim dalam request)
        if ($anggota) {
            $anggota->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Berhasil keluar. Token telah dicabut.'], 200);
    }


    public function me(Request $request)
{
    $user = $request->user(); // ambil user dari token
    
    if (!$user) {
        return response()->json([
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'data' => $user
    ]);
}

}

