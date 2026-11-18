<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SecurityInvestigation extends Model
{
    protected $table = 'security_investigations';

    protected $fillable = [
        'security_flash_alert_id',
        'folio',
        'nombre_incidente',
        'fecha_investigacion',
        'lugar',
        'fecha',
        'hora',
        'departamento',
        'empresa_contratista',
        'clasificacion_actual',
        'unidad',
        'operador',
        'afectado',
        'puesto_afectado',
        'supervisor_monitor',
        'investigacion',
        'descripcion',
        'arbol_causas',
        'naturaleza_incidente',
        'causa_raiz',
        'como_se_pudo_evitar',
        'hallazgos_iniciales',
        'reporte_camara_webfleet',
        'reporte_readi_score',
        'reporte_webfleet',
        'otra_informacion',
        'primeros_auxilios',
        'nombre_aplico_primeros_auxilios',
        'agentes',
        'tipos_contacto',
        'actos_inseguros',
        'error_humano',
        'protecciones_factores',
        'condiciones_directas',
        'factor_organizacional',
        'tipo_lesion',
        'parte_cuerpo_afectada',
        'discapacidad',
        'danio_evaluado',
        'especialista',
        'status',
        'finalized_at',
        'finalized_by',
    ];

    protected $casts = [
        'fecha_investigacion' => 'date',
        'fecha' => 'date',
        'investigacion' => 'boolean',
        'finalized_at' => 'datetime',
        'agentes' => 'array',
        'tipos_contacto' => 'array',
        'actos_inseguros' => 'array',
        'error_humano' => 'array',
        'protecciones_factores' => 'array',
        'condiciones_directas' => 'array',
        'factor_organizacional' => 'array',
        'tipo_lesion' => 'array',
        'parte_cuerpo_afectada' => 'array',
    ];

    public function actions(): HasMany
    {
        return $this->hasMany(SecurityInvestigationAction::class)
            ->orderBy('tipo')
            ->orderBy('numero');
    }

    public function team(): HasMany
    {
        return $this->hasMany(SecurityInvestigationTeam::class)
            ->orderBy('orden');
    }

    public function images(): HasMany
    {
        return $this->hasMany(SecurityInvestigationImage::class)
            ->orderBy('tipo')
            ->orderBy('orden');
    }

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    public function securityFlashAlert(): BelongsTo
    {
        return $this->belongsTo(SecurityFlashAlert::class, 'security_flash_alert_id');
    }

    public function concentradoResolutions(): HasMany
    {
        return $this->hasMany(ConcentradoResolution::class, 'security_investigation_id');
    }
}