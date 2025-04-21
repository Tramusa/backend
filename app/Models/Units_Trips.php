<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Units_Trips extends Model
{
    use HasFactory;

    protected $fillable = [ 'trip', 'type_unit', 'unit' ];

    // Relación con viaje
    public function trip()
    {
        return $this->belongsTo(Trips::class, 'trip');
    }
}
