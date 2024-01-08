<?php

namespace App\Http\Controllers;

use App\Models\Earrings;
use App\Models\OrderDetail;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if ($order->status == 2) {
            $order->authorize = Auth::id(); // ID del usuario logueado
        }else if ($order->status == 3) {
            $order->date_in = now(); // Fecha y hora actual
        }else if ($order->status == 4){
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

    public function cancel($id)
    {
        $order = Orders::find($id);
        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        // Actualizar el estado de la orden
        $order->status = 0;
        try {
            $order->save();
            return response()->json(['message' => 'Orden cancelada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al cancelar la orden'], 500);
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