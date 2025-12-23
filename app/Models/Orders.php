<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'date_attended', 'status', 'date_in', 'repair', 'requisitions', 'odometro', 'spare_parts', 'total_parts', 'total_mano', 'authorize', 'perform', 'operator'];
}
