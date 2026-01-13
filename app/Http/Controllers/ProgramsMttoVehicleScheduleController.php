<?php

namespace App\Http\Controllers;

use App\Models\ProgramsMttoVehicleSchedule;
use Illuminate\Http\Request;

class ProgramsMttoVehicleScheduleController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'program_mtto_vehicle_id' => 'required|exists:programs_mtto_vehicles,id',
            'year' => 'required|integer',
            'week' => 'required|integer|min:1|max:53',
        ]);

        $schedule = ProgramsMttoVehicleSchedule::firstOrCreate(
            [
                'program_mtto_vehicle_id' => $request->program_mtto_vehicle_id,
                'year' => $request->year,
                'week' => $request->week,
            ],
            [
                'status' => 'scheduled',
            ]
        );

        return response()->json([
            'message' => 'Actividad programada correctamente',
            'data' => $schedule,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = ProgramsMttoVehicleSchedule::where('program_mtto_vehicle_id', $id)
            ->where('week', $request->current_week)
            ->first();  // si no existe, lanza 404

        $schedule->update([
            'week' => $request->week, // nueva semana
            'rescheduled_to_week' => $request->current_week, // semana original
            'observation' => $request->observation,
            'status' => 'rescheduled',
        ]);

        return response()->json([
            'message' => 'Reprogramación guardada correctamente',
            'data' => $schedule,
        ]);
    }



    public function destroy(Request $request, $id)
    {
        $schedule = ProgramsMttoVehicleSchedule::where('program_mtto_vehicle_id', $id)
            ->where('week', $request->week)
            ->first();

        if (!$schedule) {
            return response()->json(['message' => 'Programación no encontrada'], 404);
        }

        $schedule->delete();

        return response()->json(['message' => 'Programación eliminada correctamente']);
    }

}
