<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionsTires extends Model
{
    use HasFactory;

    protected $fillable = ['tire', 'date', 'odometro', 'psi', 'internal_1', 'center_1', 'external_1', 'internal_2', 'center_2', 'external_2', 'internal_3', 'center_3', 'external_3', 'status' ];
}
