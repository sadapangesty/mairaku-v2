<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\QuotationHelper;

class QuotationCalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'jenis_pengiriman' => 'required|string|in:barang_kecil_laut,barang_besar_laut,barang_kecil_udara',
            'total_barang_rmb' => 'required|numeric|min:0',
            'panjang' => 'nullable|numeric|min:0',
            'lebar'   => 'nullable|numeric|min:0',
            'tinggi'  => 'nullable|numeric|min:0',
            'berat'   => 'nullable|numeric|min:0',
        ]);

        $dimensi = [
            'p' => $request->panjang,
            'l' => $request->lebar,
            't' => $request->tinggi,
        ];

        $result = QuotationHelper::calculate(
            $request->jenis_pengiriman,
            $request->total_barang_rmb,
            $dimensi,
            $request->berat
        );

        return response()->json($result);
    }
}
