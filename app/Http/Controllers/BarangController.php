<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Tampilkan daftar barang (dengan relasi)
     */
    public function index(Request $request)
    {
        $perPage = $request->input('all');

        $barangs = Barang::with(['kategori', 'jenis'])
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $barangs
        ]);
    }

    /**
     * Simpan barang baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'  => 'required|string|max:255',
            'id_kategori'  => 'required|exists:kategoris,id_kategori',
            'id_jenis'     => 'required|exists:jenis,id_jenis',
            'harga'        => 'required|numeric',
            'link_barang'  => 'nullable|string',
            'foto'         => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Generate SKU berdasarkan SKU terakhir
        $lastBarang = Barang::orderBy('id', 'desc')->first();
        $tahun = date('Y');
        $nextNumber = 1;

        if ($lastBarang && preg_match('/(\d+)$/', $lastBarang->sku, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }

        $validated['sku'] = 'MR/' . $tahun . '/' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        // Proses upload file foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $validated['foto'] = $filename;
        } else {
            $validated['foto'] = 'default-product.png';
        }

        $barang = Barang::create($validated);
        $barang->load(['kategori', 'jenis']);

        return response()->json([
            'success' => true,
            'message' => 'Barang created successfully',
            'data'    => $barang
        ]);
    }

    /**
     * Update barang
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang'  => 'required|string|max:255',
            'id_kategori'  => 'required|exists:kategoris,id_kategori',
            'id_jenis'     => 'required|exists:jenis,id_jenis',
            'harga'        => 'required|numeric',
            'link_barang'  => 'nullable|string',
            'foto'         => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Proses upload file foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('assets/images'), $filename);
            $validated['foto'] = $filename;
        }

        $barang->update($validated);
        $barang->load(['kategori', 'jenis']);

        return response()->json([
            'success' => true,
            'message' => 'Barang updated successfully',
            'data'    => $barang
        ]);
    }

    /**
     * Hapus barang
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang deleted successfully'
        ]);
    }
}
