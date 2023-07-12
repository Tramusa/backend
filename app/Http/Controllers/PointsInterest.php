<?php

namespace App\Http\Controllers;

use App\Models\Peajes_Ruta;
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
            $ruta = new Rutas($request->all());
            $ruta->save();
            
            //SELECCIONAMOS LA RUTA SI LO ENCUENTRA, LE AGREGAMOS EL ID A LOS PEAJES
            $ruta = DB::table('rutas')->where('origin', $request->origin)->where('destination', $request->destination)->first();
            if ($ruta) {
                $peajes = DB::table('peajes__rutas')->where('ruta_id', null)->get();
                foreach ($peajes as $peaje) {
                    //Agregar ID
                    Peajes_Ruta::find($peaje->id)->update(['ruta_id'=> $ruta->id]);
                }
            }
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
        $ruta->origin_name = $orig->name;
        
        $des = DB::table('points_interests')->where('id', $destination)->first();
        $ruta->destination_name = $des->name;

        return response()->json($ruta);
    }

    
    public function updateRuta(Request $request)
    {
        $requestData = $request->all(); // Obtén todos los datos del objeto $request como un arreglo asociativo
        unset($requestData['origin_name']); // Elimina 'inspection' del objeto $request
        unset($requestData['destination_name']); // Elimina 'inspection' del objeto $request
        
        Rutas::find($request->id)->update($requestData); 
    }
    public function destroyRuta($id)
    {
        Rutas::find($id)->delete();
        return response()->json(['message' => 'Ruta eliminada exitosamente.'], 201);
    }

    public function show($id)
    {
        $Address = ModelsPointsInterest::find($id);
        return response()->json($Address);        
    }

    public function update(Request $request, $id)
    {
        ModelsPointsInterest::find($id)->update($request->all()); 
    }

    public function destroy($id)
    {
        $existingRuta = Rutas::where('origin', $id)->orwhere('destination',  $id)->first();
        if ($existingRuta) {
            return response()->json(['message' => 'La direccion se encuentra en alguna ruta'], 422);
        }else{
            ModelsPointsInterest::find($id)->delete();
            return response()->json(['message' => 'Direccion eliminada exitosamente.'], 201);
        }
    }
}
