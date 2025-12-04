<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\laporan;
use App\Models\petugas;
use Illuminate\Foundation\Validation\ValidatesRequests;

class LaporanController extends Controller
{
    use ValidatesRequests;

    public function index()
    {
        $laporans = laporan::with('pembuat')->latest('created_at')->paginate(10);
        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        $petugass = petugas::all();
        return view('laporan.create', compact('petugass'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'periode' => 'required|string',
            'total_peminjaman' => 'required|integer',
            'total_buku_dipinjam' => 'required|integer',
            'total_denda' => 'required|numeric',
            'dibuat_oleh' => 'required|exists:petugas,id_petugas',
        ]);

        laporan::create([
            'periode' => $request->periode,
            'total_peminjaman' => $request->total_peminjaman,
            'total_buku_dipinjam' => $request->total_buku_dipinjam,
            'total_denda' => $request->total_denda,
            'dibuat_oleh' => $request->dibuat_oleh,
            'created_at' => $request->created_at ?? now(),
        ]);

        return redirect()->route('laporan.index')->with(['success' => 'Laporan tersimpan']);
    }

    public function edit($id)
    {
        $l = laporan::findOrFail($id);
        $petugass = petugas::all();
        return view('laporan.edit', compact('l','petugass'));
    }

    public function update(Request $request, $id)
    {
        $l = laporan::findOrFail($id);

        $this->validate($request, [
            'periode' => 'required|string',
            'total_peminjaman' => 'required|integer',
            'total_buku_dipinjam' => 'required|integer',
            'total_denda' => 'required|numeric',
        ]);

        $l->update($request->only(['periode','total_peminjaman','total_buku_dipinjam','total_denda','dibuat_oleh','created_at']));

        return redirect()->route('laporan.index')->with(['success' => 'Laporan diupdate']);
    }

    public function destroy($id)
    {
        $l = laporan::findOrFail($id);
        $l->delete();

        return redirect()->route('laporan.index')->with(['success' => 'Laporan dihapus']);
    }
}