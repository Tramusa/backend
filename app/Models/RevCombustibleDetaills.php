<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevCombustibleDetaills extends Model
{
    use HasFactory;

    protected $fillable = [  'revision_id', 'name', 'distancia_tablero', 'combustible_cargado', 'factor_correcion', 'distancia_ecm', 'combustible_usado', 'rendimiento_combustible', 'ecm_real', 'peso_bruto', 'tiempo', 'consumo_ralenti', 'tiempo_ralenti', 'consumo_pto', 'tiempo_pto', 'columna' ];
}
