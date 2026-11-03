<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'category', 'name', 'price', 'isr', 'iva', 'ret_iva', 'ret_ish', 'unit_measure', 'inventory', 'stock', 'min', 'max'
    ];

    /**
     * Calcular el costo neto del producto o servicio.
     */
    public function getNetCostAttribute()
    {
        $price = (float) ($this->price ?? 0);
        $iva = (float) ($this->iva ?? 0);
        $isr = (float) ($this->isr ?? 0);
        $retIva = (float) ($this->ret_iva ?? 0);
        $retIsh = (float) ($this->ret_ish ?? 0);

        return round(
            $price
            + ($price * $iva / 100)
            - ($price * $isr / 100)
            - ($price * $retIva / 100)
            + ($price * $retIsh / 100),
            2
        );
    }

    public function categoryProduct()
    {
        return $this->belongsTo(CategoryProducts::class, 'category');
    }
}
