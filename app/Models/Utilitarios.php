<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilitarios extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_economic', 'brand', 'model', 'ejes', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'state_tenure', 'expiration_tenure', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'front', 'rear', 'left', 'right', 'user', 'status'
    ];
}
