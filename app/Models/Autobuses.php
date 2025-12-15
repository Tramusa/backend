<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autobuses extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_economic', 'brand', 'model', 'no_seriously', 'ejes', 'no_placas', 'no_passengers', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'pollutant_emission', 'contaminant_expiration', 'odometro', 'customer', 'logistic', 'front', 'rear', 'left', 'right', 'user', 'status'
    ];
}
