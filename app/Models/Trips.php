<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trips extends Model
{
    use HasFactory; 
              
    protected $fillable = [ 'ceco', 'customer', 'name', 'mail', 'phone', 'position', 'date', 'hour', 'origin', 'destination' , 'type', 'product', 'detaills' , 'operator' , 'user', 'status', 'end_date'];
}
