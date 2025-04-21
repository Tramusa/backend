<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryOutput extends Model
{
    use HasFactory;

    protected $fillable = ['id_inventory', 'date', 'user_id'];
    
    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'id_inventory');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
