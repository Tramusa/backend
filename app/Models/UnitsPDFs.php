<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitsPDFs extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'unit_id', 'type_unit', 'location'];
}
