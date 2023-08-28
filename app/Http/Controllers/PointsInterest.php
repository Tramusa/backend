<?php

namespace App\Http\Controllers;

use App\Models\Peajes_Ruta;
use App\Models\PointsInterest as ModelsPointsInterest;
use App\Models\Rutas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
            $request->validate(['image' => 'image|max:3000',]); // 3MB Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.
            
            $data = $request->only(['origin', 'p_intermediate', 'p_authorized', 'destination', 'km', 'time', 'observation']);
        
            $ruta = new Rutas($data);
            $ruta->save();
            
            if ($request->file('image')){            
                $path = $request->file('image')->store('public/rutas');        
                $ruta->image = $path;
                $ruta->save();
                $imagen_rectangular = Image::make($request->file('image'))->fit(280, 320);
                $imagen_rectangular->save(public_path(Storage::url($path)));
            }  
            
            //SELECCIONAMOS LA RUTA SI LO ENCUENTRA, LE AGREGAMOS EL ID A LOS PEAJES
            $ruta = DB::table('rutas')->where('origin', $request->origin)->where('destination', $request->destination)->first();
            if ($ruta) {
                $peajes = DB::table('peajes__rutas')->where('ruta_id', 0)->get();
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
            $ruta->origin = $orig->name;          
        
            $des = DB::table('points_interests')->where('id', $destination)->first();
            $ruta->destination = $des->name;
           
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

        if ($ruta->image) {
            $ruta->image = asset(Storage::url($image));
        }

        return response()->json($ruta);
    }

    
    public function updateRuta(Request $request)
    {
        $request->validate(['image' => 'image|max:3000']); // Asegúrate de que se haya cargado una imagen y que sea de un tipo válido.

        $data = $request->only(['origin', 'destination', 'km', 'time', 'observation']);

        $ruta = Rutas::find($request->id);
        $ruta->update($data);

        if ($request->file('image')) {
            if ($ruta->image) { Storage::delete($ruta->image); }

            $path = $request->file('image')->store('public/rutas');
            $ruta->image = $path;
            $ruta->save();

            $imagen_rectangular = Image::make($request->file('image'))->fit(250, 380);
            $imagen_rectangular->save(public_path(Storage::url($path)));
        }
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
