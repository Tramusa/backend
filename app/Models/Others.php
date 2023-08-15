<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Others extends Model
{
    use HasFactory;
                                                                                           
    protected $fillable = [ 
        'user_id', 'state_civil', 'regimen', 'sex', 'birthdate', 'place_birth', 'nationality', 'scholarship', 'title', 'ine', 'license', 'type_license', 'expiration_license', 'expiration_psychophysical', 'expiration_general', 'rfc', 'curp', 'socia_health', 'umf', 'weight', 'height', 'blood_type'   
    ];
}