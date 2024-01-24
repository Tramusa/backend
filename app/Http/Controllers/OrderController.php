<?php

namespace App\Http\Controllers;

use App\Models\Earrings;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $order = new Orders();// Create a new order
        $order->date = now();
        $order->save();
        
        $orderId = $order->id;// Get the order ID
        
        $selectedEarrings = $request->input('selectedEarrings'); // This should be an array of earring IDs
        foreach ($selectedEarrings as $earringId) {
            $orderDetail = new OrderDetail();
            $orderDetail->id_order = $orderId;
            $orderDetail->id_earring = $earringId;
            $orderDetail->save();
            $earring = Earrings::find($earringId);
            if ($earring) {            
                $earring->status = 0;// Update the status of the earring to '0'
                $earring->save();
            }
        }
        return response()->json(['message' => 'Order creada correctamente', 'order_id' => $orderId]);
    }

    public function show($type)
    {
        $orders = DB::table('orders')
                    ->where('status', $type)
                    ->get();      
        return response()->json($orders);
    }

    public function showOrder($id)
    {
        $order = Orders::find($id);
        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }else{
            return response()->json($order);
        }     
    }

    public function orderEarrings($id)
    {
        $earringsInfo = DB::table('order_details')
            ->join('earrings', 'order_details.id_earring', '=', 'earrings.id')
            ->where('order_details.id_order', '=', $id) // Cambio aquí
            ->select('earrings.*')
            ->get();

        if (!$earringsInfo) {            
            return response()->json(['message' => 'Pendientes no encontrados'], 404);
        }else{
            $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

            foreach ($earringsInfo as $earring) {
                $id_unit = $earring->unit;
                
                $unit = DB::table($tablas[$earring->type])->select('no_economic')->where('id', $id_unit)->first();
                $earring->unit = $unit->no_economic;
                
                $earring->type = $tablas[$earring->type];
            }
            return response()->json($earringsInfo);
        }   
    }

    public function update(Request $request, $id)
    {
        $order = Orders::find($id);
        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }
       
        $order->status = $request->input('data.status'); // Actualizar el estado de la orden

        // Verificar el status y actualizar campos correspondientes
        if ($order->status == 0) {
            $order->fill($request->input('data.form')); // Rellenar con datos del formulario
            $order->status = 3; // Cambiar status a 3
        } else if ($order->status == 2) {
            $order->authorize = Auth::id(); // ID del usuario logueado
        } else if ($order->status == 3) {
            $order->date_in = now(); // Fecha y hora actual
        } else if ($order->status == 4){
            $order->repair = $request->input('data.form.repair');
            $order->spare_parts = $request->input('data.form.spare_parts');
            $order->total_parts = $request->input('data.form.total_parts');
            $order->total_mano = $request->input('data.form.total_mano');
            $order->operator = $request->input('data.form.operator');
            $order->date_attended = now();
            $order->perform = Auth::id();
        }

        try {
            $order->save();
            return response()->json(['message' => 'Orden actualizada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la orden: ' . $e->getMessage()], 500);
        }
    }

    private function PDF($order)
    {
        $orderData = Orders::find($order);//PRIMERO SACAMOS LA INFO DE LA ORDEN
        $operator = User::where('id', $orderData->operator)->first();        
        $autorizo = User::where('id', $orderData->authorize)->first();        
        $realizo = User::where('id', $orderData->perform)->first();     
           
        $earringsInfo = DB::table('order_details')
            ->join('earrings', 'order_details.id_earring', '=', 'earrings.id')
            ->where('order_details.id_order', '=', $order)
            ->select('earrings.*')
            ->get(); // DETALLES DE ORDEN

        if (!$earringsInfo->isEmpty()) {
            $firstEarring = $earringsInfo->first();
            $id_unit = $firstEarring->unit; // Extract unit
            $type = $firstEarring->type; // Extract type
            $fm = $firstEarring->fm; // Extract type

            $fallas = '';
            $numError = 1;
            foreach ($earringsInfo as $earring) {
                $fallas .= $numError . ".- " . $earring->description . "<br>";
                $numError++;
            }
        } else {
            $id_unit = null;
            $type = null;
            $fm = null;
            $fallas = 'No hay fallas en esta orden.';
        }

        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        $unit = DB::table($tablas[$type]) 
                ->where('id', $id_unit)
                ->first(); // Obtener el primer resultado

        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64
 
        $data = [
            'logoImage' => $logoImage,
            'orderData' => $orderData,
            'unit' => $unit,
            'fm' => $fm,
            'fallas' => $fallas,
            'operator' => $operator,
            'autorizo' => $autorizo,
            'realizo' => $realizo,
        ];

        $html = view('F-05-01-R2 ORDEN DE SERVICIO', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();                     
    }

    public function generarPDF($order){
        $pdfContent = $this->PDF($order);

        Storage::disk('public')->put('orders/Orden N°'. ($order) . '.pdf', $pdfContent);

        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');// Devolver el contenido del PDF
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }

    public function cancel($orderId)
    {
        $order = Orders::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        $earringsInfo = OrderDetail::where('id_order', $orderId)->get();

        if ($earringsInfo->isEmpty()) {            
            return response()->json(['message' => 'Pendientes no encontrados'], 404);
        }

        try {
            foreach ($earringsInfo as $earring) {
                Earrings::where('id', $earring->id_earring)->update(['status' => 1]);
            }

            $order->status = 0; // Actualizar el estado de la orden
            $order->save();

            return response()->json(['message' => 'Orden cancelada correctamente', 'total_earrings' => $earringsInfo->count()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la cancelación'], 500);
        }
    }
    
    public function delete($id)
    {
        $earring = OrderDetail::where('id_earring', $id)->first();
    
        if (!$earring) {
            return response()->json(['message' => 'Pendiente no encontrado'], 404);
        }        
    
        $earring->delete();

        $earring = Earrings::find($id);
        if ($earring) {            
            $earring->status = 1;// Update the status of the earring to '0'
            $earring->save();
        }
    
        return response()->json(['message' => 'Pendiente eliminado correctamente']);
    }
}