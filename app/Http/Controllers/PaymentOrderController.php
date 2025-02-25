<?php

namespace App\Http\Controllers;

use App\Models\BillingData;
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

            // Si el estado es APROBADA o PAGADA, agregar facturas con sus órdenes y requisiciones
            if (in_array($payment->status, ['APROBADA', 'PAGADA'])) {
                $billings = BillingData::where('id_supplier', $payment->supplier)
                    ->where('id_paymentOrder', $payment->id) // Filtrar por id_paymentOrder
                    ->get()
                    ->map(function ($billing) use ($payment) {
                        $orderIds = explode(',', $billing->id_order); // Convertir lista en array
                        
                        // Obtener todas las órdenes de la factura con su requisición
                        $orders = PurchaseOrder::whereIn('id', $orderIds)
                            ->with('requisition')
                            ->get();

                        // Verificar si todas las órdenes pertenecen al pago
                        if ($orders->isNotEmpty()) {
                            // Agregar las órdenes a la factura
                            foreach ($orders as $order) {
                                // Si existe un comprobante, generamos la URL pública
                                if ($order->requisition && $order->requisition->comprobante) {
                                    $order->requisition->comprobante = asset(Storage::url($order->requisition->comprobante));
                                } else {
                                    $order->requisition->comprobante = null;
                                }
                            }
                            $billing->orders = $orders;
                            return $billing;
                        }
                    })
                    ->filter(); // Elimina los valores null

                // Agregar las facturas al pago
                $payment->billings = $billings->values();
            }
        });

        return response()->json($payments);
    }

    public function store(Request $request)
    {        
        $orderIds = explode(',', $request->input('orders')); // Convertir la cadena de órdenes en un array
        $billingsIds = explode(',', $request->input('billings')); // Convertir la cadena de facturas en un array

        // Validar los datos principales
        $validated = $request->validate([
            'supplier' => 'required|integer|exists:suppliers,id',
            'billings' => 'required|string', // Validar que las facturas sean una cadena
            'total' => 'required|string',
            'payment' => 'required|string|numeric', // Verificar que el pago sea igual o mayor al total
            'payment_form' => 'required|string',
            'date' => 'required|date',
            'banck' => 'nullable|string',
            'reference' => 'nullable|string',
            'comprobante' => 'nullable|file|mimes:pdf', // Si se sube un archivo comprobante
        ]);
        
        // ✅ Validar manualmente que cada ID de orden de compra exista
        $orderIds = array_map('trim', $orderIds); // Limpiar espacios
        $validOrders = PurchaseOrder::whereIn('id', $orderIds)->pluck('id')->toArray();

        if (count($validOrders) !== count($orderIds)) {
            return response()->json(['error' => 'Algunas órdenes no existen.'], 422);
        }

        // ✅ Validar que las facturas existan en la BD
        $billingsIds = array_map('trim', $billingsIds);
        $validBillings = BillingData::whereIn('id', $billingsIds)->pluck('id')->toArray();

        if (count($validBillings) !== count($billingsIds)) {
            return response()->json(['error' => 'Algunas facturas no existen.'], 422);
        }

        // Verificar si ya existe esta orden de pago
        $existingPayment = PaymentOrder::where([
            'supplier' => $validated['supplier'],
            'total' => $validated['total'],
            'payment_form' => $validated['payment_form'],
            'date' => $validated['date'],
            'payment' => $validated['payment'],
            'banck' => $validated['banck'],
            'status' => 'PENDIENTE', 
        ])->first();
        
        if ($existingPayment) {
            return response()->json(['error' => 'Esta orden de pago ya ha sido registrada.'], 409);
        }

        // ✅ Actualizar el estado de las órdenes a "PAGADA"
        PurchaseOrder::whereIn('id', $orderIds)->update(['status' => 'PAGADA']);

        // ✅ Si se envía un comprobante, almacenarlo
        if ($request->hasFile('comprobante')) {
            $validated['comprobante'] = $request->file('comprobante')->store('Comprobantes', 'public');
        }

        // ✅ Registrar quién elaboró la orden
        $user = Auth::user();
        $validated['elaborate'] = $user->id;

        // ✅ Crear la nueva orden de pago
        $payment = PaymentOrder::create($validated);

        if ($payment) {
            // ✅ Actualizar el campo `id_paymentOrder` en las **facturas** (`billings`)
            BillingData::whereIn('id', $billingsIds)->update(['id_paymentOrder' => $payment->id]);

            // ✅ Devolver el PDF generado
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
                'supplierInfo.firstBankDetail', // Cambiar a firstBankDetail
                'elaborateInfo',
                'authorizeInfo'
            ])
            ->first();

        if ($paymentData && $paymentData->date) {
            $fecha = Carbon::parse($paymentData->date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        } else {
            $fecha = Carbon::parse(now())->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        }

        // Después de obtener el PaymentOrder, cargar las facturas y sus órdenes de compra relacionadas
        if ($paymentData) {
            // Cargar las facturas que tienen asignado este PaymentOrder (billing_data.id_paymentOrder = PaymentOrder.id)
            $billings = BillingData::where('id_paymentOrder', $paymentData->id)->get()
                ->map(function ($billing) {
                    $orderIds = explode(',', $billing->id_order); // Convertir lista en array
                
                    // Obtener todas las órdenes de compra con los datos de las requisiciones
                    $orders = PurchaseOrder::whereIn('id', $orderIds)
                        ->with('requisition') // Eager load the related requisition data
                        ->get();

                    // Verificar si todas las órdenes tienen el estado deseado
                    if ($orders->isNotEmpty()) {
                        // Agregar las órdenes a la factura antes de devolverla
                        $billing->purchaseOrders = $orders;
                        return $billing;
                    }
                });

            // Asignar las facturas cargadas al objeto $paymentData
            $paymentData->billings = $billings;
        }

        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath); // Convertir las imágenes a base64

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
            'status' => 'required|in:APROBADA,CANCELADA,PENDIENTE',
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
            if ($order) {
                // Cargar las facturas que tienen asignado este PaymentOrder (billing_data.id_paymentOrder = PaymentOrder.id)
                BillingData::where('id_paymentOrder', $order->id)->get()
                    ->map(function ($billing) {
                        $orderIds = explode(',', $billing->id_order); // Convertir lista en array
                    
                        // Obtener todas las órdenes de compra con los datos de las requisiciones
                        $purchaseOrders = PurchaseOrder::whereIn('id', $orderIds)->get();
    
                        // Si existen las órdenes, recorre y actualiza su estado
                        foreach ($purchaseOrders as $purchaseOrder) {
                            $purchaseOrder->status = 'APROBADA';  // O el estado que quieras poner
                            $purchaseOrder->save();
                        }
                    });
            }            
        }elseif ($request->input('status') === 'PENDIENTE'){
            $order->authorize = null; // Borra cualquier autorización anterior
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

    public function getInvoicesWithAllStatusOrders($supplierId, $status)
    {
        // Obtener facturas con sus órdenes relacionadas
        $billings = BillingData::where('id_supplier', $supplierId)
            ->get()
            ->map(function ($billing) use ($status) {
                $orderIds = explode(',', $billing->id_order); // Convertir lista en array
                
                // Obtener todas las órdenes de la factura
                $orders = PurchaseOrder::whereIn('id', $orderIds)->get();

                // Verificar si todas las órdenes tienen el estado deseado
                if ($orders->isNotEmpty() && $orders->every(fn($order) => $order->status === $status)) {
                    // Agregar las órdenes a la factura antes de devolverla
                    $billing->orders = $orders;
                    return $billing;
                }
            })
            ->filter(); // Elimina los valores null

        return response()->json($billings->values()); // Resetear índices del array
    }

}
