<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    use HasFactory;      

    protected $fillable = [
        'type_person', 'business_name', 'tradename', 'RFC', 'country', 'state', 'municipality', 'location', 'street', 'no_int', 'no_ext', 'cologne', 'postal_code', 'phone', 'e_mail', 'discount', 'credit_sale', 'credit_days', 'credit_limit',
    ];
}
