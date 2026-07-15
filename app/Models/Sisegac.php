<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sisegac extends Model
{
    use HasFactory;

    protected $fillable = [

        'corrective_action_id',
        'activity_id',

        'type',

        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec',

        'progress',

        'close_date',

        'next_verification',

        'recurrent',

        'observations',
    ];

    protected $casts = [

        'close_date' => 'date',

        'next_verification' => 'date',

        'recurrent' => 'boolean',

    ];

    public function correctiveAction()
    {
        return $this->belongsTo(
            CorrectiveAction::class
        );
    }

    public function activity()
    {
        return $this->belongsTo(
            ActionPlanActivity::class,
            'activity_id'
        );
    }
}