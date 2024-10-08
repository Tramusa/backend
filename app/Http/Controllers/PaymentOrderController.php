<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentOrderController extends Controller
{
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

        // Crear la nueva orden de pago
        $payment = PaymentOrder::create($validated);

        // Generar el PDF y devolverlo
        return $this->generarPDF($payment->id);
    }

    public function generarPDF($payment){
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
                    'requisition',
                    'supplier',
                    'orders',
                ])
                ->first();

        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64

        $data = [
            'logoImage' => $logoImage,
            'Data' => $paymentData,
            'fecha' => Carbon::parse($paymentData->date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
        ];

        $html = view('F-04-01 R1 ORDEN DE PAGO', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();                     
    }
}
