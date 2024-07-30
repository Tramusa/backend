<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierBanck extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_supplier', 'banck', 'account', 'clabe', 'moneda', 
    ];
}
