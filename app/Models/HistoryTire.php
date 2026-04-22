<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTire extends Model
{
    use HasFactory;

    protected $fillable = ['tire_ctrl', 'activity', 'date', 'details', 'user_id'];

    public function ctrlTire()
    {
        return $this->belongsTo(CtrlTires::class, 'tire_ctrl', 'id');
    } 

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
