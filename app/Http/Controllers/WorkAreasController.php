<?php

namespace App\Http\Controllers;

use App\Models\WorkAreas;
use Illuminate\Http\Request;

class WorkAreasController extends Controller
{
    public function index()
    {
        $areas = WorkAreas::all(); 
        return response()->json($areas); 
    }

    public function store(Request $request)
    {
        $area = new WorkAreas($request->all());

        $area->save();
        return response()->json(['message' => 'Area registrada con exito'], 201);
    }

    public function show($id)
    {
        $area = WorkAreas::find($id);
        return response()->json($area);
    }

    public function update(Request $request, $id)
    {
        // Find the area by ID, or return a 404 error if not found
        $area = WorkAreas::findOrFail($id);

        // Update the area with the provided data
        $area->update($request->all());
          
        return response()->json(['message' => 'Area actualizada exitosamente.'], 201);
    }

    public function destroy($id)
    {
        WorkAreas::destroy($id);

        return response()->json(['message' => 'Area eliminada exitosamente.'], 201);
    }

}
