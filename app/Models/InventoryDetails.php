<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryDetails extends Model
{
    use HasFactory;
    protected $fillable = ['id_inventory', 'id_product', 'quality'];
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'id_inventory');
    }
    
    public function product()
    {
        return $this->belongsTo(ProductsServices::class, 'id_product');
    }
}
