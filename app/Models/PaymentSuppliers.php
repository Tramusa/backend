<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSuppliers extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'quality', 'billings', 'date', 'supplier', 'user'
    ];
}
