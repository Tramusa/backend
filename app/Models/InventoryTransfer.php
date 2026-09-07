<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransfer extends Model
{
    use HasFactory;

    protected $table = 'inventory_transfers';

    protected $fillable = [ 'inventory_origin_id', 'inventory_destination_id', 'user_id', 'receiver', 'date', 'observations',];

    protected $casts = [ 'date' => 'datetime', ];

    /* ---------------------------------------------------------------------
    | Usuario responsable
    |----------------------------------------------------------------------- */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*---------------------------------------------------------------------
    | Almacén origen
    |-----------------------------------------------------------------------*/
    public function origin()
    {
        return $this->belongsTo(Warehouses::class, 'inventory_origin_id');
    }

    /*-----------------------------------------------------------------------
    | Almacén destino
    |------------------------------------------------------------------------*/
    public function destination()
    {
        return $this->belongsTo(Warehouses::class, 'inventory_destination_id');
    }

    /*-----------------------------------------------------------------------
    | Detalles
    |-----------------------------------------------------------------------*/
    public function details()
    {
        return $this->hasMany(TransferDetails::class, 'id_transfer');
    }
}