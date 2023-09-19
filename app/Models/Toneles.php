<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toneles extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_economic', 'brand', 'model', 'ejes', 'no_seriously', 'no_placas', 'volume', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'insurance_policy', 'safe_expiration', 'policy_receipt', 'expiration_receipt', 'physical_mechanical', 'physical_expiration', 'user', 'status'
    ];
}
