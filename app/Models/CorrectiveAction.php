<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectiveAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_plan_cause_id',
        'corrective_action',
        'commitment_date',
        'responsible_id',
        'status',
    ];

    public function actionPlanCause()
    {
        return $this->belongsTo(ActionPlanCause::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class,'responsible_id');
    }

    public function activities()
    {
        return $this->hasMany(ActionPlanActivity::class);
    }

    public function sisegac()
    {
        return $this->hasOne(Sisegac::class,'corrective_action_id');
    }
}