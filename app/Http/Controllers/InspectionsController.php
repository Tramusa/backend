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

class InspectionsController extends Controller
{
    public function index($user)
    {
        $inspections = DB::table('inspections')->where('status', 1)->where('responsible', $user)->get();
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($inspections as $inspection) {
            $id_unit = $inspection->unit;
            $id_responsible = $inspection->responsible;
            
            if ($inspection->type == 3) {
                $unit = DB::table('dollys')->select('no_seriously')->where('id', $id_unit)->first();
                $inspection->unit = $unit->no_seriously;
            } else {
                $unit = DB::table($tablas[$inspection->type])->select('no_economic')->where('id', $id_unit)->first();
                $inspection->unit = $unit->no_economic;
            }            
            $responsible = DB::table('users')->select('name')->where('id', $id_responsible)->first();
            $inspection->responsible = $responsible->name;
        }
        return response()->json($inspections);
    }

    public function create(Request $request)
    {
        $requestData = $request->all(); // Obtén todos los datos del objeto $request como un arreglo asociativo
        $inspection = $request->inspection; // Obtén el valor de 'inspection' del objeto $request
        unset($requestData['inspection']); // Elimina 'inspection' del objeto $request
        //GENERAMOS LA INFORMACION DE $data QUE ES LA INFOR DE LOS PENDIENTES A CREAR
        $data = [];
        $data['unit'] = $inspection['unit'];
        $data['type'] = $inspection['type'];
        foreach ($requestData as $key => $value) {
            $data['description'] = 'En '.$key.' No cumple: '.$value;
            $earrings = new Earrings($data);//GENERAMOS LOS PENDIENTES UNO A UNO 
            $earrings->save();
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

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $inspection = Inspections::find($id);
        return response()->json($inspection);
    }

    public function edit($id)
    {
        //
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
