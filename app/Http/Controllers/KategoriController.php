<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        return Kategori::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori = Kategori::create($validated);
        return response()->json($kategori, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($validated);
        return response()->json($kategori);
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return response()->noContent();
    }
}
