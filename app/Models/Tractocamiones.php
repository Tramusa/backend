<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tractocamiones extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'no_economic', 'brand', 'model', 'year', 'no_seriously', 'no_motor', 'color', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'engine_capacity', 'speeds', 'differential_pitch', 'tire', 'transmission', 'ecm', 'esn', 'cpl', 'user', 'status'
    ];
}
