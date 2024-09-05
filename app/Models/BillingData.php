<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingData extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id', 'id_order', 'date', 'payment_form', 'payment_method'
    ];

    public function order()
    {
        return $this->hasOne(BillingData::class, 'id', 'id_order');
    }
}
