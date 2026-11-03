<?php

namespace App\Http\Controllers;

use App\Models\EntryDetails;
use App\Models\InventoryDetails;
use App\Models\InventoryEntries;
use App\Models\ProductsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryEntriesController extends Controller
{
    /**
     * Registrar una entrada
     */    
    public function store(Request $request)
    {
        $request->validate([
            'id_inventory' => 'required|exists:warehouses,id',
            'invoice' => 'nullable|string|max:255',
            'requisition' => 'required|string|max:255',
            'date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.id_product' => 'required|exists:products_services,id',
            'products.*.quantity' => 'required|numeric|min:0.001',
        ]);

        try {
            $entry = DB::transaction(function () use ($request) {

                $entry = InventoryEntries::create([
                    'id_inventory' => $request->id_inventory,
                    'invoice' => $request->invoice ?: 'N/A',
                    'requisition' => $request->requisition,
                    'date' => $request->date,
                    'user_id' => auth()->id(),
                ]);

                foreach ($request->products as $item) {

                    $product = ProductsServices::findOrFail($item['id_product']);

                    $quantity = (float) $item['quantity'];

                    // Calcular el costo neto desde Laravel.
                    $netCost = (float) $product->net_cost;

                    $subtotal = round($quantity * $netCost, 2);

                    // Guardar detalle histórico de la entrada.
                    EntryDetails::create([
                        'id_entry' => $entry->id,
                        'id_product' => $product->id,
                        'name' => $product->name,
                        'category' => $product->category,
                        'unit_measure' => $product->unit_measure,
                        'description' => $product->description ?? null,
                        'quantity' => $quantity,
                        'unit_price' => $netCost,
                        'subtotal' => $subtotal,
                    ]);

                    // Buscar o crear existencia.
                    $inventoryDetail = InventoryDetails::firstOrCreate(
                        [
                            'id_inventory' => $request->id_inventory,
                            'id_product' => $product->id,
                        ],
                        ['quality' => 0,]
                    );

                    // Incrementar existencia.
                    $inventoryDetail->increment('quality', $quantity);
                }

                return $entry;
            });

            return response()->json([
                'message' => 'Entrada registrada correctamente.', 'entry' => $entry,
            ], 201);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'No se pudo registrar la entrada.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    /**
     * Historial de entradas de un almacén
    */
    public function show($id)
    {
        $entries = InventoryEntries::where('id_inventory', $id)
            ->with([
                'user',
                'details.categoryProduct',
                'details.product.categoryProduct'
            ])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return response()->json($entries);
    }
}