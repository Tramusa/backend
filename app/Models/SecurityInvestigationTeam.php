<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityInvestigationTeam extends Model
{
    protected $table = 'security_investigation_teams';

    protected $fillable = [
        'security_investigation_id',
        'orden',
        'puesto',
        'nombre',
        'firma_ruta',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    public function investigation(): BelongsTo
    {
        return $this->belongsTo(SecurityInvestigation::class, 'security_investigation_id');
    }
}