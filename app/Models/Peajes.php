<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peajes extends Model
{
    use HasFactory;

    protected $fillable = [  'caseta', 'name', 'address', 'import2', 'import3', 'import4', 'import5', 'import6', 'import9' ];
}
