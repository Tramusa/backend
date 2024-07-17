<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'category', 'name', 'price', 'iva', 'unit_measure', 'inventory', 'stock', 'min', 'max'
    ];
}
