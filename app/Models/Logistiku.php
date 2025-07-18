<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistiku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_logistiku';
    protected $table = 'logistikus';

    protected $fillable = [
        'kode',
        'nama_logistiku',
        'id_logistic',
        'jenis',
        'harga_vendor',
        'harga_jual',
        'tanggal_aktif',
        'tanggal_nonaktif',
    ];

    public function logistic()
    {
        return $this->belongsTo(Logistic::class, 'id_logistic', 'id_logistic');
    }
}
