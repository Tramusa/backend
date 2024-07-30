<?php

namespace App\Http\Controllers;

use App\Models\SupplierBanck;
use Illuminate\Http\Request;

class SupplierBanckController extends Controller
{
    public function index()
    {
        $bancks = SupplierBanck::all(); 
        return response()->json($bancks); 
    }

    public function store(Request $request)
    {
        $banck = new SupplierBanck($request->all());

        $banck->save();
        return response()->json(['message' => 'Banco registrado con exito'], 201);
    }

    
    public function show($id)
    {
        // Buscar todos los bancos donde 'id_supplier' es igual al id proporcionado
        $banks = SupplierBanck::where('id_supplier', $id)->get();
        
        // Retornar la lista de colaboradores como JSON
        return response()->json($banks);
    }

    public function update(Request $request, $id)
    {
        // Find the banck by ID, or return a 404 error if not found
        $banck = SupplierBanck::findOrFail($id);

        // Update the banck with the provided data
        $banck->update($request->all());
        
        return response()->json(['message' => 'Banco actualizado exitosamente.'], 201);
    }

    public function destroy($id)
    {
        SupplierBanck::destroy($id);

        return response()->json(['message' => 'Banco eliminado exitosamente.'], 201);
    }
}