<?php

namespace App\Http\Controllers;

use App\Models\CtrlTires;
use App\Models\HistoryTire;
use App\Models\RevisionsTires;
use App\Models\Tires;
use Illuminate\Http\Request;

class RevisionsTireController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        // Actions to perform if status == 1 (Finalize)
        if ($request->status == 1) {
            $this->finalizeTireRevision($data);
        }        

        // Crear la revisión con la fecha actual
        RevisionsTires::create($data);

        return response()->json(['message' => 'Revision de llanta registrada exitosamente.'], 201);
    }

    public function show($id)
    {
        // Buscar la revisión con el id especificado y el status igual a 0
        $revision = RevisionsTires::where('tire', $id)
                                  ->where('status', 0)
                                  ->first();

        if ($revision) {
            return response()->json($revision);
        } else {
            $revision['tire'] = $id;
            return response()->json($revision);
        }
    }

    public function update(Request $request, $id)
    {
        // Find the revision by ID, or return a 404 error if not found
        $revision = RevisionsTires::findOrFail($id);

        // Get all validated data from the request
        $data = $request->all();

        // Actions to perform if status == 1 (Finalize)
        if ($request->status == 1) {
            $this->finalizeTireRevision($data);
        }

        // Update the revision with the provided data
        $revision->update($data);
        
        return response()->json(['message' => 'Revision de llanta actualizada exitosamente.'], 201);
    }

    private function finalizeTireRevision($data)
    {
        $idTire = $data['tire'];//ID TIRE  
        $data['date'] = now(); // 1. Add current date when finalizing
        
        // Update odometro in CtrlTires and update depth in Tires
        $tireCtrl = CtrlTires::findOrFail($idTire);
        if ($tireCtrl && $tireCtrl->odometro >= $data['odometro']) {
            return response()->json(['message' => 'El valor del odómetro no puede ser menor o igual al ya registrado.'], 400);
        }
        $tireCtrl->update(['odometro' => $data['odometro']]);

        // 2. Calculate average of the measured values and set details 
        $average = ($data['internal_1'] + $data['center_1'] + $data['external_1'] + $data['internal_2'] + $data['center_2'] + $data['external_2'] + $data['internal_3'] + $data['center_3'] + $data['external_3']) / 9;
        $details = '';
        if ($average <= 3) {//if average is less than or equal to 3mm
            $details = '¡Alerta! : LLanta con 3mm o menos';
        }else if ($average <= 7) {//if average is less than or equal to 7mm
            $details = 'Observación! : LLanta proxima a ser cambiada con 7mm o menos';
        }

        // 3. Generate activity in the tire's history
        HistoryTire::create([
            'tire_ctrl' => $idTire,
            'activity' => 'Revision',
            'date' => now(),
            'details' => $details,
        ]);

        // Update the depth in Tires
        $tire = Tires::findOrFail($idTire);
        $tire->update(['depth' => $average]);

        //ESTO DEBE APLICAR PARA RENOVACIONES Y ES ESTATUS MAS R EN USO (N) por EN USO (R) o EN USO (RR)
        //  Update the status of the tire in CtrlTires to 'R'
        //$tireCtrl = CtrlTires::findOrFail($idTire); // Find the tire control record, or return 404 if not found
        //$tireCtrl->update(['status' => 'R']); //  updating the status
    }
}