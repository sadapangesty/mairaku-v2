<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function index() {
        return response()->json(Currency::all());
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'kode'   => 'required|string|max:10|unique:currencies',
            'nama'   => 'required|string|max:255',
            'simbol' => 'required|string|max:10',
            
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $currency = Currency::create($request->all());
        return response()->json($currency, 201);
    }

    public function update(Request $request, $id) {
        $currency = Currency::find($id);
        if (!$currency) return response()->json(['message' => 'Not found'], 404);

        $validator = Validator::make($request->all(), [
            'kode'   => 'required|string|max:10|unique:currencies,kode,'.$id.',id_currency',
            'nama'   => 'required|string|max:255',
            'simbol' => 'required|string|max:10',
            
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $currency->update($request->all());
        return response()->json($currency);
    }

    public function destroy($id) {
        $currency = Currency::find($id);
        if (!$currency) return response()->json(['message' => 'Not found'], 404);

        $currency->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

