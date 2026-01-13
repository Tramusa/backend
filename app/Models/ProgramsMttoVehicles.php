<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramsMttoVehicles extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'unit', 'activity', 'active'
    ];

      // Relación con programación
    public function schedules()
    {
        return $this->hasMany(ProgramsMttoVehicleSchedule::class, 'program_mtto_vehicle_id', 'id');
    }

}
