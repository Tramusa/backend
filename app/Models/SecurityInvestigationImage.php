<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityInvestigationImage extends Model
{
    protected $table = 'security_investigation_images';

    protected $fillable = [
        'security_investigation_id',
        'tipo',
        'ruta',
        'nombre_original',
        'mime_type',
        'tamano',
        'orden',
    ];

    protected $casts = [
        'tamano' => 'integer',
        'orden' => 'integer',
    ];

    protected $hidden = [
        // No ocultamos la ruta: el controller devuelve la URL privada
        // mediante un endpoint autorizado.
    ];

    public function investigation(): BelongsTo
    {
        return $this->belongsTo(SecurityInvestigation::class, 'security_investigation_id');
    }
}