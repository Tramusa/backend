<?php

namespace App\Http\Controllers;

use App\Models\Tires;
use Illuminate\Http\Request;

class TiresController extends Controller
{
    public function index()
    {
        $tires = Tires::all();

        return response()->json($tires);
    }

    public function store(Request $request)
    {
        // Busca un registro existente que tenga la misma información que la solicitud
        $existingTire = Tires::where($request->all())->first();

        if ($existingTire) {
            // Si se encuentra un registro con la misma información, devuelve una respuesta con el ID
            return response()->json(['tire' => $existingTire->id, 'message' => 'Llanta registrada exitosamente.'], 201);
        }

        // Si no existe, crea el registro y guarda la instancia creada
        $tire = Tires::create($request->all());

        // Devuelve una respuesta JSON con el ID del registro creado 
        return response()->json(['tire' => $tire->id, 'message' => 'Llanta registrada exitosamente.'], 201);
    }

    public function show($id)
    {
        $tires = Tires::find($id);

        return response()->json($tires);
    }

    public function update(Request $request, $id)
    {
        $tires = Tires::find($id);
        $tires->update($request->all());

        return response()->json(['message' => 'Llanta actualizada exitosamente.'], 201);
    }

    public function destroy($id)
    {
        Tires::destroy($id);

        return response()->json(['message' => 'Llanta eliminada exitosamente.'], 201);
    }
}
