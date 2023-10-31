<?php

namespace App\Http\Controllers;

use App\Models\ExpirationUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpirationUnitsController extends Controller
{
    public function index()
    {
        $expirations = ExpirationUnits::orderBy('type_unit')->get();
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

    public function update(Request $request, $id)
    {
        
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
}
