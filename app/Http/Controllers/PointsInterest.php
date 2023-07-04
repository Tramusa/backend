<?php

namespace App\Http\Controllers;

use App\Models\PointsInterest as ModelsPointsInterest;
use App\Models\Rutas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointsInterest extends Controller
{
    public function index()
    {
        $addresses = ModelsPointsInterest::all(); 
        return response()->json($addresses);
    }

    public function create(Request $request)
    {
        $address = new ModelsPointsInterest($request->all());
        $address->save();
        return response()->json(['message' => 'Direccion registrada con exito'], 201);
    }

    public function createRuta(Request $request)
    {
        $existingRuta = Rutas::where('origin', $request['origin'])->where('destination',  $request['destination'])->first();
        if ($existingRuta) {
            return response()->json(['message' => 'La ruta ya existe'], 422);
        }else{
            $address = new Rutas($request->all());
            $address->save();
            return response()->json(['message' => 'Ruta registrada con exito'], 201);
        }
    }

    public function rutas()
    {
        $rutas = Rutas::all(); 
        foreach ($rutas as $ruta) {
            $origin = $ruta->origin;
            $destination = $ruta->destination;
            
            $orig = DB::table('points_interests')->where('id', $origin)->first();
            $ruta->origin = $orig->street.' '.$orig->suburb.', '.$orig->city.', '.$orig->state;
        
            $des = DB::table('points_interests')->where('id', $destination)->first();
            $ruta->destination = $des->street.' '.$des->suburb.', '.$des->city.', '.$des->state;
        }
        return response()->json($rutas);
    }

    public function ruta($id)
    {
        $ruta = Rutas::find($id); 
        $origin = $ruta->origin;
        $destination = $ruta->destination;
            
        $orig = DB::table('points_interests')->where('id', $origin)->first();
        $ruta->origin_des = $orig->street.' '.$orig->suburb.', '.$orig->city.', '.$orig->state;
        
        $des = DB::table('points_interests')->where('id', $destination)->first();
        $ruta->destination_des = $des->street.' '.$des->suburb.', '.$des->city.', '.$des->state;

        return response()->json($ruta);
    }

    
    public function updateRuta(Request $request)
    {
        $requestData = $request->all(); // Obtén todos los datos del objeto $request como un arreglo asociativo
        unset($requestData['origin_des']); // Elimina 'inspection' del objeto $request
        unset($requestData['destination_des']); // Elimina 'inspection' del objeto $request
        
        Rutas::find($request->id)->update($requestData); 
    }
    public function destroyRuta($id)
    {
        Rutas::find($id)->delete();
        return response()->json(['message' => 'Ruta eliminada exitosamente.'], 201);
    }

    public function show($id)
    {
        
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
