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

    public function purchaseOrders()
    {
        $orderIds = explode(',', $this->orders); // Convertir el string en un array de IDs
        return PurchaseOrder::whereIn('id', $orderIds)->with(['requisition'])->get(); // Cargar solo 'requisition' por ahora
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