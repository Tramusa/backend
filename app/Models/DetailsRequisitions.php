<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailsRequisitions extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user', 'id_requisition', 'id_product', 'name', 'price', 'isr', 'iva', 'ret_iva', 'ret_ish', 'unit_measure', 'cantidad', 'justific'
    ];
    
}
