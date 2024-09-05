<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'category', 'name', 'price', 'isr', 'iva', 'ret_iva', 'ret_ish', 'unit_measure', 'inventory', 'stock', 'min', 'max'
    ];
}
