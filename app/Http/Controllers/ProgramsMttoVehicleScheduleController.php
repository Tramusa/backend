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
        $request->validate([
            'current_week' => 'required|integer|min:1|max:53',
            'week' => 'required|integer|min:1|max:53',
            'observation' => 'nullable|string',
        ]);

        $schedule = ProgramsMttoVehicleSchedule::where('program_mtto_vehicle_id', $id)
            ->where('week', $request->current_week)
            ->firstOrFail();

        $currentWeek = now()->isoWeek();

        // Guardamos el id original del cronograma
        $scheduleId = $schedule->id;

        // Actualizar cronograma
        $schedule->update([
            'week' => $request->week,
            'rescheduled_to_week' => $request->current_week,
            'observation' => $request->observation,
            'status' => 'rescheduled',
        ]);

        /*
        status earrings:
        0 = finalizada
        1 = pendiente
        2 = en proceso

        Si se reprograma para la semana actual o futura,
        eliminar solo las fallas preventivas pendientes o en proceso.
        */
        if ($request->week >= $currentWeek) {
            \App\Models\Earrings::where('schedule_id', $scheduleId)
                ->where('type_mtto', 'Preventivo')
                ->whereIn('status', [1, 2]) // pendiente o en proceso
                ->delete();
        }

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
