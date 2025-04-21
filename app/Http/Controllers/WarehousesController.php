<?php

namespace App\Http\Controllers;

use App\Models\Warehouses;
use Illuminate\Http\Request;

class WarehousesController extends Controller
{
    public function index()
    {
        $inventories = Warehouses::with('workArea')->get();
        return response()->json($inventories);
    }

    public function store(Request $request)
    {
        $inventory = new Warehouses($request->all());

        $inventory->save();
        return response()->json(['message' => 'Almacen registrado con exito'], 201);
    }

    public function show($id)
    {
        $inventory = Warehouses::with('workArea')->find($id);
    
        if (!$inventory) {
            return response()->json(['message' => 'Warehouse not found'], 404);
        }
    
        return response()->json($inventory);
    }

    public function update(Request $request, $id)
    {
        // Find the inventory by ID, or return a 404 error if not found
        $inventory = Warehouses::findOrFail($id);

        // Update the inventory with the provided data
        $inventory->update($request->all());
        
        return response()->json(['message' => 'Almacen actualizado exitosamente.'], 201);
    }

    public function destroy($id)
    {
        Warehouses::destroy($id);

        return response()->json(['message' => 'Almacen eliminado exitosamente.'], 201);
    }
}
