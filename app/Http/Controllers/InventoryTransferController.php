<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransfer;
use App\Models\TransferDetails;
use App\Models\InventoryDetails;
use App\Models\InventoryOutput;
use App\Models\OutputDetails;
use App\Models\EntryDetails;
use App\Models\InventoryEntries;
use App\Models\ProductsServices;
use App\Models\Warehouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use Dompdf\Dompdf;

class InventoryTransferController extends Controller
{
    /*----------------------------------------------------------------------
    | HISTORIAL DE TRASPASOS
    |-------------------------------------------------------------------- */
    public function index($id)
    {
        try {
            $transfers = InventoryTransfer::with(['user', 'origin', 'destination', 'details.product'])
            ->where(function ($query) use ($id) {
                $query->where('inventory_origin_id', $id)
                      ->orWhere('inventory_destination_id', $id);
            })
            ->orderBy('id', 'desc')
            ->get();

            return response()->json($transfers);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al consultar el historial de traspasos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*----------------------------------------------------------------------
    | CREAR TRASPASO
    |----------------------------------------------------------------------*/
    public function store(Request $request)
    {
        $request->validate([
            'inventory_origin_id' => ['required', 'exists:warehouses,id'],
            'inventory_destination_id' => [
                'required', 'exists:warehouses,id', 'different:inventory_origin_id'
            ],
            'receiver' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'observations' => ['nullable', 'string'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id_product' => [
                'required', 'exists:products_services,id'
            ],
            'products.*.quantity' => ['required', 'numeric', 'min:0.1']
        ]);

        try {

            $result = DB::transaction(function () use ($request) {

                $originId = $request->inventory_origin_id;
                $destinationId = $request->inventory_destination_id;
                $userId = auth()->id();

                // 1. Validar existencias y consultar costo neto.
                $productsToTransfer = [];

                foreach ($request->products as $item) {

                    $product = ProductsServices::findOrFail($item['id_product']);

                    $quantity = (float) $item['quantity'];

                    $originDetail = InventoryDetails::where('id_inventory', $originId)
                        ->where('id_product', $product->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$originDetail) {
                        throw new \Exception("El producto {$product->name} no existe en el almacén de origen.");
                    }

                    if ((float) $originDetail->quality < $quantity) {
                        throw new \Exception("Stock insuficiente para {$product->name}. Disponible: {$originDetail->quality}, solicitado: {$quantity}");
                    }

                    // Obtener costo neto directamente del producto.
                    $netCost = (float) $product->net_cost;

                    $productsToTransfer[] = [
                        'product' => $product,
                        'productId' => $product->id,
                        'quantity' => $quantity,
                        'netCost' => $netCost,
                        'originDetail' => $originDetail
                    ];
                }

                // 2. Obtener almacenes.
                $originWarehouse = Warehouses::findOrFail($originId);
                $destinationWarehouse = Warehouses::findOrFail($destinationId);

                // 3. Crear cabecera del traspaso.
                $transfer = InventoryTransfer::create([
                    'inventory_origin_id' => $originId,
                    'inventory_destination_id' => $destinationId,
                    'user_id' => $userId,
                    'receiver' => $request->receiver,
                    'date' => $request->date ?? now(),
                    'observations' => $request->observations ?? "Traspaso de almacén {$originId} a almacén {$destinationId}",
                ]);

                // 4. Crear salida automática.
                $output = InventoryOutput::create([
                    'id_inventory' => $originId,
                    'date' => $transfer->date,
                    'user_id' => $userId,
                    'receiver' => $request->receiver,
                    'observations' => "Traspaso de almacén {$originWarehouse->name} a almacén {$destinationWarehouse->name}",
                ]);

                // 5. Crear entrada automática.
                $entry = InventoryEntries::create([
                    'invoice' => 'TRASPASO',
                    'requisition' => 'DESDE ALMACÉN ' . $originWarehouse->name,
                    'id_inventory' => $destinationId,
                    'date' => $transfer->date,
                    'user_id' => $userId,
                ]);

                // 6. Registrar detalles y actualizar existencias.
                foreach ($productsToTransfer as $item) {

                    $product = $item['product'];
                    $productId = $item['productId'];
                    $quantity = $item['quantity'];
                    $netCost = $item['netCost'];

                    $subtotal = round($quantity * $netCost, 2);

                    // Detalle del traspaso.
                    TransferDetails::create([
                        'id_transfer' => $transfer->id,
                        'id_product' => $productId,
                        'quantity' => $quantity,
                    ]);

                    // Descontar del almacén de origen.
                    $item['originDetail']->decrement('quality', $quantity);

                    // Agregar al almacén de destino.
                    $destinationDetail = InventoryDetails::where('id_inventory', $destinationId)
                        ->where('id_product', $productId)
                        ->lockForUpdate()
                        ->first();

                    if ($destinationDetail) {
                        $destinationDetail->increment('quality', $quantity);
                    } else {
                        InventoryDetails::create([
                            'id_inventory' => $destinationId,
                            'id_product' => $productId,
                            'quality' => $quantity,
                        ]);
                    }

                    // Detalle de salida con costo neto.
                    OutputDetails::create([
                        'id_output' => $output->id,
                        'id_product' => $productId,
                        'quality' => $quantity,
                        'price' => $netCost,
                    ]);

                    // Detalle de entrada con costo neto.
                    EntryDetails::create([
                        'id_entry' => $entry->id,
                        'id_product' => $productId,
                        'name' => $product->name,
                        'category' => $product->category,
                        'unit_measure' => $product->unit_measure,
                        'description' => $product->description ?? null,
                        'quantity' => $quantity,
                        'unit_price' => $netCost,
                        'subtotal' => $subtotal,
                    ]);
                }

                return ['transfer' => $transfer, 'output' => $output, 'entry' => $entry,];
            });

            return response()->json([
                'message' => 'Traspaso realizado correctamente.',
                'transfer' => $result['transfer'],
                'output_id' => $result['output']->id,
                'entry_id' => $result['entry']->id,
            ], 201);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'No se pudo realizar el traspaso.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 400);
        }
    }

    /*----------------------------------------------------------------------
    | PDF
    |----------------------------------------------------------------------*/
    public function generarPDF($id)
    {
        $pdfContent = $this->PDF($id);

        Storage::disk('public')->put('Transfers/TRASPASO ALMACEN N°' . $id . '.pdf', $pdfContent);

        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
    }


    private function PDF($id)
    {
        $transfer = InventoryTransfer::with([
            'user',
            'origin',
            'destination',
            'details.product'
        ])->findOrFail($id);

        $fecha = Carbon::parse($transfer->date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        $logoImagePath = public_path('imgPDF/logo.png');
        $file = file_get_contents($logoImagePath);
        $logoImage = 'data:image/png;base64,' . base64_encode($file);

        $data = [
            'logoImage' => $logoImage,
            'transfer' => $transfer,
            'fecha' => $fecha,
        ];

        $html = view('29 F-04-04 VALE DE TRASPASO DE ALMACEN', $data)->render();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }
}