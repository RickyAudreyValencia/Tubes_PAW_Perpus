<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\buku;
use App\Models\kategori;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

class BukuController extends Controller
{
    use ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Fetch books with their categories, ordered by latest, and paginate the result (10 items per page).
        $buku = buku::with('kategori')->latest()->paginate(10);

        // Return the paginated data as a JSON response.
        return response()->json($buku);
    }

    /**
     * Show the form for creating a new resource (usually not needed for pure API).
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        // For an API context, instead of returning a view, we return data needed for the form.
        $kategoris = kategori::all();
        return response()->json([
            'message' => 'Data untuk form pembuatan buku',
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'judul' => 'required|string',
            ]);

            $buku = buku::create($request->only([
                'id_kategori', 'judul', 'penulis', 'penerbit', 'tahun_terbit', 'isbn', 'deskripsi', 'gambar_sampul', 'stok'
            ]));

            // Return success response with the created resource and status code 201 (Created)
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'data' => $buku
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            // Return general error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan Saat Menyimpan Data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource (usually not needed for pure API).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id): JsonResponse
    {
        // For an API context, we return the specific book data and categories for editing.
        try {
            $b = buku::findOrFail($id);
            $kategoris = kategori::all();
            return response()->json([
                'message' => 'Data buku dan kategori untuk form edit',
                'buku' => $b,
                'kategoris' => $kategoris
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data buku tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $b = buku::findOrFail($id);

            $this->validate($request, [
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'judul' => 'required|string',
            ]);

            $b->update($request->only([
                'id_kategori', 'judul','stok', 'penulis', 'penerbit', 'tahun_terbit', 'isbn', 'deskripsi', 'gambar_sampul'
            ]));

            // Return success response with the updated resource
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Diubah!',
                'data' => $b
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data buku tidak ditemukan.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            // Return general error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan Saat Mengubah Data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $b = buku::findOrFail($id);
            $b->delete();

            // Return success response with status code 204 (No Content) or 200 (OK)
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus!'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data buku tidak ditemukan.'
            ], 404);
        } catch (Exception $e) {
            // Return general error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan Saat Menghapus Data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
}