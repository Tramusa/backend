<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dollys extends Model
{
    use HasFactory;

    protected $fillable = [ 'no_economic', 'no_seriously', 'ejes', 'brand', 'model', 'user', 'status' ];
}


