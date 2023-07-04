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
use App\Models\UnitsPDFs;
use App\Models\Utilitarios;
use App\Models\Volteos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([ 'tipo' => 'required']);

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
        return response()->json(['message' => 'Unidad registrada con exito'], 201);             
       
    }

    public function show(Request $request)
    { 
        $docs = DB::table('units_p_d_fs')->where('unit_id', $request->id)->where('type_unit', $request->type)->get();
        foreach ($docs as $doc) {
            $doc->location = asset(Storage::url($doc->location));
        }
        return response()->json($docs);            
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
                Tractocamiones::find($id)->delete();
                break;
            case 2:
                Remolques::find($id)->delete();
                break;
            case 3:
                Dollys::find($id)->delete();                
                break;
            case 4:       
                Volteos::find($id)->delete();
                break;
            case 5:       
                Toneles::find($id)->delete();
                break;
            case 6:       
                Tortons::find($id)->delete();
                break;
            case 7:       
                Autobuses::find($id)->delete();
                break;
            case 8:       
                Sprinters::find($id)->delete();
                break;
            case 9:       
                Utilitarios::find($id)->delete();
                break;
            case 10:       
                Maquinarias::find($id)->delete();
                break;
            default:
                break;

            return response()->json(['message' => 'Unidad eliminada']);
        } 
        
    }

    public function destroyDocs($id)
    {
        $unit = UnitsPDFs::find($id);
        if ($unit->location){ Storage::delete($unit->location); }
        $unit->delete();
        return response()->json(['message' => 'Documento eliminado exitosamente.']);
    }

    public function upload(Request $request)
    {
        Logger($request);
        $request->validate([
            'title' => 'required',
            'unit_id' => 'required',
            'pdf' => 'required|file|mimes:pdf'
        ]);

        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('public/pdfs');  
            
            // Guardar el título y el ID de la unidad
            $title = $request->input('title');
            $unitId = $request->input('unit_id');
            $unitType = $request->input('type_unit');
            
            // Crear un nuevo registro en la tabla "pdfs"
            UnitsPDFs::create([
                'title' => $title,
                'unit_id' => $unitId,
                'type_unit' => $unitType,
                'location' => $path
            ]);
            
            return response()->json(['message' => 'Archivo PDF subido correctamente']);
        }
        
        return response()->json(['error' => 'No se encontró ningún archivo PDF']);
    }
}
