<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitingHour extends Model
{
    protected $table = 'waiting_hours';

    protected $fillable = [
        'unit_id',
        'type',
        'order_id',
        'hours',
        'justification',
        'performed_by'
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}