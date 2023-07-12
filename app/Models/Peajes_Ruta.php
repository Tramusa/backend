<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peajes_Ruta extends Model
{
    use HasFactory;

    protected $fillable = [  'ruta_id', 'caseta_id' ];
}
