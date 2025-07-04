<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Explicitly define table name
    protected $table = 'customer';

    // Define which attributes are mass assignable
    protected $fillable = ['name', 'email', 'phone', 'address', 'image'];
}
