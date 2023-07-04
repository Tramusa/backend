<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsInterest extends Model
{
    use HasFactory;

    protected $fillable = ['street', 'suburb', 'city', 'state', 'cp'];
}