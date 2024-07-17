<?php

namespace App\Http\Controllers;

use App\Models\Collaborators;
use Illuminate\Http\Request;

class CollaboratorsController extends Controller
{
    public function store(Request $request)
    {
        $collaborator = new Collaborators($request->all());

        $collaborator->save();
        return response()->json(['message' => 'Colaborador registrado con exito'], 201);
    }

    public function show($id)
    {
        // Buscar todos los colaboradores donde 'id_area' es igual al id proporcionado
        $collaborators = Collaborators::where('id_area', $id)->get();
        
        // Retornar la lista de colaboradores como JSON
        return response()->json($collaborators);
    }

    public function destroy($id)
    {
        Collaborators::destroy($id);

        return response()->json(['message' => 'Colaborador eliminado exitosamente.'], 201);
    }
}
