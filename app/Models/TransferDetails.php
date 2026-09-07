<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferDetails extends Model
{
    use HasFactory;

    protected $table = 'transfer_details';

    protected $fillable = [ 'id_transfer', 'id_product', 'quantity', ];

    protected $casts = [ 'quantity' => 'decimal:2', ];

    /* ------------------------------------------------------------------------
    | Traspaso
    |------------------------------------------------------------------------ */
    public function transfer()
    {
        return $this->belongsTo(InventoryTransfer::class, 'id_transfer');
    }

    /*------------------------------------------------------------------------
    | Producto
    |------------------------------------------------------------------------*/
    public function product()
    {
        return $this->belongsTo(ProductsServices::class, 'id_product');
    }
}