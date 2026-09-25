<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SecurityFlashAlertImage extends Model
{
    protected $fillable = [
        'security_flash_alert_id',
        'tipo',
        'imagen',
        'orden',
    ];

    protected $appends = [
        'url',
    ];

    public function alert()
    {
        return $this->belongsTo(
            SecurityFlashAlert::class,
            'security_flash_alert_id'
        );
    }

    public function getUrlAttribute()
    {
        return Storage::disk('public')->url(
            $this->imagen
        );
    }
}