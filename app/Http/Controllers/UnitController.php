<?php

namespace App\Http\Controllers;

use App\Models\Tractocamiones;
use App\Models\Dollys;
use App\Models\Remolques;
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
                $unit = Tractocamiones::find($request->id)->update($request->all()); 
                return $unit;
                break;
            case 2:       
                $unit = Remolques::find($request->id)->update($request->all());  
                return $unit;
                break;

            case 3:       
                $unit = Dollys::find($request->id)->update($request->all());
                return $unit;
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
            default:
                break;

            return response()->json(['message' => 'Unidad eliminada']);
        } 
        
    }
}
