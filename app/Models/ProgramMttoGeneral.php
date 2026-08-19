<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramMttoGeneral extends Model
{
    use HasFactory;

    protected $table = 'programs_mtto_general';

    protected $fillable = [
        'activity',
        'area',
        'building',
        'category',
        'status',
        'observations'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(ProgramMttoGeneralSchedule::class, 'program_id');
    }

     public function weeks()
    {
        return $this->hasMany(ProgramMttoGeneralSchedule::class, 'program_id', 'id');
    }

    public function concentrates()
    {
        return $this->hasMany(MaintenanceGeneralConcentrate::class, 'program_id');
    }
}