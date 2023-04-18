<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiarie extends Model
{
    use HasFactory;
                                    
    protected $fillable = [ 
        'user_id', 'name_beneficiary', 'a_paterno_beneficiary', 'a_materno_beneficiary', 'relationship', 'cell_beneficiary', 'percentage', 'birthdate_beneficiary', 'street_beneficiary', 'suburb_beneficiary', 'municipality_beneficiary', 'state_beneficiary' 
    ];
}
