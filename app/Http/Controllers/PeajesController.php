<?php

namespace App\Http\Controllers;

use App\Models\Peajes;
use App\Models\Peajes_Ruta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeajesController extends Controller
{
    public function index()
    {
        $peajes = Peajes::all(); 
        return response()->json($peajes);
    }

    public function create(Request $request)
    {
        $peaje = new Peajes($request->all());
        $peaje->save();
        return response()->json(['message' => 'Peaje registrada con exito'], 201);
    }



    public function show($id)
    {
        $peaje = Peajes::find($id);
        return response()->json($peaje);
    }

    public function update(Request $request, $id)
    {
        Peajes::find($id)->update($request->all()); 
    }

    public function addPeaje(Request $request)
    {
        // Validar que la caseta id no se repita en el mismo id de ruta
        $validatedData = $request->validate([
            'caseta_id' => 'unique:peajes__rutas,caseta_id,0,id,ruta_id,' . $request->input('ruta_id'),
        ]);

        $peaje = new Peajes_Ruta($request->all());
        $peaje->save();
        return response()->json(['message' => 'Caseta agregada con exito'], 201);
    } 

    public function peajesR()
    {
        $peajes = DB::table('peajes__rutas')
            ->leftJoin('peajes', 'peajes__rutas.caseta_id', '=', 'peajes.id')
            ->select('peajes.id', 'peajes.name', 'peajes.address')
            ->where('peajes__rutas.ruta_id', 0)
            ->get();

        return response()->json($peajes);
    }

    public function peajes($id)
    {
        $peajes = DB::table('peajes__rutas')
            ->leftJoin('peajes', 'peajes__rutas.caseta_id', '=', 'peajes.id')
            ->where('peajes__rutas.ruta_id', $id)
            ->get();

        return response()->json($peajes);
    }

    public function destroyRuta($id, $ruta)
    {
        Peajes_Ruta::where('caseta_id', $id)->where('ruta_id', $ruta)->delete(); // Elimina la caseta 
        return response()->json(['message' => 'Caseta eliminada'], 201);
    }

    public function destroy($id)
    {
        $existinginRuta = Peajes_Ruta::where('caseta_id', $id)->where('ruta_id', '!=',  0)->first();
        if ($existinginRuta) {
            return response()->json(['message' => 'La caseta se encuentra en alguna ruta'], 422);
        }else{
            Peajes::find($id)->delete();
            return response()->json(['message' => 'Caseta eliminada exitosamente.'], 201);
        }
    }
}
