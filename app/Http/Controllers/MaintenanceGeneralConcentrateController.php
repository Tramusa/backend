<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceGeneralConcentrate;
use Illuminate\Http\Request;

class MaintenanceGeneralConcentrateController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $query = MaintenanceGeneralConcentrate::with([
            'program',
            'schedule'
        ])
        ->orderBy('year', 'desc')
        ->orderBy('week', 'asc')
        ->orderBy('id', 'asc');

        /*
        |--------------------------------------------------------------------------
        | FILTRO DE ESTADO
        |--------------------------------------------------------------------------
        |
        | active = Pendientes + En proceso
        | finished = Finalizados
        | all = Todos
        |
        */

        if ($status === 'active') {

            $query->whereIn('status', [0, 1]);

        } elseif ($status === 'finished') {

            $query->where('status', 2);

        } elseif ($status === 'all') {

            // No aplicar filtro

        } else {

            // Por seguridad, si llega un valor desconocido
            $query->whereIn('status', [0, 1]);
        }

        $concentrate = $query->get();

        return response()->json([
            'status_filter' => $status,
            'total' => $concentrate->count(),
            'data' => $concentrate,
        ]);
    }
}