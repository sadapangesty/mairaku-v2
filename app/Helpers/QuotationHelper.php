<?php

namespace App\Helpers;

use App\Models\QuotationSetting;
use App\Models\CurrencyRate;
use App\Models\Logistiku;

class QuotationHelper
{
    public static function calculate($jenis_pengiriman, $total_barang_rmb, $dimensi = null, $berat = null)
    {
        // Ambil settings
        $setting = QuotationSetting::first();
        $kursSell = CurrencyRate::where('rate_type', 'sell')->first()?->rate ?? 0;

        // Ambil logistikus rate
        $cbmRate = Logistiku::where('jenis', 'laut')->whereNull('tanggal_nonaktif')->first()?->harga_jual ?? 0;
        $kgRate  = Logistiku::where('jenis', 'udara')->whereNull('tanggal_nonaktif')->first()?->harga_jual ?? 0;

        $asuransiPercent = $setting->asuransi_percent / 100;
        $marginPercent   = $setting->margin_percent / 100;
        $biayaPengirimanLautPercent = $setting->biaya_pengiriman_laut_percent / 100;

        $harga_satuan = $total_barang_rmb * $kursSell;
        $biaya_asuransi = $harga_satuan * $asuransiPercent;
        $harga_sementara = $harga_satuan + $biaya_asuransi;
        $biaya_margin = $harga_sementara * $marginPercent;
        $harga_margin = $harga_sementara + $biaya_margin;

        $totalIDR = 0;

        if ($jenis_pengiriman === 'barang_kecil_laut') {
            $biaya_pengiriman = $harga_margin * $biayaPengirimanLautPercent;
            $totalIDR = $harga_margin + $biaya_pengiriman;
        }
        elseif ($jenis_pengiriman === 'barang_kecil_udara') {
            $biaya_pengiriman = ($berat ?? 0) * $kgRate;
            $totalIDR = $harga_margin + $biaya_pengiriman;
        }
        elseif ($jenis_pengiriman === 'barang_besar_laut') {
            $cbm = 0;
            if ($dimensi && isset($dimensi['p'], $dimensi['l'], $dimensi['t'])) {
                $cbm = ($dimensi['p'] * $dimensi['l'] * $dimensi['t']) / 1000000; // cm³ ke m³
            }
            $biaya_kubikasi = $cbm * $cbmRate;
            $totalIDR = $harga_margin + $biaya_kubikasi;
        }

        return [
            'total' => round($totalIDR),
            'kurs_sell' => $kursSell,
            'cbm_rate' => $cbmRate,
            'kg_rate' => $kgRate,
        ];
    }
}
