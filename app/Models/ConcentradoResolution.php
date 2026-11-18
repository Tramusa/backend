<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcentradoResolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'non_conformity_id',
        'security_investigation_id',
        'corrective_action_id',
        'activity_id',
        'folio',
        'area',
        'resolution',
        'category',
        'agreement_date',
        'support',
        'responsible_id',
        'planned_closure',
        'real_closure',
        'status',
        'observations',
    ];

    public function nonConformity()
    {
        return $this->belongsTo(NonConformity::class);
    }

    public function investigation()
    {
        return $this->belongsTo(SecurityInvestigation::class, 'security_investigation_id' );
    }

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }

    public function activity()
    {
        return $this->belongsTo(ActionPlanActivity::class, 'activity_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
