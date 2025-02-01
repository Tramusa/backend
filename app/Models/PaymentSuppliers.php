<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSuppliers extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'quality', 'billings', 'date', 'supplier', 'user'
    ];

    public function billings()
    {
        return BillingData::whereIn('id', explode(',', $this->billings))
                        ->pluck('folio')
                        ->implode(', ');
    }

}
