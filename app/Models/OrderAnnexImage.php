<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAnnexImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_annex_id',
        'image',
        'orden',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON ANEXO
    |--------------------------------------------------------------------------
    */

    public function annex()
    {
        return $this->belongsTo(OrderAnnex::class, 'order_annex_id');
    }
}