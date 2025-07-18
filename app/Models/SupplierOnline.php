<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierOnline extends Model
{
    use HasFactory;

    protected $table = 'suppliers_online';
    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'name',
        'negara',
        'situs',
    ];
}
