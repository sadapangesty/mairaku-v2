<?php

namespace App\Http\Controllers;

use App\Models\Logistiku;
use Illuminate\Http\Request;

class LogistikuController extends Controller
{
    public function index()
    {
        // Load semua data dengan relasi logistic (vendor)
        $data = Logistiku::with('logistic')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_logistiku'   => 'required|string|max:255',
            'id_logistic'      => 'required|exists:logistics,id_logistic',
            'jenis'            => 'required|in:laut,udara',
            'harga_vendor'     => 'required|integer',
            'harga_jual'       => 'required|integer',
            'tanggal_aktif'    => 'required|date',
            'tanggal_nonaktif' => 'nullable|date|after_or_equal:tanggal_aktif',
        ]);

        // Buat kode otomatis, contoh: MR/LAUT/1
        $count = Logistiku::count() + 1;
        $kode  = 'MR/' . strtoupper($request->jenis) . '/' . $count;

        $logistikus = Logistiku::create([
            'kode'             => $kode,
            'nama_logistiku'   => $request->nama_logistiku,
            'id_logistic'      => $request->id_logistic,
            'jenis'            => $request->jenis,
            'harga_vendor'     => $request->harga_vendor,
            'harga_jual'       => $request->harga_jual,
            'tanggal_aktif'    => $request->tanggal_aktif,
            'tanggal_nonaktif' => $request->tanggal_nonaktif,
        ]);

        return response()->json($logistikus, 201);
    }

    public function update(Request $request, $id)
    {
        $logistikus = Logistiku::findOrFail($id);

        $request->validate([
            'nama_logistiku'   => 'required|string|max:255',
            'id_logistic'      => 'required|exists:logistics,id_logistic',
            'jenis'            => 'required|in:laut,udara',
            'harga_vendor'     => 'required|integer',
            'harga_jual'       => 'required|integer',
            'tanggal_aktif'    => 'required|date',
            'tanggal_nonaktif' => 'nullable|date|after_or_equal:tanggal_aktif',
        ]);

        $logistikus->update([
            'nama_logistiku'   => $request->nama_logistiku,
            'id_logistic'      => $request->id_logistic,
            'jenis'            => $request->jenis,
            'harga_vendor'     => $request->harga_vendor,
            'harga_jual'       => $request->harga_jual,
            'tanggal_aktif'    => $request->tanggal_aktif,
            'tanggal_nonaktif' => $request->tanggal_nonaktif,
        ]);

        return response()->json($logistikus, 200);
    }

    public function destroy($id)
    {
        $logistikus = Logistiku::findOrFail($id);
        $logistikus->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
