<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Detail_Peminjaman;
use App\Models\Peminjaman;
use App\Models\Item_Buku;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DetailPeminjamanController extends Controller
{
    use ValidatesRequests;


    public function index()
    {
        $details = Detail_Peminjaman::with(['peminjaman', 'itemBuku'])->paginate(20);
        return view('detail_peminjaman.index', compact('details'));
    }

    public function create()
    {
        $peminjamans = Peminjaman::all();
        $items = Item_Buku::where('status', 'tersedia')->get();
        return view('detail_peminjaman.create', compact('peminjamans', 'items'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'id_item_buku' => 'required|exists:item_buku,id_item_buku',
        ]);

        $exists = Detail_Peminjaman::where('id_peminjaman', $request->id_peminjaman)
            ->where('id_item_buku', $request->id_item_buku)
            ->exists();

        if ($exists) {
            return redirect()->back()->with(['error' => 'Item sudah terdaftar untuk peminjaman ini']);
        }

        Detail_Peminjaman::create([
            'id_peminjaman' => $request->id_peminjaman,
            'id_item_buku' => $request->id_item_buku,
        ]);

        $item = Item_Buku::find($request->id_item_buku);
        if ($item) $item->update(['status' => 'dipinjam']);

        return redirect()->route('detail_peminjaman.index')->with(['success' => 'Detail peminjaman berhasil ditambahkan']);
    }

    public function edit($id_peminjaman, $id_item_buku)
    {
        $detail = Detail_Peminjaman::where('id_peminjaman', $id_peminjaman)
            ->where('id_item_buku', $id_item_buku)
            ->firstOrFail();

        $peminjamans = Peminjaman::all();
        $items = Item_Buku::all();
        return view('detail_peminjaman.edit', compact('detail', 'peminjamans', 'items'));
    }

    public function update(Request $request, $id_peminjaman, $id_item_buku)
    {
        $this->validate($request, [
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'id_item_buku' => 'required|exists:item_buku,id_item_buku',
        ]);

        $detail = Detail_Peminjaman::where('id_peminjaman', $id_peminjaman)
            ->where('id_item_buku', $id_item_buku)
            ->firstOrFail();

        if ($request->id_item_buku != $id_item_buku) {
            $oldItem = Item_Buku::find($id_item_buku);
            if ($oldItem) $oldItem->update(['status' => 'tersedia']);

            $newItem = Item_Buku::find($request->id_item_buku);
            if ($newItem) $newItem->update(['status' => 'dipinjam']);
        }

        $detail->update([
            'id_peminjaman' => $request->id_peminjaman,
            'id_item_buku' => $request->id_item_buku,
        ]);

        return redirect()->route('detail_peminjaman.index')->with(['success' => 'Detail peminjaman berhasil diubah']);
    }

    public function destroy($id_peminjaman, $id_item_buku)
    {
        $detail = Detail_Peminjaman::where('id_peminjaman', $id_peminjaman)
            ->where('id_item_buku', $id_item_buku)
            ->first();

        if ($detail) {
            $item = Item_Buku::find($id_item_buku);
            if ($item) $item->update(['status' => 'tersedia']);
            $detail->delete();
        }

        return redirect()->route('detail_peminjaman.index')->with(['success' => 'Detail peminjaman berhasil dihapus']);
    }


    public function apiIndex()
    {
        $data = Detail_Peminjaman::with(['peminjaman', 'itemBuku'])->get();
        return response()->json(['data' => $data]);
    }

    public function apiShow($id_peminjaman, $id_item_buku)
    {
        $data = Detail_Peminjaman::where('id_peminjaman', $id_peminjaman)
            ->where('id_item_buku', $id_item_buku)
            ->with(['peminjaman', 'itemBuku'])
            ->firstOrFail();

        return response()->json(['data' => $data]);
    }

    public function apiStore(Request $request)
    {
        $this->validate($request, [
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'id_item_buku' => 'required|exists:item_buku,id_item_buku',
        ]);

        $exists = Detail_Peminjaman::where('id_peminjaman', $request->id_peminjaman)
            ->where('id_item_buku', $request->id_item_buku)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Item sudah terdaftar untuk peminjaman ini'], 422);
        }

        $record = Detail_Peminjaman::create([
            'id_peminjaman' => $request->id_peminjaman,
            'id_item_buku' => $request->id_item_buku,
        ]);

        $item = Item_Buku::find($request->id_item_buku);
        if ($item) $item->update(['status' => 'dipinjam']);

        return response()->json(['message' => 'Detail peminjaman dibuat', 'data' => $record], 201);
    }

    public function apiUpdate(Request $request, $id_peminjaman, $id_item_buku)
    {
        $this->validate($request, [
            'id_peminjaman' => 'required|exists:peminjaman,id_peminjaman',
            'id_item_buku' => 'required|exists:item_buku,id_item_buku',
        ]);

        $detail = detail_peminjaman::where('id_peminjaman', $id_peminjaman)
            ->where('id_item_buku', $id_item_buku)
            ->firstOrFail();

        if ($request->id_item_buku != $id_item_buku) {
            $oldItem = item_buku::find($id_item_buku);
            if ($oldItem) $oldItem->update(['status' => 'tersedia']);

            $newItem = item_buku::find($request->id_item_buku);
            if ($newItem) $newItem->update(['status' => 'dipinjam']);
        }

        $detail->update([
            'id_peminjaman' => $request->id_peminjaman,
            'id_item_buku' => $request->id_item_buku,
        ]);

        return response()->json(['message' => 'Detail peminjaman diupdate']);
    }

    public function apiDestroy($id_peminjaman, $id_item_buku)
    {
        $detail = Detail_Peminjaman::where('id_peminjaman', $id_peminjaman)
            ->where('id_item_buku', $id_item_buku)
            ->first();

        if ($detail) {
            $item = Item_Buku::find($id_item_buku);
            if ($item) $item->update(['status' => 'tersedia']);
            $detail->delete();
        }

        return response()->json(['message' => 'Detail peminjaman dihapus']);
    }
}
