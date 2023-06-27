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

class EarringsController extends Controller
{
    public function index()
    {
        $earrings = DB::table('earrings')->where('status', 1)->get();
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($earrings as $earring) {
            $id_unit = $earring->unit;
            if ($earring->type == 3) {
                $unit = DB::table('dollys')->select('no_seriously')->where('id', $id_unit)->first();
                $earring->unit = $unit->no_seriously;
            } else {
                $unit = DB::table($tablas[$earring->type])->select('no_economic')->where('id', $id_unit)->first();
                $earring->unit = $unit->no_economic;
            }    
            $earring->type = $tablas[$earring->type];
        }
        return response()->json($earrings);
    }

    public function store(Request $request)
    {
        //
    }

    public function create(Request $request)
    {
        $requestData = $request->all(); // Obtén todos los datos del objeto $request como un arreglo asociativo
        $inspection = $request->inspection; // Obtén el valor de 'inspection' del objeto $request
        unset($requestData['inspection']); // Elimina 'inspection' del objeto $request
        //GENERAMOS LA INFORMACION DE $data QUE ES LA INFO DE LOS PENDIENTES A CREAR
        $data = [];
        $data['unit'] = $inspection['unit'];
        $data['type'] = $inspection['type'];
        foreach ($requestData as $key => $value) {
            $description = 'En '.$key.' No cumple: '.$value;

            // Verificar si la descripción ya existe en los pendientes registrados
            $existingEarring = Earrings::where('description', $description)->where('status', 1)->where('type', $data['type'])->where('unit', $data['unit'])->first();
            if ($existingEarring) {
                continue; // La descripción ya existe, pasa al siguiente pendiente
            }else{
                $data['description'] = $description;
                $earrings = new Earrings($data);//GENERAMOS LOS PENDIENTES UNO A UNO 
                $earrings->save();
            }            
        } 
        //CAMBIAR STATUS
        Inspections::find($inspection['id'])->update(['status'=> 2]);
        $status['status'] = 'available';
        switch ($inspection['type']) {
            case 1:
                Tractocamiones::find($inspection['unit'])->update($status);
                break;   
            case 2:
                Remolques::find($inspection['unit'])->update($status);
                break;
            case 3:
                Dollys::find($inspection['unit'])->update($status);
                break;
            case 4:       
                Volteos::find($inspection['unit'])->update($status);
                break;
            case 5:       
                Toneles::find($inspection['unit'])->update($status);
                break;
            case 6:       
                Tortons::find($inspection['unit'])->update($status);
                break;
            case 7:       
                Autobuses::find($inspection['unit'])->update($status);
                break;
            case 8:       
                Sprinters::find($inspection['unit'])->update($status);
                break;
            case 9:       
                Utilitarios::find($inspection['unit'])->update($status);
                break;
            case 10:       
                Maquinarias::find($inspection['unit'])->update($status);
                break;
            default:

            
                break;
        }
        return response()->json(['message' => 'Inspeccion terminada existosamente.']);
    }

    public function show($id)
    {
        $earring = Earrings::find($id);
        $id_unit = $earring->unit;
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        if ($earring->type == 3) {
            $unit = DB::table('dollys')->select('no_seriously')->where('id', $id_unit)->first();
            $earring->unit = $unit->no_seriously;
        } else {
            $unit = DB::table($tablas[$earring->type])->select('no_economic')->where('id', $id_unit)->first();
            $earring->unit = $unit->no_economic;
        }
        $earring->type = $tablas[$earring->type];
        return response()->json($earring);
    }

    public function update(Request $request, $id)
    {   
        Earrings::find($id)->update(['description'=> $request->description] ); 
    }
}
