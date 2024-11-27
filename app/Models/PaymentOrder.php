<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier', 'orders', 'total', 'payment', 'payment_form', 'date', 'banck', 'reference', 'comprobante', 'status', 'elaborate', 'authorize'
    ];

    public function supplierInfo()
    {
        return $this->belongsTo(Suppliers::class, 'supplier');
    }

    // Convert orders string to array of IDs and use it to retrieve related PurchaseOrders
    public function purchaseOrders()
    {
        $orderIds = explode(',', $this->orders); // Convert the string into an array of IDs
        return PurchaseOrder::whereIn('id', $orderIds)->with(['billing'])->with(['requisition'])->get();
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