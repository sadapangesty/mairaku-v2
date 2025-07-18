<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'country',
        'city',
        'phone',
        'address',
        'currency_id',
        'note',
        'is_active',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id_currency');
    }
}
