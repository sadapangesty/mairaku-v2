<?php

namespace App\Http\Controllers;

use App\Models\Karyawan; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Tambahkan ini untuk validasi

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengembalikan semua data karyawan sebagai JSON
        // Pastikan nama kolom 'tanggal_masuk' dari database.
        return response()->json(Karyawan::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:karyawans', // email harus unik
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'jabatan'       => 'required|string|max:255',
            'tanggalMasuk'  => 'required|date', // 'tanggalMasuk' dari frontend, akan dimapping ke 'tanggal_masuk'
            'salary'        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat karyawan baru
        $karyawan = Karyawan::create([
            'nama'          => $request->nama,
            'email'         => $request->email,
            'telepon'       => $request->telepon,
            'alamat'        => $request->alamat,
            'jabatan'       => $request->jabatan,
            'tanggal_masuk' => $request->tanggalMasuk, // mapping ke kolom database
            'salary'        => $request->salary,
        ]);

        return response()->json($karyawan, 201); // 201 Created
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_karyawan)
    {
        $karyawan = Karyawan::find($id_karyawan);

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        // Validasi data yang masuk
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:karyawans,email,' . $id_karyawan . ',id_karyawan', // email unik kecuali dirinya sendiri
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'jabatan'       => 'required|string|max:255',
            'tanggalMasuk'  => 'required|date', // 'tanggalMasuk' dari frontend
            'salary'        => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Perbarui data karyawan
        $karyawan->update([
            'nama'          => $request->nama,
            'email'         => $request->email,
            'telepon'       => $request->telepon,
            'alamat'        => $request->alamat,
            'jabatan'       => $request->jabatan,
            'tanggal_masuk' => $request->tanggalMasuk, // mapping ke kolom database
            'salary'        => $request->salary,
        ]);

        return response()->json($karyawan, 200); // 200 OK
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_karyawan)
    {
        $karyawan = Karyawan::find($id_karyawan);

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        $karyawan->delete();

        return response()->json(['message' => 'Karyawan deleted successfully'], 200); // 200 OK
    }
}
