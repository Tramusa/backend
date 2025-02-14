<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingData extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id', 'folio', 'id_supplier', 'id_paymentOrder', 'id_order', 'date', 'payment_form', 'payment_method'
    ];

    public function orders()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'billing_orders', 'billing_id', 'order_id');
    }
}
