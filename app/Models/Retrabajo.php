<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retrabajo extends Model
{
    protected $fillable = [
        'type',
        'unit',
        'mes',
        'year',
        'cantidad'
    ];
}
