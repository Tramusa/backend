<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramMttoGeneralSchedule extends Model
{
    use HasFactory;

    protected $table = 'programs_mtto_general_schedule';

    protected $fillable = [
        'program_id',
        'year',
        'week',
        'scheduled_date',
        'status',
        'completed_date'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
    ];

    public function program()
    {
        return $this->belongsTo(ProgramMttoGeneral::class, 'program_id');
    }

    public function concentrate()
    {
        return $this->hasOne(MaintenanceGeneralConcentrate::class, 'schedule_id');
    }
}