<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required',
            'email'  => 'required|email|unique:customers,email',
            'phone'  => 'required|unique:customers,phone',
            'address'=> 'nullable',
        ]);

        // Hitung jumlah customer untuk buat nomor urut
        $count = Customer::count() + 1;
        $prefix = strtolower($validated['name']) . '/mr' . str_pad($count, 2, '0', STR_PAD_LEFT);

        // Tambah marking_code_prefix ke validated data
        $validated['marking_code_prefix'] = $prefix;

        return Customer::create($validated);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'name'   => 'required',
            'email'  => 'required|email|unique:customers,email,' . $id . ',id_customer',
            'phone'  => 'required|unique:customers,phone,' . $id . ',id_customer',
            'address'=> 'nullable',
        ]);

        // Optional: update marking jika nama berubah (boleh di-comment kalau mau fix)
        $number = $id; // pakai ID customer
        $validated['marking_code_prefix'] = strtolower($validated['name']) . '/MR' . str_pad($number, 2, '0', STR_PAD_LEFT);

        $customer->update($validated);
        return $customer;
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
