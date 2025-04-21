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
        $errors = [];
        $productsToUpdate = [];

        // 1️⃣ **Validar stock antes de realizar cambios**
        foreach ($request->products as $product) {
            $productId = $product['id_product'];
            $quantity = (float) $product['cantidad'];

            $inventoryDetail = InventoryDetails::where('id_inventory', $inventoryId)
                ->where('id_product', $productId)
                ->first();

            if ($inventoryDetail) {
                if ($inventoryDetail->quality >= $quantity) {
                    // Guardamos los productos que pueden actualizarse
                    $productsToUpdate[] = [
                        'inventoryDetail' => $inventoryDetail,
                        'quantity' => $quantity,
                        'productId' => $productId
                    ];
                } else {
                    // Error: stock insuficiente
                    $errors[] = "Stock insuficiente para el producto: " . $inventoryDetail->product->name;
                }
            } else {
                // Error: el producto no existe en el inventario
                $productName = ProductsServices::find($productId)->name ?? 'Producto desconocido';
                $errors[] = "El producto $productName no existe en el inventario";
            }
        }

        // 2️⃣ **Si hay errores, no hacemos ningún cambio y los mostramos**
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 400);
        }

        // 3️⃣ **Registrar la salida en InventoryOutput**
        $inventoryOutput = InventoryOutput::create([
            'id_inventory' => $inventoryId,
            'date' => now(),
            'user_id' => $userId,
        ]);

        // 4️⃣ **Actualizar el inventario y registrar la salida**
        foreach ($productsToUpdate as $update) {
            // Reducir la cantidad en el inventario
            $update['inventoryDetail']->decrement('quality', $update['quantity']);

            // Registrar en OutputDetails
            OutputDetails::create([
                'id_output' => $inventoryOutput->id,
                'id_product' => $update['productId'],
                'quality' => $update['quantity'],
            ]);
        }

        // Generar el PDF y devolverlo
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
