<?php

namespace App\Http\Controllers;

use App\Models\QuotationSetting;
use Illuminate\Http\Request;

class QuotationSettingController extends Controller
{

    public function index()
    {
        return response()->json(QuotationSetting::all());

    }


    public function store(Request $request)
    {
        $request->validate([
            'asuransi_percent' => 'required|numeric|min:0',
            'margin_percent' => 'required|numeric|min:0',
            'biaya_pengiriman_laut_percent' => 'required|numeric|min:0',
        ]);

        $setting = QuotationSetting::create($request->only(
            'asuransi_percent',
            'margin_percent',
            'biaya_pengiriman_laut_percent'
        ));

        return response()->json($setting, 201);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'asuransi_percent' => 'required|numeric|min:0',
            'margin_percent' => 'required|numeric|min:0',
            'biaya_pengiriman_laut_percent' => 'required|numeric|min:0',
        ]);

        $setting = QuotationSetting::findOrFail($id);

        $setting->update($request->only(
            'asuransi_percent',
            'margin_percent',
            'biaya_pengiriman_laut_percent'
        ));

        return response()->json($setting);
    }

    public function destroy($id)
    {
        $setting = QuotationSetting::findOrFail($id);
        $setting->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
