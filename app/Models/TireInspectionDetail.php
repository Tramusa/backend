<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TireInspectionDetail extends Model
{
    protected $fillable = [
        'inspection_id',
        'tire_id',
        'psi',

        'internal_1',
        'internal_2',
        'internal_3',

        'center_1',
        'center_2',
        'center_3',

        'external_1',
        'external_2',
        'external_3',

        'average',
        'observations'
    ];

    // 🔗 Relación inversa
    public function inspection()
    {
        return $this->belongsTo(TireInspection::class, 'inspection_id');
    }
    public function tire()
    {
        return $this->belongsTo(TiresControl::class, 'tire_id');
    }
}