<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiresControl extends Model
{
    protected $table = 'tires_control';

    protected $fillable = [
        'dot',
        'serial_number',
        'brand',
        'model',
        'installed_at',
        'status',
        'position',
        'burn_number',
        'tread_depth',
        'assignment',
    ];
}