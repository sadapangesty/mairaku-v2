<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationSetting extends Model
{
    use HasFactory;

    protected $table = 'quotation_settings';

    protected $fillable = [
        'asuransi_percent',
        'margin_percent',
        'biaya_pengiriman_laut_percent',
    ];
}
