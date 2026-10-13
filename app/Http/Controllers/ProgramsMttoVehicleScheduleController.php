<?php

namespace App\Http\Controllers;

use App\Models\ProgramsMttoVehicleSchedule;
use Illuminate\Http\Request;

class ProgramsMttoVehicleScheduleController extends Controller
{
    /**
     * Registrar una actividad en una semana de un año específico.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_mtto_vehicle_id' => 'required|exists:programs_mtto_vehicles,id',
            'year' => 'required|integer|min:2020|max:2100',
            'week' => 'required|integer|min:1|max:52',
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


    /**
     * Reprogramar una actividad.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2100',
            'current_week' => 'required|integer|min:1|max:52',
            'week' => 'required|integer|min:1|max:52',
            'observation' => 'nullable|string',
        ]);

        $schedule = ProgramsMttoVehicleSchedule::where(
                'program_mtto_vehicle_id',
                $id
            )
            ->where('year', $request->year)
            ->where('week', $request->current_week)
            ->firstOrFail();

        $currentYear = now()->year;
        $currentWeek = now()->isoWeek();

        $scheduleId = $schedule->id;

        $schedule->update([
            'week' => $request->week,
            'rescheduled_to_week' => $request->current_week,
            'observation' => $request->observation,
            'status' => 'rescheduled',
        ]);

        /*
         * Si la programación corresponde al año actual,
         * solamente eliminamos pendientes si la nueva semana
         * todavía no ha pasado.
         *
         * Si es un año futuro (ej. 2027), también se considera
         * una programación futura.
         */
        $isCurrentOrFuture =
            $request->year > $currentYear ||
            (
                $request->year == $currentYear &&
                $request->week >= $currentWeek
            );

        if ($isCurrentOrFuture) {
            \App\Models\Earrings::where('schedule_id', $scheduleId)
                ->where('type_mtto', 'Preventivo')
                ->whereIn('status', [1, 2])
                ->delete();
        }

        return response()->json([
            'message' => 'Reprogramación guardada correctamente',
            'data' => $schedule,
        ]);
    }


    /**
     * Eliminar una programación de una semana y año específico.
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2100',
            'week' => 'required|integer|min:1|max:52',
        ]);

        $schedule = ProgramsMttoVehicleSchedule::where(
                'program_mtto_vehicle_id',
                $id
            )
            ->where('year', $request->year)
            ->where('week', $request->week)
            ->first();

        if (!$schedule) {
            return response()->json([
                'message' => 'Programación no encontrada'
            ], 404);
        }

        $schedule->delete();

        return response()->json([
            'message' => 'Programación eliminada correctamente'
        ]);
    }
}