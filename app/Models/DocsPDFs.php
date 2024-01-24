<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsPDFs extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'location', 'dto'];
}
