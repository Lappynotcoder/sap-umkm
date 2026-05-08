<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Daftar semua produk milik user.
     */
    public function index()
    {
        $products = Product::where('user_id', Auth::id())
            ->orderByRaw('is_active DESC, nama_produk ASC')
            ->get();

        $lowStockCount = $products->where('is_active', true)
            ->filter(fn($p) => $p->stok_saat_ini <= $p->stok_minimum)->count();

        return view('pages.produk.index', compact('products', 'lowStockCount'));
    }

    /**
     * Form tambah produk baru.
     */
    public function create()
    {
        return view('pages.produk.form', ['product' => null]);
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'kategori'     => 'nullable|string|max:100',
            'harga_jual'   => 'required|numeric|min:0',
            'harga_modal'  => 'required|numeric|min:0',
            'stok_saat_ini'=> 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'satuan'       => 'required|string|max:50',
        ]);

        $validated['user_id'] = Auth::id();

        Product::create($validated);

        return redirect()->route('produk.index')
            ->with('success', 'Produk "' . $validated['nama_produk'] . '" berhasil ditambahkan!');
    }

    /**
     * Form edit produk.
     */
    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        return view('pages.produk.form', compact('product'));
    }

    /**
     * Update data produk.
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'kategori'     => 'nullable|string|max:100',
            'harga_jual'   => 'required|numeric|min:0',
            'harga_modal'  => 'required|numeric|min:0',
            'stok_saat_ini'=> 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'satuan'       => 'required|string|max:50',
        ]);

        $product->update($validated);

        return redirect()->route('produk.index')
            ->with('success', 'Produk "' . $product->nama_produk . '" berhasil diperbarui!');
    }

    /**
     * Soft-delete: nonaktifkan produk.
     */
    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $nama = $product->nama_produk;
        $product->update(['is_active' => false]);

        return redirect()->route('produk.index')
            ->with('success', 'Produk "' . $nama . '" telah dinonaktifkan.');
    }

    /**
     * API: return JSON list produk aktif (untuk AJAX dropdown di form transaksi).
     */
    public function apiList()
    {
        $products = Product::where('user_id', Auth::id())
            ->active()
            ->orderBy('nama_produk')
            ->get(['id', 'nama_produk', 'kategori', 'harga_jual', 'harga_modal', 'stok_saat_ini', 'satuan']);

        return response()->json($products);
    }
}
