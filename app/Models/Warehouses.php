<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'id_area'];
    
    public function workArea()
    {
        return $this->belongsTo(WorkAreas::class, 'id_area');
    }
}
