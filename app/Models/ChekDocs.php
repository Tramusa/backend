<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChekDocs extends Model
{
    use HasFactory;
    
    protected $fillable = ['trip', 'programming_doc', 'vale_doc', 'letter_doc', 'stamp_doc'];
    
}
