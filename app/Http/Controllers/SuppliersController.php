<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers = Suppliers::all(); 
        return response()->json($suppliers); 
    }

    public function store(Request $request)
    {
        $supplier = new Suppliers($request->all());

        $supplier->save();
        return response()->json(['message' => 'Proveedor registrado con exito'], 201);
    }

    public function show($id)
    {
        $supplier = Suppliers::find($id);
        return response()->json($supplier);
    }

    public function update(Request $request, $id)
    {
        // Find the supplier by ID, or return a 404 error if not found
        $supplier = Suppliers::findOrFail($id);

        // Update the supplier with the provided data
        $supplier->update($request->all());
        
        return response()->json(['message' => 'Proveedor actualizado exitosamente.'], 201);
    }

    public function destroy($id)
    {
        Suppliers::destroy($id);

        return response()->json(['message' => 'Proveedor eliminado exitosamente.'], 201);
    }
}