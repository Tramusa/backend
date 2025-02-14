<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier', 'total', 'payment', 'payment_form', 'date', 'banck', 'reference', 'comprobante', 'status', 'elaborate', 'authorize'
    ];

    public function supplierInfo()
    {
        return $this->belongsTo(Suppliers::class, 'supplier');
    }

    public function authorizeInfo()
    {
        return $this->belongsTo(User::class, 'authorize');
    }

    public function elaborateInfo()
    {
        return $this->belongsTo(User::class, 'elaborate');
    }
}