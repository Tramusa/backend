<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autobuses extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_economic', 'brand', 'model', 'no_seriously', 'ejes', 'no_placas', 'expiration_placas', 'circulation_card', 'expiration_circulation', 'user', 'status'
    ];
}
