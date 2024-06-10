<?php

namespace App\Http\Controllers;

use App\Models\CtrlTires;
use App\Models\HistoryTire;
use App\Models\RenovationsTires;
use Illuminate\Http\Request;

class RenovationsTireController extends Controller
{
    public function store(Request $request)
    {
        // Obtener todos los datos de la solicitud
        $data = $request->all();

        // Agregar la fecha actual al array de datos
        $data['date'] = now();

        // Crear el registro en la base de datos
        RenovationsTires::create($data);

         // Obtener la llanta correspondiente de la base de datos
        $tire = CtrlTires::find($data['tire']);

        // Verificar si la llanta existe
        if ($tire) {
            // Obtener el estado actual de la llanta
            $currentStatus = $tire->status;

            // Determinar el nuevo estado basado en el estado actual
            switch ($currentStatus) {
                case 'En Uso (N)':
                    $newStatus = 'En Uso (R)';
                    break;
                case 'En Uso (R)':
                    $newStatus = 'En Uso (RR)';
                    break;
                case 'En Uso (RR)':
                    $newStatus = 'En Uso (RRR)';
                    break;
                default:
                    // Mantener el estado actual si no es uno de los esperados
                    $newStatus = $currentStatus;
                    break;
            }

            // Actualizar el estado de la llanta en la base de datos
            $tire->status = $newStatus;
            $tire->save();
        }

        // Generar actividad en el historial de la llanta
        HistoryTire::create([
            'tire_ctrl' => $data['tire'],
            'activity' => 'Renovacion',
            'date' => now(),
            'details' => 'Estatus: '.$newStatus,
        ]);
    }
}
