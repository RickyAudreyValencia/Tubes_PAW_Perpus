<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\denda;
use App\Models\peminjaman;
use App\Models\petugas;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DendaController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        $dendas = denda::with('peminjaman','petugas')->latest()->paginate(10);
        return view('denda.index', compact('dendas'));
    }

    public function create()
    {
        $peminjamans = peminjaman::all();
        $petugass = petugas::all();
        return view('denda.create', compact('peminjamans','petugass'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'id_petugas' => 'required|exists:petugas,id_petugas',
            'jumlah' => 'required|numeric',
            'status' => 'required|string',
        ]);

        denda::create($request->only(['id_peminjaman','id_petugas','jumlah','status','tgl_pembayaran','catatan']));

        return redirect()->route('denda.index')->with(['success' => 'Denda tersimpan']);
    }

    public function edit($id)
    {
        $d = denda::findOrFail($id);
        $peminjamans = peminjaman::all();
        $petugass = petugas::all();
        return view('denda.edit', compact('d','peminjamans','petugass'));
    }

    public function update(Request $request, $id)
    {
        $d = denda::findOrFail($id);

        $this->validate($request, [
            'jumlah' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $d->update($request->only(['id_peminjaman','id_petugas','jumlah','status','tgl_pembayaran','catatan']));

        return redirect()->route('denda.index')->with(['success' => 'Denda diupdate']);
    }

    public function destroy($id)
    {
        $d = denda::findOrFail($id);
        $d->delete();

        return redirect()->route('denda.index')->with(['success' => 'Denda dihapus']);
    }
}
