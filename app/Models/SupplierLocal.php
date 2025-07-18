<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLocal extends Model
{
    use HasFactory;

    protected $table = 'suppliers_local';
    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'negara',
        'address',
    ];
}
