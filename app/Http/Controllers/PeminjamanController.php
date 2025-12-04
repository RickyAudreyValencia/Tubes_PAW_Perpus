<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\peminjaman;
use App\Models\anggota;
use App\Models\petugas;
use App\Models\item_buku;
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
            'item_ids' => 'required|array|min:1',
            'item_ids.*' => 'exists:item_buku,id_item_buku',
        ]);

        $p = peminjaman::create([
            'id_anggota' => $request->id_anggota,
            'id_petugas_pinjam' => $request->id_petugas_pinjam,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
            'status' => $request->status ?? 'pinjam',
        ]);

        // attach items
        if ($request->filled('item_ids')) {
            foreach ($request->item_ids as $itemId) {
                $p->itemBuku()->attach($itemId);
                // mark item as not available
                $ib = item_buku::find($itemId);
                if ($ib) { $ib->update(['status' => 'dipinjam']); }
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

    public function update(Request $request, $id)
    {
        $p = peminjaman::findOrFail($id);

        $this->validate($request, [
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'id_petugas_pinjam' => 'required|exists:petugas,id_petugas',
            'tgl_pinjam' => 'required|date',
            'tgl_jatuh_tempo' => 'required|date|after_or_equal:tgl_pinjam',
        ]);

        $p->update([
            'id_anggota' => $request->id_anggota,
            'id_petugas_pinjam' => $request->id_petugas_pinjam,
            'id_petugas_kembali' => $request->id_petugas_kembali,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo,
            'tgl_kembali' => $request->tgl_kembali,
            'status' => $request->status,
        ]);

        // If items provided, sync and update statuses
        if ($request->has('item_ids')) {
            $old = $p->itemBuku->pluck('id_item_buku')->toArray();
            $p->itemBuku()->sync($request->item_ids);

            // mark newly attached items as dipinjam
            foreach ($request->item_ids as $itemId) {
                $ib = item_buku::find($itemId); if ($ib) { $ib->update(['status'=>'dipinjam']); }
            }
            // mark items removed as tersedia
            foreach (array_diff($old, $request->item_ids) as $removedId) {
                $ib = item_buku::find($removedId); if ($ib) { $ib->update(['status'=>'tersedia']); }
            }
        }

        return redirect()->route('peminjaman.index')->with(['success' => 'Peminjaman berhasil diupdate']);
    }

    public function destroy($id)
    {
        $p = peminjaman::findOrFail($id);
        // set items back to available
        foreach ($p->itemBuku as $ib) { $ib->update(['status' => 'tersedia']); }
        $p->itemBuku()->detach();
        $p->delete();

        return redirect()->route('peminjaman.index')->with(['success' => 'Peminjaman dihapus']);
    }

        // ============================
    // API ONLY - POSTMAN
    // TAMBAHAN TANPA MENGUBAH LOGIKA WEB
    // ============================

    public function apiIndex()
    {
        $data = peminjaman::with(['anggota','itemBuku'])->latest()->get();
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
        // Validate required fields - id_petugas_pinjam is optional and will be auto-assigned
        // Support both 'id' and 'id_buku' field names
        $bookId = $request->id_buku ?? $request->id ?? null;
        
        if (!$bookId) {
            return response()->json(['message' => 'Book ID (id_buku or id) is required'], 422);
        }

        $this->validate($request, [
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'id_petugas_pinjam' => 'nullable|exists:petugas,id_petugas',
        ]);

        // Verify book exists - try both id and id_buku field names
        $buku = null;
        if (is_numeric($bookId)) {
            // Try as id_buku first (most common)
            $buku = \DB::table('buku')->where('id_buku', $bookId)->first();
            if (!$buku) {
                // Try as id
                $buku = \DB::table('buku')->where('id', $bookId)->first();
            }
        }

        if (!$buku) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        // Get id_petugas_pinjam or auto-assign to first petugas
        $id_petugas_pinjam = $request->id_petugas_pinjam;
        if (!$id_petugas_pinjam) {
            $petugas = petugas::first();
            if (!$petugas) {
                return response()->json(['message' => 'No officer available. Please contact admin.'], 400);
            }
            $id_petugas_pinjam = $petugas->id_petugas;
        }

        // Map field names
        $tgl_pinjam = $request->tanggal_pinjam ?? now()->format('Y-m-d');
        $tgl_jatuh_tempo = $request->tanggal_kembali;

        try {
            $p = peminjaman::create([
                'id_anggota' => $request->id_anggota,
                'id_petugas_pinjam' => $id_petugas_pinjam,
                'tgl_pinjam' => $tgl_pinjam,
                'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
                'status' => 'pinjam',
            ]);

            // Get the actual id_buku from the book record
            $actualBookId = $buku->id_buku ?? $buku->id ?? $bookId;

            // Find all item_buku for this book that are available
            $items = item_buku::where('id_buku', $actualBookId)
                               ->where('status', 'tersedia')
                               ->limit(1)
                               ->get();
            
            // If no available items, check if items exist at all
            if ($items->isEmpty()) {
                $totalItems = item_buku::where('id_buku', $actualBookId)->count();
                
                if ($totalItems === 0) {
                    // No items exist, create a new one with auto-generated kode_inventaris
                    $kodeInventaris = 'INV-' . $actualBookId . '-' . time();
                    $newItem = item_buku::create([
                        'id_buku' => $actualBookId,
                        'kode_inventaris' => $kodeInventaris,
                        'tgl_pengadaan' => now()->format('Y-m-d'),
                        'status' => 'dipinjam',
                    ]);
                    $p->itemBuku()->attach($newItem->id_item_buku);
                } else {
                    // Items exist but all are borrowed - allow anyway (waitlist mode)
                    // Get the first item regardless of status
                    $item = item_buku::where('id_buku', $actualBookId)->first();
                    if ($item) {
                        $p->itemBuku()->attach($item->id_item_buku);
                        $item->update(['status' => 'dipinjam']);
                    } else {
                        $p->delete();
                        return response()->json(['message' => 'Cannot process this book'], 400);
                    }
                }
            } else {
                // Items available - use them
                foreach ($items as $ib) {
                    $p->itemBuku()->attach($ib->id_item_buku);
                    $ib->update(['status' => 'dipinjam']);
                }
            }

            return response()->json([
                'message' => 'Peminjaman berhasil dibuat',
                'data' => $p->load(['anggota', 'itemBuku'])
            ], 201);

        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function apiUpdate(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    public function apiDestroy($id)
    {
        return $this->destroy($id);
    }

}