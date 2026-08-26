<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_entry',
        'id_product',
        'name',
        'category',
        'unit_measure',
        'description',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function inventoryEntry()
    {
        return $this->belongsTo(
            InventoryEntries::class,
            'id_entry'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            ProductsServices::class,
            'id_product'
        );
    }
}