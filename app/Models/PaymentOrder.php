<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier', 'orders', 'total', 'payment', 'payment_form', 'date', 'banck', 'reference', 'comprobante'
    ];

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'supplier');
    }
}
