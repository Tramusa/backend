<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairTires extends Model
{
    use HasFactory;

    protected $fillable = [
        'tire', 'odometro', 'damage_type', 'date'
    ];
}
