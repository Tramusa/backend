<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityInvestigationAction extends Model
{
    protected $table = 'security_investigation_actions';

    protected $fillable = [
        'security_investigation_id',
        'tipo',
        'numero',
        'descripcion',
        'responsible_id',
        'fecha_compromiso',
    ];

    protected $casts = [
        'numero' => 'integer',
        'fecha_compromiso' => 'date',
    ];

    // Investigación a la que pertenece la acción
    public function investigation(): BelongsTo
    {
        return $this->belongsTo(SecurityInvestigation::class, 'security_investigation_id');
    }

    // Usuario responsable de la acción
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}
