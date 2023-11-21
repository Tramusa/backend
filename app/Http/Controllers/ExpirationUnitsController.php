<?php

namespace App\Http\Controllers;

use App\Models\ExpirationUnits;
use App\Models\UnitsPDFs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpirationUnitsController extends Controller
{
    public function index()
    {
        $expirations = ExpirationUnits::where('status', 1)->orderBy('type_unit')->get();
        //sacar info de la unidad  donde type_unit es la tabla y unit es el id agregar a expirations
        foreach ($expirations as $expiration) {
            $unitInfo = DB::table($expiration->type_unit)
                ->where('id', $expiration->unit)
                ->get();    
            // Agregar la información de la unidad al registro de expirations
            $expiration->unit = $unitInfo;
        } 
        return response()->json($expirations);
    }

    public function update($id)
    {
        $expirationUnit = ExpirationUnits::find($id);

        if (!$expirationUnit) {
            return response()->json(['message' => 'Expiration unit not found'], 404);
        }

        $expirationUnit->status = 0;
        $expirationUnit->date_attended = now();
        $expirationUnit->save();

        return response()->json(['message' => 'Expiration status updated successfully']);
    }

    public function show($id)
    {
        $expiration = ExpirationUnits::find($id);
    
        if ($expiration) {
            // Retrieve information about the associated unit
            $unitInfo = DB::table($expiration->type_unit)
                ->where('id', $expiration->unit)
                ->first(); // Use first() to get a single result
    
            // Check if unitInfo is not null
            if ($unitInfo) {
                // Add the unit information to the expiration object
                $expiration->unitInfo = $unitInfo;
            } else {
                // Handle the case where unitInfo is not found (e.g., return an error response)
                return response()->json(['error' => 'Unit information not found'], 404);
            }
    
            return response()->json($expiration);
        } else {
            // Handle the case where the expiration record is not found
            return response()->json(['error' => 'Expiration unit not found'], 404);
        }
    }

    public function destroy($id)
    {
        ExpirationUnits::find($id)->delete();
        return response()->json(['message' => 'Vencimiento eliminado'], 201);
    }

    public function updatePDF(Request $request)
    {
        $request->validate([
            'unit_id' => 'required',
            'type_unit' => 'required',
            'pdf' => 'required|file|mimes:pdf'
        ]);
        
        $unitId = $request->input('unit_id');
        $typeUnit = $request->input('type_unit');
        // Buscar el PDF existente relacionado con 'unit_id' y 'type_unit'
        $existingPdf = UnitsPDFs::where('unit_id', $unitId)
            ->where('type_unit', $typeUnit)
            ->first();
        if ($existingPdf) {
            if ($request->hasFile('pdf')) {                
                $path = $request->file('pdf')->store('public/pdfs');// Subir el nuevo archivo PDF
                // Si existe un PDF relacionado, actualiza la ruta del archivo PDF existente
                Storage::delete($existingPdf->location); // Elimina el archivo PDF anterior del sistema de archivos
                $existingPdf->location = $path; // Actualiza la ruta del archivo en la base de datos
                $existingPdf->save();
            }
            return response()->json(['message' => 'Archivo PDF actualizado correctamente']);
        }
        return response()->json(['error' => 'No se encontró ningún archivo PDF']);
    }

    public function updateDate(Request $request)
    {
        $table = $request->input('table');
        $id = $request->input('unit');
        $unit = DB::table($table)->where('id', $id)->first();
        if ($unit) {
            DB::table($table)->where('id', $id)->update($request->except(['_token', 'table', 'unit']));
            return response()->json(['message' => 'Fecha actualizada correctamente']);
        }
        return response()->json(['error' => 'No se encontró ninguna entrada con el ID de unidad especificado en la tabla proporcionada']);
    }
}