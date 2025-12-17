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
    public function index(Request $request)
    {
        $tipo = $request->get('tipo'); // personal, cc, utilitario

        $query = DB::table('earrings')
            ->join('units_all', function ($join) {
                $join->on('units_all.unit_id', '=', 'earrings.unit')
                    ->on('units_all.type', '=', 'earrings.type');
            })
            ->where('earrings.status', 1)
            ->select(
                'earrings.*',
                'units_all.no_economic',
                'units_all.logistic',
                'units_all.customer'
            );

        // FILTRO POR LOGÍSTICA
        if ($tipo === 'personal') {
            $query->where('units_all.logistic', 'Logistica Personal');
        }

        if ($tipo === 'cc') {
            $query->where('units_all.logistic', 'Logistica cc');
        }

        if ($tipo === 'utilitario') {
            $query->where('units_all.logistic', 'Utilitarios');
        }

        return response()->json($query->get());
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
            $existingEarring = Earrings::where('description', 'like', '%' . $description . '%')
                            ->where('status', 1)
                            ->where('type', $data['type'])
                            ->where('unit', $data['unit'])
                            ->first();
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
        Earrings::find($id)->update(['description'=> $request->description, 'type_mtto'=> $request->type_mtto] );
        
        return response()->json(['message' => 'Earrings updated successfully.']); 
    }

    public function destroy($id)
    {
        $earring = Earrings::findOrFail($id);
        if (!$earring) {
            return response()->json(['message' => 'Falla no encontrada.'], 404);
        }
    
        $user = auth()->user();
    
        if ($user) {
            $allowedRoles = ['Coordinador Logistica Concentrado', 'Coordinador Logistica Personal', 'Coordinador Mantenimiento', 'Administrador', 'Auxiliar Administrativo'];
    
            if (!in_array($user->rol, $allowedRoles)) {
                return response()->json(['message' => 'No tienes permiso para eliminar esta falla.'], 403);
            }
    
            $earring->delete();
    
            return response()->json(['message' => 'Falla eliminada exitosamente.'], 201);
        }
    
        return response()->json(['message' => 'Usuario no autenticado.'], 401);
    }
}
