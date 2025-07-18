<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistic extends Model
{
    use HasFactory;

    protected $table = 'logistics';
    protected $primaryKey = 'id_logistic';

    protected $fillable = [
        'name',
        'type',
        'service',
        'phone',
        'website',
        'address',
    ];
}
