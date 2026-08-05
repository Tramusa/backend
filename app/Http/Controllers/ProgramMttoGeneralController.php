<?php

namespace App\Http\Controllers;

use App\Models\ProgramMttoGeneral;
use Illuminate\Http\Request;

class ProgramMttoGeneralController extends Controller
{
    public function index()
    {
        return ProgramMttoGeneral::with('weeks')
            ->orderBy('category')
            ->orderBy('building')
            ->orderBy('activity')
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity'   => 'required|string|max:255',
            'area'       => 'required|string|max:255',
            'building'   => 'required|string|max:255',
            'category'   => 'required|in:COMPUTO,INFRA',
            'observations' => 'nullable|string',
        ]);

        $activity = ProgramMttoGeneral::create([
            'activity'      => $request->activity,
            'area'          => $request->area,
            'building'      => $request->building,
            'category'      => $request->category,
            'observations'  => $request->observations,
            'status'        => 1,
        ]);

        return response()->json($activity,201);
    }

    public function show($id)
    {
        return ProgramMttoGeneral::with('weeks')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $activity = ProgramMttoGeneral::findOrFail($id);

        $activity->update([
            'activity'      => $request->activity,
            'area'          => $request->area,
            'building'      => $request->building,
            'category'      => $request->category,
            'observations'  => $request->observations,
            'status'        => $request->status ?? $activity->status,
        ]);

        return response()->json([
            'message'=>'Actividad actualizada'
        ]);
    }

    public function destroy(ProgramMttoGeneral $programMttoGeneral)
    {
        $programMttoGeneral->schedules()->delete();

        $programMttoGeneral->delete();

        return response()->json([
            'message' => 'Actividad eliminada correctamente.'
        ]);
    }

    public function toggleStatus(ProgramMttoGeneral $programMttoGeneral)
    {
        $programMttoGeneral->update([
            'status' => !$programMttoGeneral->status
        ]);

        return response()->json([
            'message' => 'Estado actualizado',
            'status' => $programMttoGeneral->status
        ]);
    }
}