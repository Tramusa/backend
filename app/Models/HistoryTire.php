<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTire extends Model
{
    use HasFactory;

    protected $fillable = ['tire_ctrl', 'activity', 'date', 'details'];

    public function ctrlTire()
    {
        return $this->belongsTo(CtrlTires::class, 'tire_ctrl', 'id');
    } 
}
