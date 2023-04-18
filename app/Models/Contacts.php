<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'user_id', 'name_contact', 'a_paterno_contact', 'a_materno_contact', 'relationship_contact', 'cell_contact'
    ];
}
