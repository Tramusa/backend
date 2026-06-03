<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPlanActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'corrective_action_id',
        'activity',
        'commitment_date',
        'responsible_id',
        'completed',
    ];

    public function correctiveAction()
    {
        return $this->belongsTo(CorrectiveAction::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}