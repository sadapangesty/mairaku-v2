<?php

namespace App\Http\Controllers;

use App\Models\Logistic;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    public function index()
    {
        return Logistic::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required',
            'type'    => 'required|in:Local,Internasional',
            'service' => 'required|in:Reguler,Trucking,Sameday,Instant,LCL,FCL',
            'phone'   => 'nullable',
            'website' => 'nullable',
            'address' => 'nullable',
        ]);

        return Logistic::create($validated);
    }

    public function update(Request $request, $id)
    {
        $logistic = Logistic::findOrFail($id);
        $validated = $request->validate([
            'name'    => 'required',
            'type'    => 'required|in:Local,Internasional',
            'service' => 'required|in:Reguler,Trucking,Sameday,Instant,LCL,FCL',
            'phone'   => 'nullable',
            'website' => 'nullable',
            'address' => 'nullable',
        ]);

        $logistic->update($validated);
        return $logistic;
    }

    public function destroy($id)
    {
        $logistic = Logistic::findOrFail($id);
        $logistic->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
