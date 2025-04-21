<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputDetails extends Model
{
    use HasFactory;

    protected $fillable = ['id_output', 'id_product', 'quality'];
    
    public function inventoryOutput()
    {
        return $this->belongsTo(InventoryOutput::class, 'id_output');
    }
    
    public function product()
    {
        return $this->belongsTo(ProductsServices::class, 'id_product');
    }
}
