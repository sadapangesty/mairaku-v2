<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Currency;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return Warehouse::with('currency')->get();
    }

    public function dropdownData()
    {
        return response()->json([
            'currencies' => Currency::all(['id_currency', 'kode', 'nama']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:warehouses,code',
            'name' => 'required',
            'country' => 'required',
            'city' => 'nullable',
            'phone' => 'nullable|string',
            'address' => 'nullable',
            'currency_id' => 'required|exists:currencies,id_currency',
            'note' => 'nullable',
            'is_active' => 'boolean',
        ]);

        return Warehouse::create($validated);
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|unique:warehouses,code,' . $id,
            'name' => 'required',
            'country' => 'required',
            'city' => 'nullable',
            'phone' => 'nullable|string',
            'address' => 'nullable',
            'currency_id' => 'required|exists:currencies,id_currency',
            'note' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $warehouse->update($validated);

        return $warehouse;
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
