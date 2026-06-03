<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPlanCause extends Model
{
    use HasFactory;

    protected $fillable = [
        'non_conformity_id',
        'ishikawa_cause_id',
        'main_cause',
        'commitment_date',
        'responsible_id',
    ];

    public function nonConformity()
    {
        return $this->belongsTo(NonConformity::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function correctiveActions()
    {
        return $this->hasMany(CorrectiveAction::class);
    }

    /*
     * Se activa después cuando exista IshikawaCause.
     */
    // public function ishikawaCause()
    // {
    //     return $this->belongsTo(IshikawaCause::class);
    // }
}