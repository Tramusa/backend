<?php

namespace App\Http\Controllers;

use App\Models\BillingData;
use App\Models\DetailsRequisitions;
use App\Models\PurchaseOrder;
use App\Models\Requisitions;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el estado de las ordenes de compra desde la solicitud, si existe
        $status = $request->query('status');
    
        // Construir la consulta
        $query = PurchaseOrder::query();
    
        // Filtrar por estado si se proporciona
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filtrar las ordenes de compra con id diferente de 0 y cargar las relaciones necesarias
        $purchaseOrders = $query->with([
                'requisition' => function ($query) {
                    $query->with(['work_areaInfo', 'collaboratorInfo']);
                },
                'supplier',
                'billing',
                'performInfo',
                'authorizeInfo'
            ])->get();
    
        // Devolver la respuesta JSON con las ordenes de compra filtradas
        return response()->json($purchaseOrders);
    }

    public function store(Request $request)
    {
        // Extraer los campos principales
        $orderData = $request->only('id_requisition', 'date_order', 'id_supplier', 'additional', 'total');
        $user = Auth::user();
        $orderData['perform'] = $user->id;
        // Extraer la información de facturación
        $billingData = $request->input('Billing');

        // Actualizar precios de los productos
        $products = $request->input('products');
        foreach ($products as $product) {
            DetailsRequisitions::where('id', $product['id'])
                ->update(['price' => $product['price']]);
        }
        
        // Crear la orden de compra
        $order = PurchaseOrder::create($orderData);
        // Crear datso de facturion
        $billingData['id_order'] = $order->id;
        BillingData::create($billingData);

        // Actualizar el estado de la requisición a 'ORDEN COMPRA'
        $requisition = Requisitions::find($orderData['id_requisition']);
        $requisition->status = 'ORDEN COMPRA';
        $requisition->save();
        
        // Generar el PDF y devolverlo
        return $this->generarPDF($order->id);
    }

    public function generarPDF($order){
        $pdfContent = $this->PDF($order);

        Storage::disk('public')->put('ordersCompra/ORDEN COMPRA N°'. ($order) . '.pdf', $pdfContent);

        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');// Devolver el contenido del PDF
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }

    private function PDF($order)
    {       
        $orderData = PurchaseOrder::where('id', $order)
                ->with([
                    'requisition.products',
                    'requisition.work_areaInfo',
                    'requisition.collaboratorInfo',
                    'requisition.parent_accountInfo',
                    'requisition.title_accountInfo',
                    'requisition.subtitle_accountInfo',
                    'requisition.mayor_accountInfo',
                    'requisition.user_authorized',
                    'supplier',
                    'performInfo',
                    'authorizeInfo',
                ])
                ->first();
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64

        $data = [
            'logoImage' => $logoImage,
            'Data' => $orderData,
            'fecha' => Carbon::parse($orderData->date_order)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
        ];

        $html = view('F-04-03 R4 ORDEN DE COMPRA', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();                     
    }

}
