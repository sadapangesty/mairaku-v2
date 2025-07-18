<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;

class JenisController extends Controller
{
    public function index()
    {
        return view('jenis.index'); // jika pakai blade, atau hapus jika full SPA
    }

    // API - Get all
    public function getAll()
    {
        return Jenis::all();
    }

    // API - Store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        return Jenis::create($validated);
    }

    // API - Update
    public function update(Request $request, $id)
    {
        $jenis = Jenis::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $jenis->update($validated);
        return $jenis;
    }

    // API - Delete
    public function destroy($id)
    {
        $jenis = Jenis::findOrFail($id);
        $jenis->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
