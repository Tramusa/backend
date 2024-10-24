<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentOrderController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el estado de las ordenes de compra desde la solicitud, si existe
        $status = $request->query('status');
        // Construir la consulta
        $query = PaymentOrder::query();
    
        // Filtrar por estado si se proporciona
        if ($status) {
            $query->where('status', $status);
        }

        // Recuperar todas las órdenes de pago con la relación 'supplierInfo'
        $payments = $query->with('supplierInfo')->get(); 

        // Generar URL completa para el comprobante de pago y el comprobante de cada orden de compra
        $payments->each(function ($payment) {
            // Generar URL para el comprobante de pago
            if ($payment->comprobante) {
                $payment->comprobante = asset(Storage::url($payment->comprobante));
            }

            // Cargar las órdenes de compra personalizadas
            $payment->purchaseOrders = $payment->purchaseOrders()->map(function ($purchaseOrder) {
                // Generar URL para el comprobante de la requisición de cada orden de compra
                if ($purchaseOrder->requisition && $purchaseOrder->requisition->comprobante) {
                    $purchaseOrder->requisition->comprobante = asset(Storage::url($purchaseOrder->requisition->comprobante));
                }
                return $purchaseOrder;
            });
        });

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        // Convertir la cadena de órdenes en un array
        $orderIds = explode(',', $request->input('orders'));

        // Validar los datos principales
        $validated = $request->validate([
            'supplier' => 'required|integer|exists:suppliers,id',
            'orders' => 'required|string', // Validar que sea una cadena
            'total' => 'required|string',
            'payment' => 'required|string|numeric|min:' . $request->input('total'), // Verificar que el pago sea igual o mayor al total
            'payment_form' => 'required|string',
            'date' => 'required|date',
            'banck' => 'nullable|string',
            'reference' => 'nullable|string',
            'comprobante' => 'nullable|file|mimes:pdf', // Si se sube un archivo comprobante
        ]);

        // Validar manualmente que cada ID de orden de compra exista
        $orderIds = array_map('trim', $orderIds); // Asegurarse de quitar espacios
        $validOrders = PurchaseOrder::whereIn('id', $orderIds)->pluck('id')->toArray();

        if (count($validOrders) !== count($orderIds)) {
            return response()->json(['error' => 'Algunas órdenes no existen.'], 422);
        }

        // Actualizar el estado de cada orden a "PAGADA"
        PurchaseOrder::whereIn('id', $orderIds)->update(['status' => 'PAGADA']);

        // Si se envía un comprobante, lo almacenamos
        if ($request->hasFile('comprobante')) {
            $validated['comprobante'] = $request->file('comprobante')->store('Comprobantes', 'public');
        }

        $user = Auth::user();
        $validated['elaborate'] =  $user->id;

        // Crear la nueva orden de pago
        $payment = PaymentOrder::create($validated);
        // Generar el PDF y devolverlo
        if ($payment) {
            // Generar el PDF y devolverlo
            return $this->generarPDF($payment->id);
        }
    }

    public function generarPDF($payment)
    {
        $pdfContent = $this->PDF($payment);        

        Storage::disk('public')->put('ordersPayment/ORDEN PAGO N°'. ($payment) . '.pdf', $pdfContent);

        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');// Devolver el contenido del PDF
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }

    private function PDF($payment)
    {       
        $paymentData = PaymentOrder::where('id', $payment)
            ->with([
                'supplierInfo',
                'elaborateInfo',
                'authorizeInfo'])
            ->first();

        if ($paymentData && $paymentData->date) {
            $fecha = Carbon::parse($paymentData->date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        } else {
            $fecha = Carbon::parse(now())->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        }

        // After retrieving the PaymentOrder, get the related purchase orders
        if ($paymentData) {
            $paymentData->purchaseOrders = $paymentData->purchaseOrders(); // Manually load purchase orders
        }
        //Logger($paymentData);

        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64

        $data = [
            'logoImage' => $logoImage,
            'Data' => $paymentData,
            'fecha' => $fecha, // Safely handle the date
        ];

        $html = view('F-04-01 R1 ORDEN DE PAGO', $data)->render();

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

        $order = PaymentOrder::findOrFail($id);
        $order->status = $request->input('status');
        $order->authorize = $request->input('status') === 'APROBADA' ? $user->id : null;

         // Si el estado es CANCELADA, actualiza también el estado de las órdenes de compra
        if ($request->input('status') === 'CANCELADA') {
            $order->authorize = null; // Borra cualquier autorización anterior

            // Convierte los IDs de las órdenes de compra a un array
            $orderIds = explode(',', $order->orders);

            // Encuentra las órdenes de compra correspondientes
            $purchaseOrders = PurchaseOrder::whereIn('id', $orderIds)->get();

            // Si existen las órdenes, recorre y actualiza su estado
            foreach ($purchaseOrders as $purchaseOrder) {
                $purchaseOrder->status = 'APROBADA';  // O el estado que quieras poner
                $purchaseOrder->save();
            }
        }

        $order->save();

        return response()->json(['message' => 'Estatus actualizado correctamente']);
    }

    public function updatePdf(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
            'id' => 'required|integer|exists:payment_orders,id',
        ]);

        $order = PaymentOrder::find($request->input('id'));

        // Eliminar el archivo anterior si existe
        if ($order->comprobante) {
            Storage::delete($order->comprobante);
        }

        // Guardar el nuevo archivo
        $path = $request->file('file')->store('Comprobantes', 'public');
        $order->comprobante = $path;
        $order->save();

        return response()->json(['message' => 'PDF actualizado exitosamente']);
    }
}
