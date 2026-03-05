<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['id_order', 'id_earring'];

     /*
    |--------------------------------------------------------------------------
    | RELACIÓN → ORDEN
    |--------------------------------------------------------------------------
    */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'id_order');
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN → EARRING (UNIDAD)
    |--------------------------------------------------------------------------
    */
    public function earring()
    {
        return $this->belongsTo(Earrings::class, 'id_earring');
    }
}
