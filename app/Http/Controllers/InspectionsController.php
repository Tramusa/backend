<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\Dollys;
use App\Models\Earrings;
use App\Models\Inspections;
use App\Models\Maquinarias;
use App\Models\Remolques;
use App\Models\Sprinters;
use App\Models\Toneles;
use App\Models\Tortons;
use App\Models\Tractocamiones;
use App\Models\Utilitarios;
use App\Models\Volteos;
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
            
            if ($inspection->type == 3) {
                $unit = DB::table('dollys')->select('no_seriously')->where('id', $id_unit)->first();
                $inspection->unit = $unit->no_seriously;
            } else {
                $unit = DB::table($tablas[$inspection->type])->select('no_economic')->where('id', $id_unit)->first();
                $inspection->unit = $unit->no_economic;
            }            
            $responsible = DB::table('users')->select('name')->where('id', $id_responsible)->first();
            $inspection->responsible = $responsible->name;
        }
        return response()->json($inspections);
    }

    public function create(Request $request)
    {
       #Logger($request);
       $program = new Inspections($request->all());                
       $program->save();
       return response()->json(['message' => 'Insepeccion generada existosamente.']);
   
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $inspection = Inspections::find($id);
        return response()->json($inspection);
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
