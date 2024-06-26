<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tires extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id', 'serie', 'brand', 'depth', 'extent', 'model', 'layers', 'number_dot', 'lrh_lrg', 'simple_maximum', 'double_maximum', 'suitable_renewal',
    ];

    public function ctrlTires()
    {
        return $this->hasMany(CtrlTires::class, 'tire', 'id');
    }
}
