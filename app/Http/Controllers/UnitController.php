<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\Tractocamiones;
use App\Models\Dollys;
use App\Models\Maquinarias;
use App\Models\Remolques;
use App\Models\Sprinters;
use App\Models\Toneles;
use App\Models\Tortons;
use App\Models\Utilitarios;
use App\Models\Volteos;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function units($type)
    {
        switch ($type) {
            case 1:
                $units = Tractocamiones::all();
                return response()->json($units);
            case 2:
                $units = Remolques::all();
                return response()->json($units);
            case 3:
                $units = Dollys::all(); 
                return response()->json($units);
            case 4:
                $units = Volteos::all(); 
                return response()->json($units);
            case 5:
                $units = Toneles::all(); 
                return response()->json($units);
            case 6:
                $units = Tortons::all(); 
                return response()->json($units);
            case 7:
                $units = Autobuses::all(); 
                return response()->json($units);
            case 8:
                $units = Sprinters::all(); 
                return response()->json($units);
            case 9:
                $units = Utilitarios::all(); 
                return response()->json($units);
            case 10:
                $units = Maquinarias::all(); 
                return response()->json($units);
            default:
                break;
        }
    }
    
    public function create(Request $request)
    {      
        $request->validate([ 'tipo' => 'required', ]);

        switch ($request->tipo) {
            case 1:
                $unit = new Tractocamiones($request->all());                
                $unit->save();
                break;
            case 2:
                $unit = new Remolques($request->all());
                $unit->save();
                break;
            case 3:
                $unit = new Dollys($request->all());                
                $unit->save();
                break;
            case 4:
                $unit = new Volteos($request->all());                
                $unit->save();
                break;
            case 5:
                $unit = new Toneles($request->all());                
                $unit->save();
                break;
            case 6:
                $unit = new Tortons($request->all());                
                $unit->save();
                break;
            case 7:
                $unit = new Autobuses($request->all());                
                $unit->save();
                break;
            case 8:
                $unit = new Sprinters($request->all());                
                $unit->save();
                break;
            case 9:
                $unit = new Utilitarios($request->all());                
                $unit->save();
                break;
            case 10:
                $unit = new Maquinarias($request->all());                
                $unit->save();
                break;
            default:
                break;
        }

        return response()->json([
            'message' => 'Successfully registered'
        ], 201);             
       
    }

    public function unit(Request $request)
    {      
        $request->validate([ 'type' => 'required', ]);

        switch ($request->type) {
            case 1:
                $unit = Tractocamiones::find($request->id);
                return $unit;
                break;
            case 2:
                $unit = Remolques::find($request->id);
                return $unit;
                break;
            case 3:
                $unit = Dollys::find($request->id);
                return $unit;
                break;
            case 4:
                $unit = Volteos::find($request->id); 
                return $unit;
                break;
            case 5:
                $unit = Toneles::find($request->id); 
                return $unit;
                break;
            case 6:
                $unit = Tortons::find($request->id); 
                return $unit;
                break;
            case 7:
                $unit = Autobuses::find($request->id); 
                return $unit;
                break;
            case 8:
                $unit = Sprinters::find($request->id); 
                return $unit;
                break;
            case 9:
                $unit = Utilitarios::find($request->id); 
                return $unit;
                break;
            case 10:
                $unit = Maquinarias::find($request->id); 
                return $unit;
                break;
            default:
                break;
        }           
    }

    public function show($unit)
    {
        return response()->json($unit);
    }

    public function update(Request $request, $type)
    {   
        switch ($type) {
            case 1:
                Tractocamiones::find($request->id)->update($request->all()); 
                break;
            case 2:       
                Remolques::find($request->id)->update($request->all());  
                break;
            case 3:       
                Dollys::find($request->id)->update($request->all());
                break;
            case 4:       
                Volteos::find($request->id)->update($request->all());
                break;
            case 5:       
                Toneles::find($request->id)->update($request->all());
                break;
            case 6:       
                Tortons::find($request->id)->update($request->all());
                break;
            case 7:       
                Autobuses::find($request->id)->update($request->all());
                break;
            case 8:       
                Sprinters::find($request->id)->update($request->all());
                break;
            case 9:       
                Utilitarios::find($request->id)->update($request->all());
                break;
            case 10:       
                Maquinarias::find($request->id)->update($request->all());
                break;
            default:
                break;
        } 
    }

    public function destroy(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        switch ($type) {
            case 1:
                $unit = Tractocamiones::find($id);
                $unit->delete();
                break;
            case 2:
                $unit = Remolques::find($id);
                $unit->delete();
                break;
            case 3:
                $unit = Dollys::find($id);
                $unit->delete();                
                break;
            case 4:       
                $unit = Volteos::find($id);
                $unit->delete();
                break;
            case 5:       
                $unit = Toneles::find($id);
                $unit->delete();
                break;
            case 6:       
                $unit = Tortons::find($id);
                $unit->delete();
                break;
            case 7:       
                $unit = Autobuses::find($id);
                $unit->delete();
                break;
            case 8:       
                $unit = Sprinters::find($id);
                $unit->delete();
                break;
            case 9:       
                $unit = Utilitarios::find($id);
                $unit->delete();
                break;
            case 10:       
                $unit = Maquinarias::find($id);
                $unit->delete();
                break;
            default:
                break;

            return response()->json(['message' => 'Unidad eliminada']);
        } 
        
    }
}
