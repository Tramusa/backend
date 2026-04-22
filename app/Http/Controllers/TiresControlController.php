<?php

namespace App\Http\Controllers;

use App\Models\HistoryTire;
use App\Models\TiresControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TiresControlController extends Controller
{
    public function index(Request $request)
    {
        $query = TiresControl::orderBy('id', 'desc');

        // Filtrar por número económico si se envía
        if ($request->filled('assignment')) {
            $query->where('assignment', $request->assignment);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required',
            'installed_at' => 'required|date',
            'position' => 'required',
            'burn_number' => 'required',
        ]);

        $tire = new TiresControl();

        $tire->fill($request->only([
            'dot',
            'serial_number',
            'brand',
            'model',
            'installed_at',
            'position',
            'burn_number',
            'tread_depth',
        ]));

        // 🔥 assignment directo (FZNxxx / Almacén / Desecho)
        $tire->assignment = $request->assignment;

        // 🔥 lógica de status automática
        if ($request->assignment === 'Almacen') {
            $tire->status = 'paused';
        } else {
            $tire->status = 'in_use';
        }

        $tire->save();

        // 🔥 HISTORIAL AUTOMÁTICO
        HistoryTire::create([
            'tire_ctrl' => $tire->id,
            'activity' => 'Registro',
            'date' => now(),
            'details' => 'Llanta registrada en sistema (' . $tire->assignment . ')',
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'LLanta agregada existosamente...',
            'data' => $tire
        ]);
    }

    public function show($id)
    {
        $tire = TiresControl::findOrFail($id);

        return response()->json($tire);
    }

    public function update(Request $request, $id)
    {
        $tire = TiresControl::findOrFail($id);

        // 🔥 valores anteriores
        $oldAssignment = $tire->assignment;
        $oldPosition = $tire->position;

        // 🔥 nuevos valores
        $newAssignment = $request->assignment;
        $newPosition = $request->position;

        /*** 🔥 UPDATE DATOS (sin status) */
        $tire->update($request->except('status')); // excluimos status del request

        //🔥 STATUS AUTOMÁTICO
        if ($newAssignment === 'Almacen') {
            $tire->status = 'paused';
        } else {
            $tire->status = 'in_use';
        }
        $tire->save(); // guardar el status actualizado

        //🔥 DETECTAR CAMBIOS
        $changes = [];

        // 🚛 Cambio de unidad / asignación
        if ($oldAssignment !== $newAssignment) {
            $changes[] = "Cambio de asignación: {$oldAssignment} → {$newAssignment}";
        }

        // 📍 Cambio de posición
        if ($oldPosition !== $newPosition) {
            $changes[] = "Cambio de posición: {$oldPosition} → {$newPosition}";
        }

        /**🔥 GUARDAR HISTORIAL SI HUBO CAMBIOS*/
        if (!empty($changes)) {
            HistoryTire::create([
                'tire_ctrl' => $tire->id,
                'activity' => 'Actualización',
                'date' => now(),
                'details' => implode(' | ', $changes),
                'user_id' => Auth::id(),
            ]);
        }

        return response()->json([
            'message' => 'Llanta actualizada correctamente',
            'data' => $tire
        ]);
    }

    public function destroy($id)
    {
        TiresControl::findOrFail($id)->delete();

        return response()->json([
            'message' => 'LLanta eliminada existosamente...'
        ]);
    }

    public function scrap(Request $request, $id)
    {
        $tire = TiresControl::findOrFail($id);

        // Validar que venga el motivo
        $request->validate([
            'motivo' => 'required|string|max:255',
        ]);

        // Guardar estado anterior
        $oldStatus = $tire->status;

        // Actualizar llanta a desecho
        $tire->update([
            'status' => 'scrapped',
        ]);

        // Crear detalle con oldStatus → Desecho + motivo
        $details = "Cambio de status: {$oldStatus} → Desecho | Motivo: {$request->motivo}";

        // Guardar historial
        HistoryTire::create([
            'tire_ctrl' => $tire->id,
            'activity' => 'Desecho',
            'date' => now(),
            'details' => $details,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Llanta desechada correctamente',
            'data' => $tire,
        ]);
    }

    public function getByUnit($type, $unit) 
{
    $unitData = DB::table('units_all')
        ->where('unit_id', $unit)
        ->where('type', $type)
        ->first();

    if (!$unitData) {
        return response()->json([
            'message' => 'Unidad no encontrada'
        ], 404);
    }

    logger((array) $unitData); // ya no da error

    $tires = TiresControl::where('assignment', $unitData->no_economic)
        ->where('status', 'in_use')
        ->orderBy('position')
        ->get();

    logger($tires->toArray()); // ya no da error

    return response()->json($tires);
}
}