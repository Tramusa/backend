<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ishikawa extends Model
{
    protected $fillable = [
        'non_conformity_id'
    ];

    public function nonConformity()
    {
        return $this->belongsTo(
            NonConformity::class
        );
    }

    public function causes()
    {
        return $this->hasMany(
            IshikawaCause::class
        );
    }
}