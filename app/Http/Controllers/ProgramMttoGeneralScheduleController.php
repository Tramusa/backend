<?php

namespace App\Http\Controllers;

use App\Models\ProgramMttoGeneralSchedule;
use Illuminate\Http\Request;

class ProgramMttoGeneralScheduleController extends Controller
{
    public function index()
    {
        return ProgramMttoGeneralSchedule::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs_mtto_general,id',
            'year'       => 'required|integer',
            'week'       => 'required|integer|min:1|max:52',
        ]);

        $exists = ProgramMttoGeneralSchedule::where('program_id', $request->program_id)
            ->where('year', $request->year)
            ->where('week', $request->week)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'La actividad ya está programada para esa semana.'
            ], 422);
        }

        $schedule = ProgramMttoGeneralSchedule::create([
            'program_id'     => $request->program_id,
            'year'           => $request->year,
            'week'           => $request->week,
            'scheduled_date' => null,
            'status'         => 'pending',
            'completed_date' => null,
        ]);

        return response()->json([
            'message' => 'Semana programada correctamente.',
            'data'    => $schedule
        ], 201);
    }

    public function show($id)
    {
        return ProgramMttoGeneralSchedule::findOrFail($id);
    }

    public function update(Request $request,$id)
    {
        $schedule = ProgramMttoGeneralSchedule::findOrFail($id);

        $request->validate([
            'week'=>'required|min:1|max:52'
        ]);

        $schedule->update([
            'week'=>$request->week,
            'status'=>$request->status ?? $schedule->status
        ]);

        return response()->json([
            'message'=>'Programación actualizada'
        ]);
    }

    public function destroy($id)
    {
        ProgramMttoGeneralSchedule::findOrFail($id)->delete();

        return response()->json([
            'message'=>'Programación eliminada'
        ]);
    }
}