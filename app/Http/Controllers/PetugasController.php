<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\petugas;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException; // Digunakan untuk penanganan validasi API yang lebih baik

class PetugasController extends Controller
{
    use ValidatesRequests;

    // Catatan: Model 'petugas' (huruf kecil) mungkin tidak sesuai konvensi. 
    // Sebaiknya ganti nama model menjadi 'Petugas' (huruf kapital) dan ubah di sini jika memungkinkan.
    
    // ============================
    // WEB CRUD 
    // ============================

    public function index()
    {
        // Penggunaan 'nama' untuk sortir, jika tidak ada kolom 'id' yang terdefinisi
        $petugas = petugas::orderBy('nama', 'asc')->paginate(10); 
        return view('petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('petugas.create');
    }

    /**
     * Menyimpan data Petugas baru (digunakan oleh Admin/Manajemen)
     */
    public function store(Request $request)
    {
        try {
            // WEB STORE
            $this->validate($request, [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:petugas,email',
                'kata_sandi' => 'required|string|min:6',
                'jabatan' => 'required|string|max:100', // Batasan panjang
                'role' => 'required|string|in:admin,staff', // Membatasi nilai role
            ]);

            petugas::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'kata_sandi' => Hash::make($request->kata_sandi), 
                'jabatan' => $request->jabatan,
                'role' => $request->role,
                // Menggunakan Carbon/now() untuk kejelasan
                'tgl_bergabung' => $request->tgl_bergabung ?? now()->toDateString(), 
            ]);

            return redirect()->route('petugas.index')->with(['success' => 'Data Petugas Berhasil Disimpan!']);
        } catch (ValidationException $e) {
            // Penanganan jika validasi gagal
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            // Penanganan error umum
            return redirect()->route('petugas.index')->with(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }


    public function edit($id)
    {
        // Mencari berdasarkan kolom primary key yang mungkin bukan 'id' (mungkin 'id_petugas')
        $p = petugas::findOrFail($id); 
        return view('petugas.edit', compact('p'));
    }

    /**
     * Memperbarui data Petugas
     */
    public function update(Request $request, $id)
    {
        try {
            $p = petugas::findOrFail($id);
    
            $this->validate($request, [
                'nama' => 'required|string|max:255',
                // Menggunakan kolom primary key yang tepat (disini diasumsikan 'id_petugas') untuk mengecualikan email saat ini
                'email' => 'required|email|unique:petugas,email,' . $p->id_petugas . ',id_petugas', 
                'jabatan' => 'required|string|max:100',
                'role' => 'required|string|in:admin,staff',
            ]);
    
            $data = [
                'nama' => $request->nama,
                'email' => $request->email,
                'jabatan' => $request->jabatan,
                'role' => $request->role,
                'tgl_bergabung' => $request->tgl_bergabung,
            ];
    
            // Hanya perbarui kata sandi jika ada input baru
            if ($request->filled('kata_sandi')) {
                $this->validate($request, ['kata_sandi' => 'string|min:6']);
                $data['kata_sandi'] = Hash::make($request->kata_sandi); 
            }
    
            $p->update($data);
    
            return redirect()->route('petugas.index')->with(['success' => 'Data Petugas Berhasil Diubah!']);

        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('petugas.index')->with(['error' => 'Petugas tidak ditemukan.']);
        } catch (Exception $e) {
            return redirect()->route('petugas.index')->with(['error' => 'Gagal mengubah data: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $p = petugas::findOrFail($id);
            $p->delete();
            return redirect()->route('petugas.index')->with(['success' => 'Data Petugas Berhasil Dihapus!']);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('petugas.index')->with(['error' => 'Petugas tidak ditemukan.']);
        } catch (Exception $e) {
            return redirect()->route('petugas.index')->with(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }

    // ============================
    // API WRAPPERS
    // ============================

    public function apiIndex()
    {
        // Menggunakan paginate untuk respons API yang lebih baik
        $data = petugas::orderBy('nama', 'asc')->paginate(10); 
        return response()->json(['data' => $data]);
    }

    public function apiCreate()
    {
        return response()->json([
            'message' => 'Metadata untuk membuat petugas',
            'defaults' => [
                'role' => 'staff',
                'jabatan' => 'Staff Umum',
                'tgl_bergabung' => now()->toDateString(),
            ],
            'roles_available' => ['staff', 'admin']
        ]);
    }

    public function apiShow($id)
    {
        try {
            $p = petugas::findOrFail($id);
            return response()->json(['data' => $p]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Petugas tidak ditemukan'], 404);
        }
    }

    /**
     * API login for petugas, returns a JSON token on success.
     * HARUS menggunakan HasApiTokens di Model Petugas (dari Sanctum/Passport).
     */
    public function apiLogin(Request $request)
    {
        // Menggunakan try-catch untuk penanganan validasi API
        try {
            $this->validate($request, [
                'email' => 'required|email',
                // Di API, field input yang umum adalah 'password', bukan 'kata_sandi'
                'password' => 'required|string|min:6', 
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        // Mencari petugas berdasarkan email
        $p = petugas::where('email', $request->email)->first();

        // Cek keberadaan petugas dan verifikasi kata sandi
        if (!$p || !Hash::check($request->password, $p->kata_sandi)) {
            return response()->json(['message' => 'Email atau kata sandi salah'], 401);
        }

        // Perbaikan: Pastikan model Petugas memiliki trait HasApiTokens.
        // Anda sudah memperbaiki ini berdasarkan instruksi sebelumnya.
        // Jika masih error, ulangi langkah penambahan HasApiTokens.
        $token = $p->createToken('petugas-api-token', ['role:' . $p->role])->plainTextToken; 

        // Ensure the returned object contains a 'role' field inside user data
        $p->role = 'petugas';
        $p->role_name = $p->role;

        return response()->json([
            'message' => 'Login berhasil', 
            'data' => $p, 
            'user' => $p,
            'role' => 'petugas',
            'role_name' => $p->role,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function apiStore(Request $request)
    {
        // Disable API registration for petugas (officer) by default.
        // Set ALLOW_PETUGAS_REGISTER=true in .env to re-enable.
        if (! env('ALLOW_PETUGAS_REGISTER', false)) {
            return response()->json(['message' => 'Petugas creation disabled. Use the seeded admin account.'], 403);
        }

        try {
            $this->validate($request, [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:petugas,email',
                // Menggunakan 'password' sebagai field input standar untuk API, 
                // meskipun disimpan di kolom 'kata_sandi' DB
                'password' => 'required|string|min:6', 
                'jabatan' => 'required|string|max:100',
                'role' => 'sometimes|string|in:admin,staff',
            ]);

            $created = petugas::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'kata_sandi' => Hash::make($request->password), // Menggunakan Hash::make dari input 'password'
                'jabatan' => $request->jabatan,
                'role' => $request->role ?? 'staff',
                'tgl_bergabung' => $request->tgl_bergabung ?? now()->toDateString(),
            ]);

            // Include 'role' so frontend gets consistent data
            $token = null;
            try { $token = $created->createToken('petugas-api-token')->plainTextToken; } catch (\Exception $e) {}
            return response()->json(['message' => 'Petugas berhasil dibuat.', 'data' => $created, 'token' => $token, 'role' => 'petugas', 'role_name' => $created->role], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal membuat petugas: ' . $e->getMessage()], 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        try {
            $p = petugas::findOrFail($id);

            $this->validate($request, [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:petugas,email,' . $p->id_petugas . ',id_petugas',
                'jabatan' => 'sometimes|string|max:100',
                'role' => 'sometimes|string|in:admin,staff',
            ]);

            $data = [
                'nama' => $request->nama,
                'email' => $request->email,
                'jabatan' => $request->jabatan ?? $p->jabatan,
                'role' => $request->role ?? $p->role,
                'tgl_bergabung' => $request->tgl_bergabung ?? $p->tgl_bergabung,
            ];

            // Menggunakan 'password' untuk API
            if ($request->filled('password')) {
                $this->validate($request, ['password' => 'string|min:6']);
                $data['kata_sandi'] = Hash::make($request->password); 
            }

            $p->update($data);

            return response()->json(['message' => 'Petugas diupdate', 'data' => $p]);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Petugas tidak ditemukan'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Gagal mengupdate petugas: ' . $e->getMessage()], 500);
        }
    }
    
    public function apiDestroy($id)
    {
        try {
            $p = petugas::findOrFail($id);
            $p->delete();
            return response()->json(['message' => 'Petugas dihapus']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Petugas tidak ditemukan'], 404);
        }
    }

    public function apiLogout(Request $request)
    {
        // Memeriksa apakah user (petugas) terautentikasi melalui Sanctum/Passport
        $petugas = $request->user();

        if ($petugas) {
            // Mencabut token yang saat ini digunakan (token yang dikirim dalam request)
            $petugas->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Berhasil keluar. Token telah dicabut.'], 200);
    }
    
    // ============================
    // WEB AUTHENTICATION 
    // ============================

    /**
     * Tampilkan halaman register untuk petugas baru (hanya untuk staff/web, bukan Admin CRUD)
     */
    public function showRegister()
    {
        // Asumsi view untuk register Petugas adalah 'PetugasRegister.index'
        return view('PetugasRegister.index');
    }

    /**
     * Menangani pendaftaran petugas via Web
     */
    public function registerStore(Request $request)
    {
        try {
            $this->validate($request, [
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:petugas,email',
                'password' => 'required|string|min:6|same:password_confirmation',
            ]);

            petugas::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'kata_sandi' => Hash::make($request->password),
                'jabatan' => 'Staff Biasa',
                'role' => 'staff', // Default role untuk pendaftaran mandiri
                'tgl_bergabung' => now()->toDateString(),
            ]);

            // Redirect ke halaman login setelah pendaftaran berhasil
            return redirect(url('petugas/login'))->with(['success' => 'Pendaftaran petugas berhasil! Silakan login.']);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with(['error' => 'Pendaftaran gagal: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan halaman login untuk petugas
     */
    public function showLogin()
    {
        return view('PetugasLogin.index');
    }

    /**
     * Handle login form submission for petugas
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $p = petugas::where('email', $request->email)->first();
        
        // Cek keberadaan petugas dan verifikasi kata sandi
        if (!$p || !Hash::check($request->password, $p->kata_sandi)) {
            return redirect()->back()->withInput()->with(['error' => 'Email atau kata sandi salah']);
        }

        // Set session untuk menandai petugas telah login
        Session::put('petugas_id', $p->id_petugas);
        Session::put('petugas_nama', $p->nama);
        Session::put('petugas_role', $p->role); 

        return redirect('/dashboard')->with(['success' => 'Login berhasil sebagai ' . $p->role]);
    }

    /**
     * Logout petugas (Web Session)
     */
    public function logout()
    {
        Session::forget(['petugas_id', 'petugas_nama', 'petugas_role']);
        return redirect('/petugas/login')->with(['success' => 'Berhasil keluar']);
    }
}