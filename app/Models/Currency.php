<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_currency';

    // Jika tabel tidak punya kolom created_at & updated_at:
    public $timestamps = false;

    protected $fillable = ['kode', 'nama', 'simbol'];
}
