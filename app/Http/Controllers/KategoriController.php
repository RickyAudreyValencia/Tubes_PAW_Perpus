<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\kategori;
use Illuminate\Foundation\Validation\ValidatesRequests;

class KategoriController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        $kategori = kategori::latest()->paginate(10);
        return view('kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string|max:100',
        ]);

        kategori::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function edit($id)
    {
        $k = kategori::findOrFail($id);
        return view('kategori.edit', compact('k'));
    }

    public function update(Request $request, $id)
    {
        $k = kategori::findOrFail($id);

        $this->validate($request, [
            'nama' => 'required|string|max:100',
        ]);

        $k->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    public function destroy($id)
    {
        $k = kategori::findOrFail($id);
        $k->delete();

        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
}