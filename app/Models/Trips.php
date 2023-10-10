<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    use HasFactory; 
              
    protected $fillable = [ 'ceco', 'customer', 'name', 'mail', 'application_medium',  'phone', 'position', 'date', 'hour', 'origin', 'p_intermediate', 'p_authorized', 'destination' , 'type', 'product', 'detaills' , 'operator' , 'user', 'status', 'reason', 'end_date', 'end_hour'];
}
