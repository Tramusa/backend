<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceGeneralConcentrate extends Model
{
    use HasFactory;

    protected $table = 'maintenance_general_concentrates';

    protected $fillable = [
        'program_id',
        'schedule_id',
        'year',
        'week',
        'status',
        'observations',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(
            ProgramMttoGeneral::class,
            'program_id'
        );
    }

    public function schedule()
    {
        return $this->belongsTo(
            ProgramMttoGeneralSchedule::class,
            'schedule_id'
        );
    }
}