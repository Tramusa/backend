<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CtrlTires extends Model
{
    use HasFactory;

    protected $fillable = [
        'tire', 'type',  'unit', 'odometro', 'installation_date', 'status', 'position'
    ];

    public function tireInfo()
    {
        return $this->hasOne(Tires::class, 'id', 'tire');
    }

    public function historyTires()
    {
        return $this->hasMany(HistoryTire::class, 'tire_ctrl', 'id')->onDelete('cascade');
    }
}
