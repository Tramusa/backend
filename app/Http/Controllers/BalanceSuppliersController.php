<?php

namespace App\Http\Controllers;

use App\Models\BillingData;
use App\Models\PaymentOrder;
use App\Models\PurchaseOrder;
use App\Models\Suppliers;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BalanceSuppliersController extends Controller
{ 
    public function index()
    {
        $balance = []; // Array para almacenar los datos de los proveedores con órdenes aprobadas
    
        // Obtener todos los proveedores junto con sus detalles bancarios
        $suppliers = Suppliers::with('bankDetails')->orderBy('business_name')->get();
    
        foreach ($suppliers as $supplier) {
            // Obtener las órdenes de pago aprobadas del proveedor
            $approvedPayments = PaymentOrder::where('supplier', $supplier->id)
                ->where('status', 'APROBADA')
                ->get();
    
            // Calcular el total sumando directamente el campo 'payment' de las órdenes aprobadas
            $totalAmount = $approvedPayments->sum('payment');
    
            // Si el proveedor tiene órdenes aprobadas y un total > 0, agregarlo al balance
            if ($totalAmount > 0) {
                $balance[] = [
                    'supplier' => $supplier,
                    'total_payments' => $totalAmount,                   
                ];
            }
        }
    
        return response()->json($balance);
    }

    public function show($id)
    {
        $balance = []; // Array para almacenar la información del proveedor con órdenes aprobadas
    
        // Obtener el proveedor con sus datos bancarios
        $supplier = Suppliers::where('id', $id)->with('bankDetails')->first();
    
        if (!$supplier) {
            return response()->json(['message' => 'Proveedor no encontrado'], 404);
        }
    
        // Obtener todas las órdenes de pago aprobadas del proveedor
        $approvedPayments = PaymentOrder::where('supplier', $id)
            ->where('status', 'APROBADA')
            ->get();
    
        if ($approvedPayments->isEmpty()) {
            return response()->json(['message' => 'No hay órdenes de pago aprobadas'], 200);
        }
    
        // Sumar el total de la columna "pago" de las órdenes de pago aprobadas
        $totalAmount = $approvedPayments->sum('payment');
        $billings = []; // Para almacenar todas las facturas con sus órdenes y requisiciones
    
        foreach ($approvedPayments as $paymentOrder) {
            // Obtener facturas relacionadas con la orden de pago
            $billingData = BillingData::where('id_paymentOrder', $paymentOrder->id)->get();
    
            foreach ($billingData as $billing) {
                $orderIds = explode(',', $billing->id_order); // Convertimos la lista en array
    
                // Obtener las órdenes de compra con sus requisiciones y cuentas contables relacionadas
                $purchaseOrders = PurchaseOrder::whereIn('id', $orderIds)
                ->with(['requisition' => function ($query) {
                    $query->with('subtitle_accountInfo');
                }])
                ->get();
    
                // Sumar el total de las órdenes de compra asociadas
                $billingTotal = $purchaseOrders->sum('total');
    
                // Agregar la factura con sus órdenes de compra y requisiciones
                $billings[] = [
                    'billing_id' => $billing->id,
                    'billing_folio' => $billing->folio,
                    'billing_date' => $billing->date,
                    'payment_method' => $billing->payment_method,
                    'billing_total' => $billingTotal,
                    'payment_form' => $billing->payment_form,
                    'purchase_orders' => $purchaseOrders->toArray(), // Pasamos todo el objeto completo
                ];
            }
        }
    
        // Construcción del balance final
        $balance = [
            'supplier' => $supplier,
            'billings' => $billings,
            'total_payments' => $totalAmount,
        ];
    
        return response()->json($balance);
    }
    
    public function store(Request $request)
    {
        $supplierInfo = $request->input('supplierInfo');
        $orders = $request->input('orders');

        if (!$supplierInfo || empty($orders)) {
            return response()->json(['message' => 'Datos incompletos'], 400);
        }
    
        // Obtener la fecha actual en español
        $fechaActual = Carbon::now();
        $fecha = $fechaActual->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
    
        // Convertir el logo a base64
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);
    

        // Preparar los datos para la vista
        $dataPDF = [
            'logoImage' => $logoImage,
            'supplierInfo' => $supplierInfo,
            'orders' => $orders,
            'fecha' => $fecha
        ];
    
        // Renderizar la vista con los datos
        $html = view('DETALLES SALDO PROVEEDOR', $dataPDF)->render();
    
        // Configuración de Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $pdfContent = $dompdf->output();   
    
        // Generar un nombre único para el archivo PDF
        $filename = 'Detalles_Saldo_Proveedor_#'.$supplierInfo['supplier'].'_' . now()->format('Ymd_His') . '.pdf';
    
        // Guardar el PDF en el almacenamiento
        Storage::disk('public')->put('pdfs/' . $filename, $pdfContent);
    
        // Devolver el contenido del PDF
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
    }

    public function balancePDF(Request $request){
        $suppliersInfo = $request->input('suppliersInfo');
        // Sumar los total_payments de todos los proveedores
        $totalAdeudado = collect($suppliersInfo)->sum('total_payments');
        $totalApagar = $request->input('totalApagar');;
        $capital = $request->input('capital');
        $saldoDisponible = $request->input('saldoDisponible');

        if (!$suppliersInfo || empty($totalAdeudado)) {
            return response()->json(['message' => 'Datos incompletos'], 400);
        }
    
        // Obtener la fecha actual en español
        $fechaActual = Carbon::now();
        $fecha = $fechaActual->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
    
        // Convertir el logo a base64
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);
    

        // Preparar los datos para la vista
        $dataPDF = [
            'logoImage' => $logoImage,
            'supplierInfo' => $suppliersInfo,
            'totalAdeudado' => $totalAdeudado,
            'totalApagar' => $totalApagar,
            'capital' => $capital,
            'saldoDisponible' => $saldoDisponible,
            'fecha' => $fecha
        ];
    
        // Renderizar la vista con los datos
        $html = view('SALDO PROVEEDORES', $dataPDF)->render();
    
        // Configuración de Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $pdfContent = $dompdf->output();   
    
        // Generar un nombre único para el archivo PDF
        $filename = 'Saldo_Proveedores_' . now()->format('Ymd_His') . '.pdf';
    
        // Guardar el PDF en el almacenamiento
        Storage::disk('public')->put('pdfs/' . $filename, $pdfContent);
    
        // Devolver el contenido del PDF
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }

}
