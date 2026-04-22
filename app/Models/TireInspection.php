<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TireInspection extends Model
{
    protected $fillable = [
        'unit_id',
        'type',
        'status',
        'inspection_date',
        'user_id'
    ];

    // 🔗 Relación: una inspección tiene muchas llantas
    public function details()
    {
        return $this->hasMany(TireInspectionDetail::class, 'inspection_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
