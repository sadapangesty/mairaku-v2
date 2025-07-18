<?php

namespace App\Http\Controllers;

use App\Models\SupplierOnline;
use Illuminate\Http\Request;

class SupplierOnlineController extends Controller
{
    public function index()
    {
        return SupplierOnline::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required',
            'negara' => 'required',
            'situs'  => 'required',
        ]);

        return SupplierOnline::create($validated);
    }

    public function update(Request $request, $id)
    {
        $supplier = SupplierOnline::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required',
            'negara' => 'required',
            'situs'  => 'required',
        ]);

        $supplier->update($validated);
        return $supplier;
    }

    public function destroy($id)
    {
        $supplier = SupplierOnline::findOrFail($id);
        $supplier->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
