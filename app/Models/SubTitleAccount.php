<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTitleAccount extends Model
{
    use HasFactory;
    
    protected $fillable = ['id', 'account', 'name',];
}
