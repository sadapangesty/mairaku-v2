<?php

namespace App\Http\Controllers;

use App\Models\SupplierLocal;
use Illuminate\Http\Request;

class SupplierLocalController extends Controller
{
    public function index()
    {
        return SupplierLocal::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required',
            'email'   => 'required|email|unique:suppliers_local,email',
            'phone'   => 'required|unique:suppliers_local,phone',
            'negara'  => 'required',
            'address' => 'nullable',
        ]);
        return SupplierLocal::create($validated);
    }

    public function update(Request $request, $id)
    {
        $supplier = SupplierLocal::findOrFail($id);
        $validated = $request->validate([
            'name'    => 'required',
            'email'   => 'required|email|unique:suppliers_local,email,' . $id . ',id_supplier',
            'phone'   => 'required|unique:suppliers_local,phone,' . $id . ',id_supplier',
            'negara'  => 'required',
            'address' => 'nullable',
        ]);
        $supplier->update($validated);
        return $supplier;
    }

    public function destroy($id)
    {
        $supplier = SupplierLocal::findOrFail($id);
        $supplier->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
