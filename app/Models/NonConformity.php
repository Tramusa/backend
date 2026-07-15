<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonConformity extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'date',
        'date_commitment',
        'problem',
        'detected',
        'affects',
        'responsible',
        'area',
        'type',
        'status'
    ];

    /* ================= RESPONSIBLE ================= */
    public function responsibleUser()
    {
        return $this->belongsTo(User::class, 'responsible' );
    }

    /* ================= EVALUATION ================= */
    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }

    public function actionPlanCauses()
    {
        return $this->hasMany(ActionPlanCause::class);
    }

    public function relation()
    {
        return $this->hasOne(Relation::class);
    }

    public function ishikawa()
    {
        return $this->hasOne(Ishikawa::class);
    }
}