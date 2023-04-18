<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remolques extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'no_economic', 'brand', 'model', 'year', 'no_seriously', 'color', 'capacity', 'unit', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'dry_measure', 'wet_measure', 'seal_inviolability', 'rear_bumper_size', 'authorized_capacity', 'user', 'status'
    ];

}
