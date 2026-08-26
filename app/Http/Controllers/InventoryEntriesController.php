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
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            $entry = DB::transaction(function () use ($request) {
                /*------------------------------------------------------------------------
                | 1. Crear cabecera de entrada
                |------------------------------------------------------------------------*/
                $entry = InventoryEntries::create([
                    'id_inventory' => $request->id_inventory,
                    'invoice' => $request->invoice ?: 'N/A',
                    'requisition' => $request->requisition,
                    'date' => $request->date,
                    'user_id' => auth()->id(),
                ]);

                /*----------------------------------------------------------------------
                | 2. Registrar productos
                |------------------------------------------------------------------------*/
                foreach ($request->products as $item) {
                    $product = ProductsServices::findOrFail($item['id_product']);

                    $quantity = (float) $item['quantity'];
                    $unitPrice = (float) $item['unit_price'];
                    $subtotal = $quantity * $unitPrice;

                    /*----------------------------------------------------------------------
                    | 3. Guardar detalle histórico de la entrada
                    |------------------------------------------------------------------------*/

                    EntryDetails::create([
                        'id_entry' => $entry->id,
                        'id_product' => $product->id,
                        // COPIA HISTÓRICA DEL PRODUCTO
                        'name' =>  $product->name,
                        'category' => $product->category,
                        'unit_measure' => $product->unit_measure,
                        'description' => $product->description ?? null,
                        // DATOS DE LA COMPRA
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);

                    /*----------------------------------------------------------------------
                    | 4. Buscar existencia del producto
                    |-----------------------------------------------------------------------*/
                    $inventoryDetail =
                        InventoryDetails::firstOrCreate(
                            [
                                'id_inventory' => $request->id_inventory,
                                'id_product' => $product->id,
                            ],
                            [
                                'quality' => 0,
                            ]
                        );

                    /*-----------------------------------------------------------------------
                    | 5. Incrementar existencia
                    |-------------------------------------------------------------------------*/
                    $inventoryDetail->increment('quality', $quantity);

                    /*---------------------------------------------------------------------
                    | 6. Actualizar precio actual del producto
                    |-----------------------------------------------------------------------*/
                    $product->update(['price' => $unitPrice,]);
                }

                return $entry;
            });

            return response()->json([
                'message' => 'Entrada registrada correctamente.',
                'entry' => $entry,
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'No se pudo registrar la entrada.',
                'error' =>  $e->getMessage(),
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
        ->with(['user', 'details'])
        ->orderByDesc('date')
        ->orderByDesc('id')
        ->get();

        return response()->json($entries);
    }
}