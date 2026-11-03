<?php

namespace App\Http\Controllers;

use App\Models\InventoryDetails;
use App\Models\InventoryOutput;
use App\Models\OutputDetails;
use App\Models\ProductsServices;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventoryOutputController extends Controller
{

    /**
     * Historial de salidas de un almacén
     */
    public function index(Request $request)
    {
        try {

            $idInventory = $request->id_inventory;

            $outputs = InventoryOutput::with([
                'user',
                'details.product.categoryProduct'
            ])
            ->where('id_inventory', $idInventory)
            ->orderBy('id', 'desc')
            ->get();

            return response()->json($outputs);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al consultar el historial de salidas.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_inventory' => 'required|exists:warehouses,id',
            'receiver' => 'required|string|max:255',
            'observations' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id_product' => 'required|exists:products_services,id',
            'products.*.cantidad' => 'required|numeric|min:0.1',
        ]);

        try {
            $inventoryOutput = DB::transaction(function () use ($request) {

                $inventoryId = $request->id_inventory;
                $productsToUpdate = [];

                // 1. Validar productos y existencias.
                foreach ($request->products as $item) {

                    $product = ProductsServices::findOrFail($item['id_product']);
                    $quantity = (float) $item['cantidad'];

                    $inventoryDetail = InventoryDetails::where('id_inventory', $inventoryId)
                        ->where('id_product', $product->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$inventoryDetail) {
                        throw new \Exception("El producto {$product->name} no existe en el inventario.");
                    }

                    if ((float) $inventoryDetail->quality < $quantity) {
                        throw new \Exception("Stock insuficiente para {$product->name}. Disponible: {$inventoryDetail->quality}, solicitado: {$quantity}");
                    }

                    // 2. Obtener costo neto desde Laravel.
                    $netCost = (float) $product->net_cost;

                    $productsToUpdate[] = [
                        'inventoryDetail' => $inventoryDetail,
                        'productId' => $product->id,
                        'quantity' => $quantity,
                        'price' => $netCost,
                    ];
                }

                // 3. Crear cabecera de salida.
                $inventoryOutput = InventoryOutput::create([
                    'id_inventory' => $inventoryId,
                    'date' => now(),
                    'user_id' => auth()->id(),
                    'receiver' => $request->receiver,
                    'observations' => $request->observations,
                ]);

                // 4. Descontar inventario y guardar costo neto.
                foreach ($productsToUpdate as $item) {

                    $item['inventoryDetail']->decrement('quality', $item['quantity']);

                    OutputDetails::create([
                        'id_output' => $inventoryOutput->id,
                        'id_product' => $item['productId'],
                        'quality' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                return $inventoryOutput;
            });

            // 5. Generar PDF.
            return $this->generarPDF($inventoryOutput->id);

        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'No se pudo registrar la salida.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function generarPDF($output)
    {
        $pdfContent = $this->PDF($output);        

        Storage::disk('public')->put('Outputs/SALIDA ALMACEN N°'. ($output) . '.pdf', $pdfContent);

        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');// Devolver el contenido del PDF
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }

    private function PDF($output)
    {       
        $outputData = InventoryOutput::where('id', $output)
            ->first();
            
        $details = OutputDetails::where('id_output', $output)
            ->with('product') // Carga la relación del producto
            ->get();

        $fecha = Carbon::parse(now())->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');

        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath); // Convertir las imágenes a base64

        $data = [
            'logoImage' => $logoImage,
            'DataOut' => $outputData,
            'DataDetails' => $details,
            'fecha' => $fecha, // Safely handle the date
        ];

        $html = view('29 F-04-04 VALE DE SALIDA DE ALMACEN', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();                     
    }

    public function show($id)
    {
        $details = InventoryOutput::where('id_inventory', $id)
            ->with([
                'user',
                'details' => function ($query) {
                    $query->with(['product.categoryProduct']);
                }
            ])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return response()->json($details);
    }
}
