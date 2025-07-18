<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengembalikan semua data bank sebagai JSON
        return response()->json(Bank::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validator = Validator::make($request->all(), [
            'nama_bank'   => 'required|string|max:255',
            'atas_nama'   => 'required|string|max:255',
            'no_rekening' => 'required|string|max:100|unique:banks',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat bank baru
        $bank = Bank::create([
            'nama_bank'   => $request->nama_bank,
            'atas_nama'   => $request->atas_nama,
            'no_rekening' => $request->no_rekening,
        ]);

        return response()->json($bank, 201); // 201 Created
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_bank)
    {
        $bank = Bank::find($id_bank);

        if (!$bank) {
            return response()->json(['message' => 'Bank not found'], 404);
        }

        // Validasi data yang masuk
        $validator = Validator::make($request->all(), [
            'nama_bank'   => 'required|string|max:255',
            'atas_nama'   => 'required|string|max:255',
            'no_rekening' => 'required|string|max:100|unique:banks,no_rekening,' . $id_bank . ',id_bank',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Perbarui data bank
        $bank->update([
            'nama_bank'   => $request->nama_bank,
            'atas_nama'   => $request->atas_nama,
            'no_rekening' => $request->no_rekening,
        ]);

        return response()->json($bank, 200); // 200 OK
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_bank)
    {
        $bank = Bank::find($id_bank);

        if (!$bank) {
            return response()->json(['message' => 'Bank not found'], 404);
        }

        $bank->delete();

        return response()->json(['message' => 'Bank deleted successfully'], 200); // 200 OK
    }
}
