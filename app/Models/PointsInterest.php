<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsInterest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'street', 'suburb', 'city', 'state', 'cp', 'siic', 'no_season'];
}