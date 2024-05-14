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
        Tires::create($request->all());

        return response()->json(['message' => 'Llanta registrada exitosamente.'], 201);
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
