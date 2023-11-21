<?php

namespace App\Http\Controllers;

use App\Models\Earrings;
use App\Models\OrderDetail;
use App\Models\Orders;
use Illuminate\Http\Request;
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
        return response()->json(['message' => 'Order created successfully', 'order_id' => $orderId]);
    }

    public function show($type)
    {
        $orders = DB::table('orders')
                    ->where('status', $type)
                    ->get();      
        return response()->json($orders);
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
}