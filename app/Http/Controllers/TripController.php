<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\CECOs;
use App\Models\Customers;
use App\Models\Dollys;
use App\Models\Inspections;
use App\Models\Maquinarias;
use App\Models\PointsInterest as ModelsPointsInterest;
use App\Models\Remolques;
use App\Models\Rutas;
use App\Models\Sprinters;
use App\Models\Toneles;
use App\Models\Tortons;
use App\Models\Tractocamiones;
use App\Models\Trips;
use App\Models\Units_Trips;
use App\Models\Utilitarios;
use App\Models\Volteos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

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
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orwhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            case 4:
                $resultados = DB::table('volteos')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            case 5:
                $resultados = DB::table('toneles')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            case 6:
                $resultados = DB::table('tortons')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            case 7:
                $resultados = DB::table('autobuses')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            case 8:
                $resultados = DB::table('sprinters')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            case 9:
                $resultados = DB::table('utilitarios')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
                    ->first();
                break;
            case 10:
                $resultados = DB::table('maquinarias')
                    ->where('no_economic', 'LIKE', '%' . $query . '%')
                    ->orWhere('brand', 'LIKE', '%' . $query . '%')
                    ->orWhere('model', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_seriously', 'LIKE', '%' . $query . '%')
                    ->orWhere('no_placas', 'LIKE', '%' . $query . '%')
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
    public function rutaTrip(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');
    
        $ruta = Rutas::where('origin', $origin)
            ->where('destination', $destination)
            ->first();
    
        return response()->json($ruta);
    }

    public function operatorsAll()
    {
        $users = DB::table('users')->where('rol', 'like', 'Operador%')->get(); 
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
            case 4:       
                $available=Volteos::find($id);
                break;
            case 5:       
                $available=Toneles::find($id);
                break;
            case 6:       
                $available=Tortons::find($id);
                break;
            case 7:       
                $available=Autobuses::find($id);
                break;
            case 8:       
                $available=Sprinters::find($id);
                break;
            case 9:       
                $available=Utilitarios::find($id);
                break;
            case 10:       
                $available=Maquinarias::find($id);
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
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                case 4:       
                    $unit=Volteos::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                case 5:       
                    $unit=Toneles::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                case 6:       
                    $unit=Tortons::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                case 7:       
                    $unit=Autobuses::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                case 8:       
                    $unit=Sprinters::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                case 9:       
                    $unit=Utilitarios::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                case 10:       
                    $unit=Maquinarias::find($id);
                    $detaills = $unit->no_economic.' '.$unit->brand.' ('.$unit->no_placas.')';
                    break;
                default:
                    break;
            }
            $item->detaills = $detaills;
            $item->ejes = $unit->ejes;
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
                foreach ($trips as $item) {
                    $origin = ModelsPointsInterest::find($item->origin);
                    $destination = ModelsPointsInterest::find($item->destination);
                    $item->origin = $origin->name;
                    $item->destination = $destination->name;
                }
                break;
            case 3:
                $trips = DB::table('trips')
                    ->where('origin', '!=', null)
                    ->where('destination', '!=', null)
                    ->where('operator', '!=', null)
                    ->where('date', '<=', $Hoy)
                    ->where('status', 1)
                    ->get();
                foreach ($trips as $item) {
                    $origin = ModelsPointsInterest::find($item->origin);
                    $destination = ModelsPointsInterest::find($item->destination);
                    $item->origin = $origin->name;
                    $item->destination = $destination->name;
                }
                break;
        }        
        return response()->json($trips);
    }

    public function showTrip($id)
    {
        $trip = Trips::find($id);
        $operator = DB::table('users')->where('id', $trip->operator)->get();
        $trip->operator = $operator[0]->name.' '.$operator[0]->a_paterno; 
        
        $origin = ModelsPointsInterest::find($trip->origin);
        $destination = ModelsPointsInterest::find($trip->destination);
        $customer = Customers::find($trip->customer);
        $CECO = CECOs::find($trip->ceco);    
        $ruta = Rutas::where('origin', $trip->origin)
            ->where('destination', $trip->destination)
            ->first();
        $trip->customer_id = $customer->id;
        $trip->customer = $customer->name;
        $trip->prefijo = $customer->prefijo;
        $trip->prefijo = $customer->prefijo;
        $trip->ruta = $ruta->id;
        $trip->km = $ruta->km;
        $trip->time = $ruta->time;
        $trip->ceco = $CECO->description;
        $trip->origin = $origin->name;
        $trip->destination = $destination->name;
        
        return response()->json($trip);
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
                case 4:       
                    Volteos::find($id)->update($data);
                    break;
                case 5:       
                    Toneles::find($id)->update($data);
                    break;
                case 6:       
                    Tortons::find($id)->update($data);
                    break;
                case 7:       
                    Autobuses::find($id)->update($data);
                    break;
                case 8:       
                    Sprinters::find($id)->update($data);
                    break;
                case 9:       
                    Utilitarios::find($id)->update($data);
                    break;
                case 10:       
                    Maquinarias::find($id)->update($data);
                    break;
                default:
                    break;
            }
        }
        Trips::find($trip)->update($request->all()); 
        
        $dompdf = new Dompdf();
        $html = '
        <html>
        <head>
            <style>
                /* Estilos personalizados */
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .logo {
                    max-width: 150px;
                    height: auto;
                }
                .list {
                    margin-bottom: 20px;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    border: 1px solid #000;
                    padding: 5px;
                }
                .footer {
                    text-align: center;
                    position: fixed;
                    bottom: 20px;
                    width: 100%;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <img class="logo" src="ruta/al/logo.png" alt="Logo">
                <h1>¡Hola, PDF!</h1>
            </div>
            <div class="content">
                <h2>VIAJE DE PRUEBA N°' . $trip . '</h2>
                <ul class="list">
                    <li>Elemento 1</li>
                    <li>Elemento 2</li>
                    <li>Elemento 3</li>
                </ul>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Columna 1</th>
                            <th>Columna 2</th>
                            <th>Columna 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dato 1</td>
                            <td>Dato 2</td>
                            <td>Dato 3</td>
                        </tr>
                        <tr>
                            <td>Dato 4</td>
                            <td>Dato 5</td>
                            <td>Dato 6</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer">
                Pie de página
            </div>
        </body>
        </html>';
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $pdfContent = $dompdf->output();
        Storage::disk('public')->put('trips/ViajePrueba N°' . $trip . '_pdf.pdf', $pdfContent);

        // Devolver el contenido del PDF
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
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
        //CAMBIAMOS EL ESTATUS A 2 QUE ES TERMINADO Y AGREGAMOS LA FECHA DE TERMINO DEL VIAJE
        $data_trip['status'] = 2;
        $data_trip['end_date'] = date('Y-m-d');
        Trips::find($trip)->update($data_trip);
        //CAMBIAMOS EL ESTATUS DE LAS UNIDADES INVOLUCRADAS EN EL TRIP ['status'] = 'inspection'
        $units = DB::table('units__trips')->where('trip', $trip)->get();
        $data['status'] = 'inspection';
        foreach ($units as $item) {
            $id = $item->unit;
            //TAMBIEN GENERAMOS UNA INSPECCION FISICOMECANICA POR CADA UNIDAD INVOLUCRADA
            $trip =Trips::find($trip);
            $data_inspection['responsible'] = $trip->operator;
            $data_inspection['type'] = $item->type_unit;
            $data_inspection['unit'] = $id;
            $data_inspection['is'] = 'fisico mecanica';
            $inspection = new Inspections($data_inspection);
            $inspection->save();
            
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
                case 4:       
                    Volteos::find($id)->update($data);
                    break;
                case 5:       
                    Toneles::find($id)->update($data);
                    break;
                case 6:       
                    Tortons::find($id)->update($data);
                    break;
                case 7:       
                    Autobuses::find($id)->update($data);
                    break;
                case 8:       
                    Sprinters::find($id)->update($data);
                    break;
                case 9:       
                    Utilitarios::find($id)->update($data);
                    break;
                case 10:       
                    Maquinarias::find($id)->update($data);
                    break;
                default:
                    break;
            }
        }        
        return response()->json(['message' => 'Viaje terminado exitosamente.']);
    }
}
