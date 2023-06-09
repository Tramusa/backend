<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspections extends Model
{
    use HasFactory;

    protected $fillable = ['responsible', 'type', 'unit', 'status', 'is', 'end_date' ];
}
