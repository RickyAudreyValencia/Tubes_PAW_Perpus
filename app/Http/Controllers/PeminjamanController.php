<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\peminjaman;
use App\Models\anggota;
use App\Models\petugas;
use App\Models\item_buku;
use App\Models\buku;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Support\Facades\DB;
class PeminjamanController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        $peminjaman = peminjaman::with(['anggota', 'itemBuku'])->latest()->paginate(10);
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $anggotas = anggota::all();
        $petugass = petugas::all();
        $items = item_buku::where('status', 'tersedia')->get();
        return view('peminjaman.create', compact('anggotas','petugass','items'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'id_petugas_pinjam' => 'required|exists:petugas,id_petugas',
            'tgl_pinjam' => 'required|date',
            'tgl_jatuh_tempo' => 'required|date|after_or_equal:tgl_pinjam',
            'id_item_buku' => 'required|exists:item_buku,id_item_buku',
        ]);

        // Cari item buku
        $item = item_buku::findOrFail($request->id_item_buku);

        $p = peminjaman::create([
            'id_anggota' => $request->id_anggota,
            'id_petugas_pinjam' => $request->id_petugas_pinjam,
            'id_item_buku' => $request->id_item_buku,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
            'status' => $request->status ?? 'pinjam',
        ]);

        // mark item as not available dan kurangi stok
        if ($item) { 
            $item->update(['status' => 'dipinjam']);
            // Kurangi stok buku
            $bukuModel = buku::find($item->id_buku);
            if ($bukuModel) {
                $bukuModel->decrement('stok');
            }
        }

        return redirect()->route('peminjaman.index')->with(['success' => 'Peminjaman berhasil dibuat']);
    }

    public function edit($id)
    {
        $p = peminjaman::with('itemBuku')->findOrFail($id);
        $anggotas = anggota::all();
        $petugass = petugas::all();
        $items = item_buku::all();
        return view('peminjaman.edit', compact('p','anggotas','petugass','items'));
    }

    public function apiUpdate(Request $request, $id)
{
    $p = peminjaman::where('id_peminjaman', $id)->firstOrFail();

    $this->validate($request, [
        'tgl_jatuh_tempo' => 'sometimes|date',
    ]);

    $p->update([
        'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Tanggal jatuh tempo berhasil diupdate',
        'data' => $p->fresh(['anggota', 'itemBuku'])
    ]);
}

        // ============================
    // API ONLY - POSTMAN
    // TAMBAHAN TANPA MENGUBAH LOGIKA WEB
    // ============================

    public function apiIndex()
    {
        $data = peminjaman::with([
            'anggota',
            'itemBuku.buku'   // <-- tambahkan ini
        ])->latest()->get();
        return response()->json(['data' => $data]);
    }

    public function apiShow($id)
    {
        $data = peminjaman::with(['anggota','itemBuku'])
                ->findOrFail($id);
        return response()->json(['data' => $data]);
    }

   public function apiStore(Request $request)
    {
        // Validate input
        $this->validate($request, [
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'tanggal_pinjam' => 'required|date',
            'id_buku' => 'required|exists:buku,id_buku'
        ]);

        // Cek stok di tabel buku
        $bukuModel = buku::findOrFail($request->id_buku);
        if (!$bukuModel || $bukuModel->stok <= 0) {
            return response()->json(['message' => 'Tidak ada stok item buku tersedia'], 400);
        }

        // Cari item buku yang tersedia (status = 'tersedia')
        $item = item_buku::where('id_buku', $request->id_buku)
            ->where('status', 'tersedia')
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Tidak ada stok item buku tersedia'], 400);
        }

        // Ambil petugas pertama (jika tidak diinput)
        $petugas = petugas::first();
        if (!$petugas) {
            return response()->json(['message' => 'Tidak ada petugas tersedia'], 400);
        }

        // Membuat peminjaman
        $p = Peminjaman::create([
            'id_anggota' => $request->id_anggota,
            'id_petugas_pinjam' => $petugas->id_petugas,
            'id_item_buku' => $item->id_item_buku,   // <-- SIMPAN DI SINI!
            'tgl_pinjam' => $request->tanggal_pinjam,
            'tgl_jatuh_tempo' => $request->tanggal_pinjam,
            'status' => 'pending',
        ]);

        // Update status item buku ke pending (belum dipinjam, tunggu disetujui)
        $item->update(['status' => 'pending']);

        return response()->json([
            'message' => 'Peminjaman berhasil dibuat',
            'data' => $p->load(['anggota', 'itemBuku.buku'])
        ]);
    }

    public function apiDestroy($id)
    {
         $peminjaman = peminjaman::where('id_peminjaman', $id)
                ->with(['anggota', 'itemBuku'])
                ->first();

         if(!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan',
            ], 404);
        }
        
        // Hanya allow cancel jika status masih 'pending'
        if ($peminjaman->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman dengan status ' . $peminjaman->status . ' tidak bisa dibatalkan. Hanya peminjaman pending yang bisa dibatalkan.',
            ], 400);
        }
        
        // Kembalikan status item ke tersedia (tidak perlu tambah stok karena belum dikurangi)
        if ($peminjaman->itemBuku) {
            $itemBuku = $peminjaman->itemBuku;
            $itemBuku->update(['status' => 'tersedia']);
        }
        
        $peminjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dibatalkan',
        ]);
    }

    public function apiUpdateStatusToPinjam($id)
    {
        try {
            // Cari peminjaman dengan item buku
            $peminjaman = peminjaman::where('id_peminjaman', $id)
                ->with(['anggota', 'itemBuku'])
                ->first();
            
            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman tidak ditemukan'
                ], 404);
            }
            
            if ($peminjaman->status === 'pinjam') {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman sudah berstatus pinjam'
                ], 400);
            }
            
            // Update status peminjaman menjadi pinjam
            $peminjaman->update([
                'status' => 'pinjam',
                'tgl_pinjam' => now(),
            ]);
            
            // Update hanya item yang terkait peminjaman ini
            $itemBuku = $peminjaman->itemBuku;
            if ($itemBuku) {
                if ($itemBuku->status !== 'dipinjam') {
                    $itemBuku->update(['status' => 'dipinjam']);
                    
                    // Kurangi stok buku HANYA saat status berubah dari pending ke dipinjam
                    $bukuModel = buku::find($itemBuku->id_buku);
                    if ($bukuModel) {
                        $bukuModel->decrement('stok');
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Status peminjaman berhasil diubah menjadi pinjam',
                'data' => $peminjaman->fresh(['anggota', 'itemBuku'])
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function apiReturn($id)
    {
        try {
            // Cari peminjaman yang sudah dipinjam
            $peminjaman = peminjaman::where('id_peminjaman', $id)
                ->with(['anggota', 'itemBuku'])
                ->first();
            
            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman tidak ditemukan'
                ], 404);
            }
            
            if ($peminjaman->status !== 'pinjam') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya peminjaman dengan status pinjam yang bisa dikembalikan'
                ], 400);
            }
            
            // Update status peminjaman menjadi kembali
            $peminjaman->update([
                'status' => 'kembali',
                'tgl_kembali' => now(),
            ]);
            
            // Kembalikan item dan tambah stok
            $itemBuku = $peminjaman->itemBuku;
            if ($itemBuku) {
                $itemBuku->update(['status' => 'tersedia']);
                
                // Tambah stok buku
                $bukuModel = buku::find($itemBuku->id_buku);
                if ($bukuModel) {
                    $bukuModel->increment('stok');
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dikembalikan',
                'data' => $peminjaman->fresh(['anggota', 'itemBuku'])
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

}