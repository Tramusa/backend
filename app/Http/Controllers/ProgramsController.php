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

    public function store(Request $request)
    {
        $program = new Programs($request->all());                
        $program->save();
        return response()->json(['message' => 'Mantenimiento agregado existosamente.']);
    }

    public function units($type)
    {
        $tablas = ['','tractocamiones','remolques','dollys','volteos','toneles','tortons','autobuses','sprinters','utilitarios','maquinarias'];
        
        $units = DB::table($tablas[$type])->select('no_economic')->get();
        foreach ($units as $item) {
            $unit = $item->no_economic;   
            unset($item->no_economic);           
            $item->unit = $unit;
        }
        return response()->json($units);
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
        Programs::find($id)->delete();
        return response()->json(['message' => 'Mantenimineto eliminado exitosamente.']);
    }
}
