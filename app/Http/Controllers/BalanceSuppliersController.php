<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\Suppliers;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BalanceSuppliersController extends Controller
{ 
    public function index()
    {
        $balance = []; // Array para almacenar los datos de los proveedores con órdenes aprobadas
    
        // Obtener todos los proveedores junto con sus detalles bancarios
        $suppliers = Suppliers::with('bankDetails')->get();
    
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
                    'bank_details' => $supplier->bankDetails->isNotEmpty()
                        ? $supplier->bankDetails->map(function ($bank) {
                            return [
                                'bank' => $bank->banck ?? '', // Valor por defecto: cadena vacía
                                'account' => $bank->account ?? '',
                                'clabe' => $bank->clabe ?? '',
                            ];
                        })
                        : [['bank' => '', 'account' => '', 'clabe' => '']], // Valores vacíos si no hay detalles bancarios
                ];
            }
        }
    
        return response()->json($balance);
    }

    public function show($id)
    {
        $balance = []; // Array to store supplier data with approved orders

        // Get the specific supplier along with bank details
        $supplier = Suppliers::where('id', $id)->with('bankDetails')->first();

        if ($supplier) {
            // Filter approved payment orders for the supplier
            $approvedPayments = PaymentOrder::where('supplier', $id)
                ->where('status', 'APROBADA')
                ->get();

            // If the supplier has approved orders, calculate the total
            if ($approvedPayments->isNotEmpty()) {
                // Calcular el total sumando directamente el campo 'payment' de las órdenes aprobadas
                $totalAmount = $approvedPayments->sum('payment');
                 // Cargar las órdenes de compra manualmente
                $approvedPayments->each(function ($paymentData) use ($id){
                    $paymentData->purchaseOrders = $paymentData->purchaseOrders()->map(function ($purchaseOrder) use ($id, $paymentData){
                        // Obtener la factura (billing) de la orden de compra
                        $billing = $purchaseOrder->billing(); // Aquí obtenemos la factura
                        // Verificar si billing pertenece al proveedor actual
                        if ($billing && $billing->id_supplier == $id) {
                            // Asignar el total de approvedPayments al total de la factura
                            $billing->total = $paymentData->payment; // Asignamos el payment como total a la factura

                            $purchaseOrder->billing = $billing; // Asignamos la factura si coincide el proveedor
                        } else {
                            $purchaseOrder->billing = null; // Si no coincide, ignoramos la factura
                        }
                        // Obtener la requisición con la relación 'subtitle_accountInfo'
                        $requisition = $purchaseOrder->requisition()->with('subtitle_accountInfo')->first();

                        // Verificar si hay requisición y asignar el nombre del subtítulo si existe
                        $purchaseOrder->subtitle = $requisition && $requisition->subtitle_accountInfo
                            ? $requisition->subtitle_accountInfo->name
                            : null;

                        return $purchaseOrder;
                    });
                });
                
                // Filter purchase orders with unpaid invoices (where payment == 0)
                $orders = $approvedPayments->flatMap(function ($paymentOrder) {
                    return collect($paymentOrder['purchaseOrders'])->filter(function ($purchaseOrder) {
                        return $purchaseOrder->billing && $purchaseOrder->billing->payment == 0;
                    });
                });

                // Populate the balance array with supplier data and totals
                $balance = [
                    'supplier' => $supplier,
                    'orders' => $orders,
                    'total_payments' => $totalAmount,
                    'bank_details' => $supplier->bankDetails->map(function ($bank) {
                        return [
                            'bank' => $bank->banck,
                            'account' => $bank->account,
                            'clabe' => $bank->clabe,
                        ];
                    }),
                ];
            }
        }

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
    
        // Calcular el total de todas las órdenes
        $totalAdeudado = array_sum(array_column($orders, 'total'));

        // Preparar los datos para la vista
        $dataPDF = [
            'logoImage' => $logoImage,
            'supplierInfo' => $supplierInfo,
            'orders' => $orders,
            'totalAdeudado' => $totalAdeudado,
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

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }

}
