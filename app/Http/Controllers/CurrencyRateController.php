<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index()
    {
        $rates = CurrencyRate::with(['fromCurrency', 'toCurrency'])->get();
        return response()->json($rates);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_currency_id' => 'required|exists:currencies,id_currency',
            'to_currency_id' => 'required|exists:currencies,id_currency',
            'rate_category' => 'required|in:internal,money_changer,markup',
            'rate_type' => 'required|in:selling,buy,middle',
            'rate' => 'required|numeric',
            'effective_date' => 'required|date',
        ]);

        $rate = CurrencyRate::create($validated);
        return response()->json($rate, 201);
    }

    public function update(Request $request, $id)
    {
        $rate = CurrencyRate::findOrFail($id);
        $validated = $request->validate([
            'from_currency_id' => 'required|exists:currencies,id_currency',
            'to_currency_id' => 'required|exists:currencies,id_currency',
            'rate_category' => 'required|in:internal,money_changer,markup',
            'rate_type' => 'required|in:selling,buy,middle',
            'rate' => 'required|numeric',
            'effective_date' => 'required|date',
        ]);

        $rate->update($validated);
        return response()->json($rate);
    }

    public function destroy($id)
    {
        $rate = CurrencyRate::findOrFail($id);
        $rate->delete();
        return response()->json(null, 204);
    }


    public function dropdownData()
    {
        $currencies = Currency::select('id_currency', 'kode')->get();
        $rate_types = ['selling', 'buy', 'middle'];
        $rate_categories = ['internal', 'money_changer', 'markup'];

        return response()->json([
            'currencies' => $currencies,
            'rate_types' => $rate_types,
            'rate_categories' => $rate_categories,
        ]);
    }
}
