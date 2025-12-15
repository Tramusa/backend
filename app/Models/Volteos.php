<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volteos extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_economic', 'brand', 'ejes', 'model', 'no_seriously', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'volume', 'logistic', 'user', 'status'
    ];
}
