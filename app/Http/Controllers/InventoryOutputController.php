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
use Illuminate\Support\Facades\Storage;

class InventoryOutputController extends Controller
{

    public function index(Request $request)
    {
        try {

            $idInventory = $request->id_inventory;

            $outputs = InventoryOutput::with([
                'user',
                'details.product'
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
            'products.*.price' => 'required|numeric|min:0',
        ]);

        $userId = auth()->id();
        $inventoryId = $request->id_inventory;
        $errors = [];
        $productsToUpdate = [];

        /*----------------------------------------------------------------------
        | 1. VALIDAR EXISTENCIAS
        |--------------------------------------------------------------------------*/
        foreach ($request->products as $product) {
            $productId = $product['id_product'];
            $quantity = (float) $product['cantidad'];
            $price = (float) $product['price'];
            $inventoryDetail = InventoryDetails::where('id_inventory', $inventoryId)
            ->where('id_product', $productId)
            ->first();

            /*------------------------------------------------------------------------
            | Producto no existe en inventario
            |-------------------------------------------------------------------------*/
            if (!$inventoryDetail) {
                $productData = ProductsServices::find($productId);
                $errors[] = "El producto " .($productData->name ?? 'desconocido') ." no existe en el inventario.";
                continue;
            }

            /*-----------------------------------------------------------------------
            | Stock insuficiente
            |--------------------------------------------------------------------------*/
            if ((float) $inventoryDetail->quality < $quantity) {
                $errors[] =
                    "Stock insuficiente para el producto: " .($inventoryDetail->product->name ?? 'desconocido') .
                    ". Disponible: " .$inventoryDetail->quality .
                    ", solicitado: " .$quantity;
                continue;
            }

            /*------------------------------------------------------------------------
            | Precio válido
            |------------------------------------------------------------------------*/
            if ($price < 0) {
                $errors[] =
                    "El precio del producto " . ($inventoryDetail->product->name ?? 'desconocido') .
                    " no es válido.";
                continue;
            }

            /*----------------------------------------------------------------------
            | Guardar para actualizar
            |--------------------------------------------------------------------------*/
            $productsToUpdate[] = [
                'inventoryDetail' => $inventoryDetail,
                'productId' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        /*------------------------------------------------------------------------
        | 2. SI HAY ERRORES NO HACER NADA
        |------------------------------------------------------------------------ */
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 400);
        }

        /*-----------------------------------------------------------------------
        | 3. CREAR SALIDA
        |-------------------------------------------------------------------------*/
        $inventoryOutput = InventoryOutput::create([
            'id_inventory' => $inventoryId,
            'date' => now(),
            'user_id' => $userId,
            'receiver' => $request->receiver,
            'observations' => $request->observations,
        ]);

        /*-----------------------------------------------------------------------
        | 4. DESCONTAR INVENTARIO Y GUARDAR PRECIO
        |------------------------------------------------------------------------*/
        foreach ($productsToUpdate as $item) {
            $inventoryDetail = $item['inventoryDetail'];
            /*  Descontar existencia */
            $inventoryDetail->decrement('quality', $item['quantity']);

            /* Guardar detalle de salida */
            OutputDetails::create([
                'id_output' => $inventoryOutput->id,
                'id_product' => $item['productId'],
                'quality' => $item['quantity'],
                // ESTE ES EL PRECIO QUE EL USUARIO DEJÓ
                'price' => $item['price'],
            ]);
        }

        /*-----------------------------------------------------------------------
        | 5. GENERAR PDF
        |------------------------------------------------------------------------*/
        return $this->generarPDF($inventoryOutput->id);
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

        Logger($outputData);
        Logger($details);

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
        // Filtrar por id_inventory si se proporciona en la solicitud
        $details = InventoryOutput::where('id_inventory', $id)->with('user')->get();
    
        return response()->json($details);
    }
}
