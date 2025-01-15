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
        $supplierId = $request->query('supplier');

        // Construir la consulta
        $query = PurchaseOrder::query();

        if ($supplierId) {
            $query->where('id_supplier', $supplierId);
        }

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
                'performInfo',
                'authorizeInfo'
            ])->get();

        // Recorrer cada orden de compra para agregar la URL del comprobante y la factura
        foreach ($purchaseOrders as $order) {
            // Comprobante
            if ($order->requisition && $order->requisition->comprobante) {
                $order->requisition->comprobante_url =  asset(Storage::url($order->requisition->comprobante));
            } else {
                $order->requisition->comprobante_url = null;
            }

            // Factura (billing)
            $billing = $order->billing(); // Aquí obtenemos la factura
            if ($billing) {
                $order->billing = $billing; // Añadimos la información de la factura a la orden
            }
        }

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
        
        // Verificar si la factura ya existe considerando folio e id_supplier
        $billing = BillingData::where('folio', $billingData['folio'])
            ->where('id_supplier', $orderData['id_supplier'])
            ->first();

        if ($billing) {
            // La factura ya existe, verificar si la orden ya está registrada
            $orderIds = explode(',', $billing->id_order); // Separar los IDs de la orden existentes
            if (!in_array($order->id, $orderIds)) {
                // Agregar el nuevo ID de la orden
                $billing->id_order .= ',' . $order->id;
                $billing->save();
            }
        } else {
            // La factura no existe, crear un nuevo registro con id_supplier
            $billingData['id_order'] = $order->id;
            $billingData['id_supplier'] = $orderData['id_supplier']; // Agregar id_supplier al registro
            BillingData::create($billingData);
        }

        // Actualizar el estado de la requisición a 'ORDEN COMPRA'
        $requisition = Requisitions::find($orderData['id_requisition']);
        $requisition->status = 'ORDEN COMPRA';
        $requisition->date_atended = now();
        $requisition->analyze = $user->id;
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

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:APROBADA,CANCELADA',
        ]);
        // Verifica si el usuario está autenticado
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $order = PurchaseOrder::findOrFail($id);
        $order->status = $request->input('status');
        $order->authorize = $request->input('status') === 'APROBADA' ? $user->id : null;
        $order->cancel_reason = $request->input('reason');

        // Si el estado es CANCELADA, actualiza también el estado de la requisición relacionada
        if ($request->input('status') === 'CANCELADA') {
            $order->authorize = null; // Borra cualquier autorización anterior

            // Encuentra la requisición relacionada con esta orden
            $requisition = Requisitions::where('id', $order->id_requisition)->first();
            
            // Si existe la requisición, actualiza su estado
            if ($requisition) {
                $requisition->status = 'PENDIENTE';
                $requisition->save();
            }
        }

        $order->save();

        return response()->json(['message' => 'Estatus actualizado correctamente']);
    }

    public function updatePdf(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
            'requisitionId' => 'required|integer|exists:requisitions,id',
        ]);

        $requisition = Requisitions::find($request->input('requisitionId'));

        // Eliminar el archivo anterior si existe
        if ($requisition->comprobante) {
            Storage::delete($requisition->comprobante);
        }

        // Guardar el nuevo archivo
        $path = $request->file('file')->store('Comprobantes', 'public');
        $requisition->comprobante = $path;
        $requisition->save();

        return response()->json(['message' => 'PDF actualizado exitosamente']);
    }

}
