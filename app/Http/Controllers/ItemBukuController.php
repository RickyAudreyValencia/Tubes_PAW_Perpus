<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\item_buku;
use App\Models\buku;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ItemBukuController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        $items = item_buku::with('buku')->latest()->paginate(10);
        return view('item_buku.index', compact('items'));
    }

    public function create()
    {
        $bukus = buku::all();
        return view('item_buku.create', compact('bukus'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_buku' => 'required|exists:buku,id_buku',
            'kode_inventaris' => 'required|string',
            'tgl_pengadaan' => 'required|date',
        ]);

        item_buku::create([
            'id_buku' => $request->id_buku,
            'kode_inventaris' => $request->kode_inventaris,
            'status' => $request->status ?? 'tersedia',
            'tgl_pengadaan' => $request->tgl_pengadaan,
        ]);

        return redirect()->route('item_buku.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function edit($id)
    {
        $it = item_buku::findOrFail($id);
        $bukus = buku::all();
        return view('item_buku.edit', compact('it', 'bukus'));
    }

    public function update(Request $request, $id)
    {
        $it = item_buku::findOrFail($id);

        $this->validate($request, [
            'id_buku' => 'required|exists:buku,id_buku',
            'kode_inventaris' => 'required|string',
            'tgl_pengadaan' => 'required|date',
        ]);

        $it->update([
            'id_buku' => $request->id_buku,
            'kode_inventaris' => $request->kode_inventaris,
            'status' => $request->status,
            'tgl_pengadaan' => $request->tgl_pengadaan,
        ]);

        return redirect()->route('item_buku.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id)
    {
        $it = item_buku::findOrFail($id);
        $it->delete();

        return redirect()->route('item_buku.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}