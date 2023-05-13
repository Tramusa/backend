<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramsController extends Controller
{
    public function index()
    {
        $programs = Programs::all(); 
        return response()->json($programs);
    }

    public function create(Request $request)
    {
        #Logger($request);
        $program = new Programs($request->all());                
        $program->save();
        return response()->json(['message' => 'Mantenimiento agregado existosamente.']);
    }

    public function units($type)
    {
        $tablas = ['','tractocamiones','remolques','dollys','volteos','toneles','tortons','autobuses','sprinters','utilitarios','maquinarias'];
        if ($type == 3) {
            $units = DB::table($tablas[$type])->select('no_seriously')->get();
            foreach ($units as $item) {
                $unit = $item->no_seriously;  
                unset($item->no_seriously);         
                $item->unit = $unit;
            }
            return response()->json($units);
        }else{
            $units = DB::table($tablas[$type])->select('no_economic')->get();
            foreach ($units as $item) {
                $unit = $item->no_economic;   
                unset($item->no_economic);           
                $item->unit = $unit;
            }
            return response()->json($units);
        }
    }

    public function show($id)
    {
        $mto = Programs::find($id);
        return response()->json($mto);
    }

    public function update(Request $request, $id)
    {
        Programs::find($id)->update($request->all()); 
    }

    public function destroy($id)
    {
        $unit = Programs::find($id);
        $unit->delete();
        return response()->json(['message' => 'Mantenimineto eliminado exitosamente.']);
    
    }
}
