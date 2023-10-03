<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tractocamiones extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'no_economic', 'brand', 'ejes', 'model', 'year', 'no_seriously', 'no_motor', 'color', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'cre', 'expiration_cre', 'front', 'rear', 'left', 'right', 'engine_capacity', 'speeds', 'differential_pitch', 'tire', 'extent_tire', 'transmission', 'ecm', 'esn', 'cpl', 'user', 'carrier', 'status'
    ];
}
