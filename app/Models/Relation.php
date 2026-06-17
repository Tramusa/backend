<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    protected $fillable = [

        'non_conformity_id',

        'matrix',

    ];

    protected $casts = [

        'matrix' => 'array',

    ];

    public function nonConformity()
    {
        return $this->belongsTo(
            NonConformity::class
        );
    }
}