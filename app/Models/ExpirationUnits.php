<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpirationUnits extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'type_unit', 'unit', 'type_expiration', 'description', 'date_expiration', 'date_attended', 'user',  'status'];
}
