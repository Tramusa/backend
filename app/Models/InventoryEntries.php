<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryEntries extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_inventory',
        'date',
        'user_id',
        'invoice',
        'requisition',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'id_inventory');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(EntryDetails::class, 'id_entry');
    }
}
