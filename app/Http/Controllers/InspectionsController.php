<?php

namespace App\Http\Controllers;

use App\Models\Inspections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectionsController extends Controller
{
    public function index($user)
    {
        $inspections = DB::table('inspections')->where('status', 1)->where('responsible', $user)->get();
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($inspections as $inspection) {
            $id_unit = $inspection->unit;
            $id_responsible = $inspection->responsible;
            
            $unit = DB::table($tablas[$inspection->type])->select('no_economic')->where('id', $id_unit)->first();
            $inspection->unit = $unit->no_economic;    
                   
            $responsible = DB::table('users')->select('name')->where('id', $id_responsible)->first();
            $inspection->responsible = $responsible->name;
        }
        return response()->json($inspections);
    }

    public function create(Request $request)
    {
        #Logger($request);
        try {
            // Obtén el tipo y el ID de la unidad de la solicitud
            $type = $request->input('type');
            $unitId = $request->input('unit');

            // Determina la tabla correspondiente según el tipo de unidad
            $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
            $tablaUnidad = $tablas[$type];

            // Actualiza el estado de la unidad a "inspection" en la tabla correspondiente
            if (!empty($tablaUnidad)) {
                DB::table($tablaUnidad)->where('id', $unitId)->update(['status' => 'inspection']);
            }

            // Guarda la inspección
            $program = new Inspections($request->all());
            $program->save();

            return response()->json(['message' => 'Inspección generada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al generar la inspección.'], 500);
        }
    }

    public function show($id)
    {
        $inspection = Inspections::find($id);
        return response()->json($inspection);
    }
}