<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IshikawaCause extends Model
{
    protected $fillable = [

        'ishikawa_id',

        'category',

        'description'

    ];

    public function ishikawa()
    {
        return $this->belongsTo(
            Ishikawa::class
        );
    }
}