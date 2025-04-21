<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryDetails extends Model
{
    use HasFactory;
    protected $fillable = ['id_entry', 'id_product', 'quality'];
    
    public function inventoryEntry()
    {
        return $this->belongsTo(InventoryEntries::class, 'id_entry');
    }
    
    public function product()
    {
        return $this->belongsTo(ProductsServices::class, 'id_product');
    }
}
