<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'date_attended', 'status', 'date_in', 'repair', 'requisitions', 'odometro', 'spare_parts', 'total_parts', 'total_mano', 'authorize', 'perform', 'operator','created_by',];
  
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'id_order');
    }
    
    public function waitingHours()
    {
        return $this->hasMany(WaitingHour::class, 'order_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
