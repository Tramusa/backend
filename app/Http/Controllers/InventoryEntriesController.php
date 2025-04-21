<?php

namespace App\Http\Controllers;

use App\Models\EntryDetails;
use App\Models\InventoryDetails;
use App\Models\InventoryEntries;
use Illuminate\Http\Request;

class InventoryEntriesController extends Controller
{  
    public function store(Request $request)
    {
        $request->validate([
            'id_inventory' => 'required|exists:warehouses,id',
            'products' => 'required|array',
            'products.*.id_product' => 'required|exists:products_services,id',
            'products.*.cantidad' => 'required|numeric|min:0.1', // Permitir decimales
        ]);

        $userId = auth()->id(); // Obtener usuario autenticado
        $inventoryId = $request->id_inventory;

        // 1️⃣ **Registrar la entrada en InventoryEntries**
        $inventoryEntry = InventoryEntries::create([
            'id_inventory' => $inventoryId,
            'date' => now(),
            'user_id' => $userId,
        ]);

        foreach ($request->products as $product) {
            $productId = $product['id_product'];
            $quantity = (float) $product['cantidad']; // Convertir a float para permitir decimales

            // 2️⃣ **Actualizar o crear en InventoryDetails**
            $inventoryDetail = InventoryDetails::where('id_inventory', $inventoryId)
                ->where('id_product', $productId)
                ->first();

            if ($inventoryDetail) {
                // Si existe, incrementar calidad
                $inventoryDetail->increment('quality', $quantity);
            } else {
                // Si no existe, crear nuevo registro
                InventoryDetails::create([
                    'id_inventory' => $inventoryId,
                    'id_product' => $productId,
                    'quality' => $quantity,
                ]);
            }

            // 3️⃣ **Registrar en EntryDetails**
            EntryDetails::create([
                'id_entry' => $inventoryEntry->id,
                'id_product' => $productId,
                'quality' => $quantity,
            ]);
        }

        return response()->json([
            'message' => 'Inventario actualizado correctamente',
            'inventory_entry' => $inventoryEntry,
        ], 201);
    }    

    public function show($id)
    {    
        // Filtrar por id_inventory si se proporciona en la solicitud
        $details = InventoryEntries::where('id_inventory', $id)->with('user')->get();
    
        return response()->json($details);
    }
}
