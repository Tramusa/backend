<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissingDocs extends Model
{
    use HasFactory;
    
    protected $fillable = ['type', 'unit', 'description', 'date', 'status', 'inspection', 'date_attended', 'attended' ];

}