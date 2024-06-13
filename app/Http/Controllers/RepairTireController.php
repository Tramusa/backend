<?php

namespace App\Http\Controllers;

use App\Models\CtrlTires;
use App\Models\Earrings;
use App\Models\HistoryTire;
use App\Models\RepairTires;
use Illuminate\Http\Request;

class RepairTireController extends Controller
{
    public function store(Request $request)
    {
        // Obtener todos los datos de la solicitud
        $data = $request->all();
        // Agregar la fecha actual al array de datos
        $data['date'] = now();

        // Verificar si existe un registro de reparación con un odómetro mayor o igual al proporcionado
        $existingRepair = RepairTires::where('tire', $data['tire'])
                        ->orderBy('odometro', 'desc')
                        ->first();

        if ($existingRepair && $existingRepair->odometro >= $data['odometro']) {
            return response()->json(['message' => 'El valor del odómetro no puede ser menor o igual al ya registrado.'], 400);
        }
  
        // Crear la revisión con la fecha actual
        RepairTires::create($data);

        // Generar actividad en el historial de la llanta
        HistoryTire::create([
            'tire_ctrl' => $data['tire'],
            'activity' => 'Reparacion',
            'date' => now(),
            'details' => 'Odometro: '.$data['odometro']. ', Tipo: '.$data['damage_type'],
        ]);

        // Obtener la información de la llanta desde la tabla CTRL_LLANTAS
        $ctrlTire = CtrlTires::where('id', $data['tire'])->first();

        // Verificar si se encontró la información de la llanta
        if ($ctrlTire) {
            $dataFalla = [
                'unit' => $ctrlTire->unit,
                'type' => $ctrlTire->type,
                'description' => 'Reparacion de llanta N°' . $data['tire'] . ', Odometro: ' . $data['odometro'] . ', Tipo: ' . $data['damage_type']
            ];

            // Verificar si la descripción ya existe en los pendientes registrados
            $existingEarring = Earrings::where('description', 'like', '%' . $dataFalla['description'] . '%')
                                    ->where('status', 1)
                                    ->where('type', $dataFalla['type'])
                                    ->where('unit', $dataFalla['unit'])
                                    ->first();

            // Si no existe un pendiente con la misma descripción, crear uno nuevo
            if (!$existingEarring) {
                Earrings::create($dataFalla);
            }
        } else {
            return response()->json(['message' => 'No se encontró información de la llanta.'], 404);
        }

        return response()->json(['message' => 'Reparacion registrada exitosamente.'], 201);
    }
}