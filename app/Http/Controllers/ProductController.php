<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. Tampilkan Semua Produk (Bisa diakses Admin & Mahasiswa)
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    // 2. Tambah Produk (HANYA ADMIN)
    public function store(Request $request)
    {
        // Pengecekan Hak Akses Admin
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak! Hanya admin yang dapat menambah produk.'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
        ], 201);
    }

    // 3. Update / Edit Produk (HANYA ADMIN)
    public function update(Request $request, $id)
    {
        // Pengecekan Hak Akses Admin
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak! Hanya admin yang dapat mengubah produk.'
            ], 403);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
        ]);

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => $product
        ], 200);
    }

    // 4. Hapus Produk (HANYA ADMIN)
    public function destroy(Request $request, $id)
    {
        // Pengecekan Hak Akses Admin
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak! Hanya admin yang dapat menghapus produk.'
            ], 403);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ], 200);
    }
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
    }
}