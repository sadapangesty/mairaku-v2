<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_currency_rate';
    public $timestamps = false;

    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'rate_category',
        'rate_type',
        'rate',
        'effective_date',
    ];

    public function fromCurrency() {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency() {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }
}
