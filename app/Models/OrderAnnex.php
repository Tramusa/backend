<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAnnex extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON ORDEN
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON IMÁGENES
    |--------------------------------------------------------------------------
    */

    public function images()
    {
        return $this->hasMany(OrderAnnexImage::class, 'order_annex_id')
            ->orderBy('orden');
    }
}