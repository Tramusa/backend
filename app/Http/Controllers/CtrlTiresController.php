<?php

namespace App\Http\Controllers;

use App\Models\CtrlTires;
use App\Models\HistoryTire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CtrlTiresController extends Controller
{
    public function index()
    {
        $ctrlTires = CtrlTires::with('tireInfo')->get();
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        
        foreach ($ctrlTires as $ctrlTire) {
            if ($ctrlTire->unit == 0) {
                $ctrlTire->unit = 'Almacen';
            } elseif ($ctrlTire->unit != 0 && isset($tablas[$ctrlTire->type])) {
                // Si la unidad es diferente de 0 y el tipo de unidad es válido
                $tableName = $tablas[$ctrlTire->type];
                $unitInfo = DB::table($tableName)->select('no_economic')->where('id', $ctrlTire->unit)->first();
                
                if ($unitInfo && $unitInfo->no_economic) {
                    $ctrlTire->unit = $unitInfo->no_economic;
                } else {
                    $ctrlTire->unit = null;
                }
            }
        } 

        return response()->json($ctrlTires);
    }

    public function store(Request $request)
    {
        $tire = CtrlTires::create($request->all());
        $tireId = $tire->id; // Obtener el ID de la llanta recién registrada

        HistoryTire::create([
            'tire_ctrl' => $tireId, // Usar el ID de la llanta
            'activity' => 'Registro',
            'date' => now(),
            'details' => 'Registro',
        ]);

        return response()->json(['message' => 'Llanta registrada en control exitosamente.'], 201);
    }

    public function show($id)    
    {
        $ctrlTire = CtrlTires::find($id);
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        if ($ctrlTire->unit == 0) {
            $ctrlTire->nameUnit = 'Almacen';
        } elseif ($ctrlTire->unit != 0 && isset($tablas[$ctrlTire->type])) {
            // Si la unidad es diferente de 0 y el tipo de unidad es válido
            $tableName = $tablas[$ctrlTire->type];
            $unit = DB::table($tableName)->select('no_economic')->where('id', $ctrlTire->unit)->first();
            
            if ($unit && $unit->no_economic) {
                $ctrlTire->nameUnit = $unit->no_economic;
            } else {
                $ctrlTire->nameUnit = null;
            }
        }
        $tireInfo = $ctrlTire->tireInfo;

        return response()->json([
            'ctrlTire' => $ctrlTire,
            'tireInfo' => $tireInfo,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tires = CtrlTires::find($id);
        $requestData = $request->except('nameUnit');
        
        $changes = [];// Crear un array para almacenar los detalles de los cambios

        // Verificar y registrar cambios en la unidad y tipo
        if ($tires->unit != $requestData['unit'] || $tires->type != $requestData['type']) {
            $oldUnit = $this->getUnitName($tires->unit, $tires->type);
            $newUnit = $this->getUnitName($requestData['unit'], $requestData['type']);
            $changes[] = "• Cambio 'Unidad' anterior: '{$oldUnit}', nueva: '{$newUnit}'";
        }

        // Verificar y registrar cambios en la posición
        if ($tires->position != $requestData['position']) {
            $changes[] = "• Cambio 'Posición' anterior: '{$tires->position}', nueva: '{$requestData['position']}'";
        }

        // Verificar y registrar cambios en la fecha de instalación
        if ($tires->installation_date != $requestData['installation_date']) {
            $changes[] = "• Cambio 'Fecha de instalación' anterior: '{$tires->installation_date}', nueva: '{$requestData['installation_date']}'";
        }     

        // Si hay cambios, registrar la nueva actividad en history_tires
        if (!empty($changes)) {
            HistoryTire::create([
                'tire_ctrl' => $id,
                'activity' => 'Actualización',
                'date' => now(),
                'details' => implode("\n", $changes),
            ]);    
        }

        $tires->update($requestData);// Actualizar la llanta con los datos enviados

        
        return response()->json(['message' => 'Llanta actualizada exitosamente.'], 201);
    }

    private function getUnitName($unitId, $type)
    {
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        
        if ($unitId == 0) {
            return 'Almacen';
        }

        if (isset($tablas[$type])) {
            $tableName = $tablas[$type];
            $unit = DB::table($tableName)->select('no_economic')->where('id', $unitId)->first();
            
            if ($unit && $unit->no_economic) {
                return $unit->no_economic;
            }
        }

        return null;
    }

    public function destroy($id)
    {
        $ctrlTire = CtrlTires::findOrFail($id);
        if (!$ctrlTire) {
            return response()->json(['message' => 'Llanta no encontrada.'], 404);
        }

        $ctrlTire->delete();

        return response()->json(['message' => 'Llanta e historial eliminados exitosamente.'], 201);
    }
}
