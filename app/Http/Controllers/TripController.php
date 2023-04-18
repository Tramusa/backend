<?php

namespace App\Http\Controllers;

use App\Models\Dollys;
use App\Models\Remolques;
use App\Models\Tractocamiones;
use App\Models\Trips;
use App\Models\Units_Trips;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    public function addUnit(Request $request)
    {
        $unit = DB::table('units__trips')
            ->join('trips', 'units__trips.trip', '=', 'trips.id')
            ->where('type_unit', $request->type_unit)
            ->where('unit', $request->unit)
            ->where('status', 1)
            ->get();
        if (count($unit) > 0) {
            return response()->json([
                'message' => 'La unidad ya se encuentra en la lista de unidades'
            ], 422); 
        }else{
            $unit = new Units_Trips($request->all());                
            $unit->save();
        }
        return response()->json($request);
    }

    public function search(Request $request)
    {
        $type = $request->input('type');
        $query = $request->input('query');
        switch ($type) {
            case 1:
                $resultados = DB::table('tractocamiones')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_motor', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
                    ->first();
                break;   
            case 2:
                $resultados = DB::table('remolques')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            case 3:
                $resultados = DB::table('dollys')
                    ->where('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            default:
                $resultados = '';
                break;
        } 
        if ($resultados) {
            // Agregamos el campo extra 'type_unit' con valor igual a $type al array de la fila
            $resultados->type_unit = $type;
            return response()->json($resultados);
        }else{
            return response()->json([
                'message' => 'No se encontraron unidades al buscar intente nuevamente'
            ], 422);
        }  
    }

    public function operatorsAll()
    {
        $users = DB::table('users')->where('rol', 'Operador')->get(); 
        return response()->json($users); 
    }

    public function create(Request $request)
    {      
        $id = $request->unit;
        switch ($request->type_unit) {
            case 1:
                $available= Tractocamiones::find($id);
                break;   
            case 2:
                $available= Remolques::find($id);
                break;
            case 3:
                $available= Dollys::find($id);
                break;
            default:
                break;
        }
        if ($available->status == 'available') {        
            $unit = DB::table('units__trips')
                ->join('trips', 'units__trips.trip', '=', 'trips.id')
                ->where('type_unit', $request->type_unit)
                ->where('unit', $request->unit)
                ->where('status', 1)
                ->get();
            if (count($unit) > 0) {
                return response()->json([
                    'message' => 'La unidad ya se encuentra en algun viaje'
                ], 422); 
            }else{
                $trip = new Trips(); 
                $trip->user = $request->user;                   
                $trip->save();
                if($trip){
                    $data = $request->only(['type_unit', 'unit' ]);
                    $data['trip'] = $trip->id;
                    $unit_trip = new Units_Trips($data);
                    $unit_trip->save();
                } 
            }       
            return $trip->id;
        }else{
            return response()->json([
                'message' => 'La unidad no se encuentra disponible para viaje'
            ], 422); 
        }
    }

    public function show($trip)
    {
        $units = DB::table('units__trips')->where('trip', $trip)->get(); 
        foreach ($units as $item) {
            $id = $item->unit;
            switch ($item->type_unit) {
                case 1:
                    $unit = Tractocamiones::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;   
                case 2:
                    $unit = Remolques::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                case 3:
                    $unit = Dollys::find($id);
                    $detaills = $unit->no_seriously.' '.$unit->brand .' '.$unit->model;
                    break;
                default:
                    break;
            }
            $item->detaills = $detaills;
        }
        return response()->json($units); 
    }

    public function showTrips($type)
    {
        $Hoy = date('Y-m-d');
        switch ($type) {
            case 1:
                $trips = DB::table('trips')
                    ->where('origin', null)
                    ->where('destination', null)
                    ->where('operator', null)
                    ->where('status', 1)
                    ->get();
                break;   
            case 2:
                $trips = DB::table('trips')
                    ->where('origin', '!=', null)
                    ->where('destination', '!=', null)
                    ->where('operator', '!=', null)
                    ->where('date', '>', $Hoy)
                    ->where('status', 1)
                    ->get();
                break;
            case 3:
                $trips = DB::table('trips')
                    ->where('origin', '!=', null)
                    ->where('destination', '!=', null)
                    ->where('operator', '!=', null)
                    ->where('date', '<=', $Hoy)
                    ->where('status', 1)
                    ->get();
                break;
        }
        return response()->json($trips);
    }

    public function update(Request $request, $trip)
    {   
        $units = DB::table('units__trips')->where('trip', $trip)->get();    
        $data['status'] = 'trip';
        foreach ($units as $item) {
            $id = $item->unit;
            switch ($item->type_unit) {
                case 1:
                    Tractocamiones::find($id)->update($data);
                    break;   
                case 2:
                    Remolques::find($id)->update($data);
                    break;
                case 3:
                    Dollys::find($id)->update($data);
                    break;
                default:
                    break;
            }
        }
        $trip = Trips::find($trip)->update($request->all());         
    }

    public function deleteUnit($id)
    {        
        $unit = Units_Trips::find($id);
        $unit->delete();
        return response()->json(['message' => 'Unidad eliminada exitosamente.']);
    }

    public function cancel($trip)
    {
        $status['status'] = 0;
        $units = DB::table('units__trips')->where('trip', $trip)->get();
        foreach ($units as $item) {
            $id = $item->id;
            $unit = Units_Trips::find($id);
            $unit->delete();            
        }
        $trip = Trips::find($trip)->update($status);
        return response()->json(['message' => 'Viaje cancelado exitosamente.']);
    }

    public function finish($trip)
    {
        $units = DB::table('units__trips')->where('trip', $trip)->get();
        $data['status'] = 'inspection';
        foreach ($units as $item) {
            $id = $item->unit;
            switch ($item->type_unit) {
                case 1:
                    Tractocamiones::find($id)->update($data);
                    break;   
                case 2:
                    Remolques::find($id)->update($data);
                    break;
                case 3:
                    Dollys::find($id)->update($data);
                    break;
                default:
                    break;
            }
        }
        $status['status'] = 2;
        $trip = Trips::find($trip)->update($status);
        return response()->json(['message' => 'Viaje terminado exitosamente.']);
    }
}
