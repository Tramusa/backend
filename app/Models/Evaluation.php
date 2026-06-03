<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'non_conformity_id',
        'evaluation_type',
        'severity',
        'detectability',
        'occurrence',
        'npr',
        'result',
        'requires_analysis',
        'evaluated_by'
    ];

    /* ================= NON CONFORMITY ================= */
    public function nonConformity()
    {
        return $this->belongsTo(
            NonConformity::class
        );
    }

    /* ================= EVALUATOR ================= */
    public function evaluator()
    {
        return $this->belongsTo(
            User::class,
            'evaluated_by'
        );
    }
}