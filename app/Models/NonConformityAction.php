<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonConformityAction extends Model
{
    protected $fillable = [
        'non_conformity_id',
        'action',
        'responsible_id',
        'date_commitment',
        'status'
    ];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function nonConformity()
    {
        return $this->belongsTo(NonConformity::class);
    }
}