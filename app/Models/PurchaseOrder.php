<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_requisition', 'date_order', 'id_supplier', 'perform', 'status', 'authorize', 'additional', 'total', 'cancel_reason', 'color'
    ];

    public function requisition()
    {
        return $this->belongsTo(Requisitions::class, 'id_requisition');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'id_supplier');
    }

    /**
     * Relación personalizada para obtener la factura asociada.
     */
    public function billing()
    {
        return BillingData::whereRaw("FIND_IN_SET(?, id_order)", [$this->id])->first() ?: null;
    }

    public function performInfo()
    {
        return $this->belongsTo(User::class, 'perform');
    }

    public function authorizeInfo()
    {
        return $this->belongsTo(User::class, 'authorize');
    }
}
