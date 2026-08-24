<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earrings extends Model
{
    use HasFactory;
    
    protected $fillable = ['type', 'unit', 'status', 'fm', 'description', 'priority', 'committed_date', 'type_mtto', 'schedule_id', 'reported_by',];

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}


