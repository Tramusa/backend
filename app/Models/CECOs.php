<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CECOs extends Model
{
    use HasFactory;
    
    protected $fillable = ['customer_id', 'description'];
    
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }    
}