<?php

namespace App\Http\Controllers;

use App\Models\Revisions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevisionsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Obtén el usuario autenticado
        
        if ($user && ($user->rol === 'Administrador' || strpos($user->rol, 'Coordinador') !== false)) {
            $revisions = DB::table('revisions')->where('status', 1)->get();
        } else {
            $revisions = DB::table('revisions')->where('status', 1)->where('responsible', $user->id)->get();
        }

        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($revisions as $revision) {
            $id_unit = $revision->unit;
            $id_responsible = $revision->responsible;
            
            $unit = DB::table($tablas[$revision->type])->select('no_economic')->where('id', $id_unit)->first();
            $revision->unit = $unit->no_economic;    
                   
            $responsible = DB::table('users')->select('name')->where('id', $id_responsible)->first();
            $revision->responsible = $responsible->name;
        }
        return response()->json($revisions);
    }

    public function create(Request $request)
    {
        try {
            // Obtén el tipo y el ID de la unidad de la solicitud
            $type = $request->input('type');
            $unitId = $request->input('unit');

            // Determina la tabla correspondiente según el tipo de unidad
            $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
            $tablaUnidad = $tablas[$type];

            // Actualiza el estado de la unidad a "revision" en la tabla correspondiente
            if (!empty($tablaUnidad)) {
                DB::table($tablaUnidad)->where('id', $unitId)->update(['status' => 'inspection']);
            }

            // Guarda la revision
            $program = new Revisions($request->all());
            $program->save();

            return response()->json(['message' => 'Revision generada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al generar la revision.'], 500);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $revision = Revisions::find($id);

        return response()->json($revision);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
