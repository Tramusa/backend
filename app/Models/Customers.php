<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'prefijo', 'manager_base', 'email'];

    public function cecos()
    {
        return $this->hasMany(CECOs::class, 'customer_id')->onDelete('cascade');
    }
}
