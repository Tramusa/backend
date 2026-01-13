<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramsMttoVehicleSchedule extends Model
{
    use HasFactory;

    protected $table = 'programs_mtto_vehicle_schedule';

    protected $fillable = [
        'program_mtto_vehicle_id', 'year', 'week', 'status', 'executed_at', 'rescheduled_to_week', 'observation',
    ];

    public function program()
    {
        return $this->belongsTo(ProgramsMttoVehicles::class, 'program_mtto_vehicle_id', 'id');
    }
}
