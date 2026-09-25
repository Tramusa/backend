<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SecurityFlashAlert extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'security_flash_alerts';

    protected $fillable = [
        'folio',
        'status',

        'area_lugar',
        'fecha',
        'hora',
        'departamento',

        'clasificacion_actual',

        'unidad',
        'operador',

        'afectado',
        'puesto_afectado',
        'supervisor_monitor',

        'descripcion',

        'anexos',
        'acciones_repeticion',
        'investigacion',

        'reportado_por',


        'created_by',
        'finalized_by',
        'finalized_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i',
        'fecha_elaboro' => 'date',
        'fecha_reviso' => 'date',
        'fecha_autorizo' => 'date',
        'finalized_at' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    /*------------------------------------------------------------------------
    | Fotos
    |--------------------------------------------------------------------------*/
    public function images()
    {
        return $this->hasMany(SecurityFlashAlertImage::class, 'security_flash_alert_id')->orderBy('tipo')->orderBy('orden');
    }

    public function evidencias()
    {
        return $this->hasMany(SecurityFlashAlertImage::class, 'security_flash_alert_id')
        ->where('tipo', 'evidencia')
        ->orderBy('orden');
    }

    public function anexosImagenes()
    {
        return $this->hasMany(SecurityFlashAlertImage::class, 'security_flash_alert_id')
        ->where('tipo', 'anexo')
        ->orderBy('orden');
    }

    public function webfleet()
    {
        return $this->hasMany(SecurityFlashAlertImage::class, 'security_flash_alert_id')
        ->where('tipo', 'webfleet')
        ->orderBy('orden');
    }
}