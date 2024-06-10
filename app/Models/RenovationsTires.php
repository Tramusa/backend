<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenovationsTires extends Model
{
    use HasFactory;

    protected $fillable = [
        'tire', 'supplier', 'floor_type', 'date'
    ];
}
