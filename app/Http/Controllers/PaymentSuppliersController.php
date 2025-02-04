<?php

namespace App\Http\Controllers;

use App\Models\BillingData;
use App\Models\PaymentOrder;
use App\Models\PaymentSuppliers;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;

class PaymentSuppliersController extends Controller
{
    public function show($id)
    {
        // Obtener todos los pagos del proveedor
        $payments = PaymentSuppliers::where('supplier', $id)->get();

        // Agregar las facturas correspondientes a cada pago
        foreach ($payments as $payment) {
            $payment->billings = $payment->billings(); // Asignar las facturas al pago
        }

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        // Validar los datos principales
        $validated = $request->validate([
            'supplier' => 'required|integer|exists:suppliers,id',
            'billings' => 'required|string',  
            'quality' => 'required|numeric',  
            'user' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);
    
        // Convertir y validar las facturas proporcionadas
        $billingIds = array_map('trim', explode(',', $request->input('billings')));
    
        // Obtener solo las facturas con `payment == 0`
        $validBillings = BillingData::whereIn('id', $billingIds)
            ->where('payment', 0)
            ->pluck('id')
            ->toArray();
    
        if (empty($validBillings)) {
            return response()->json(['error' => 'No hay facturas válidas con estado en 1.'], 422);
        }
    
        // Actualizar el estado de las facturas a PAGADA
        BillingData::whereIn('id', $validBillings)->update(['payment' => 1]);
 
        // 1️⃣ OBTENEMOS TODAS LAS ÓRDENES DE PAGO APROBADAS DEL PROVEEDOR
        $paymentOrders = DB::table('payment_orders')
            ->where('supplier', $validated['supplier'])
            ->where('status', 'APROBADA')
            ->get();

        //$payments = collect();
        $orderIdsToUpdate = []; // Array para almacenar los IDs de órdenes encontradas

        foreach ($paymentOrders as $order) {
            // ✅ Eliminamos espacios y convertimos en array
            $cleanOrders = explode(',', str_replace(' ', '', $order->orders));

            // 2️⃣ OBTENEMOS FACTURAS RELACIONADAS
            $billingData = DB::table('billing_data')
                ->where('id_supplier', $validated['supplier'])
                ->whereIn('id', $validBillings)
                ->where(function ($query) use ($cleanOrders) {
                    foreach ($cleanOrders as $cleanOrder) {
                        $query->orWhereRaw("FIND_IN_SET(?, REPLACE(billing_data.id_order, ' ', ''))", [$cleanOrder]);
                    }
                })
                ->get();

            // 3️⃣ GUARDAMOS LAS ÓRDENES ENCONTRADAS Y SU INFO
            if ($billingData->isNotEmpty()) {
                $orderIdsToUpdate[] = $order->id; // Guardamos el ID para actualizar el status

    //         foreach ($billingData as $billing) {
    //             $payments->push((object) [
    //                 'id' => $order->id,
    //                 'supplier' => $order->supplier,
    //                 'status' => 'PAGADA', // Ya cambiamos el status en la vista
    //                 'orders' => $order->orders,
    //                 'matched_id_order' => $billing->id_order,
    //                 'folio' => $billing->folio,
    //                 'id_supplier' => $billing->id_supplier,
    //                 'payment' => $billing->payment,
    //             ]);
    //         }
            }
        }

        // 4️⃣ ACTUALIZAMOS LAS ÓRDENES EN LA BASE DE DATOS
        if (!empty($orderIdsToUpdate)) {
        DB::table('payment_orders')
            ->whereIn('id', $orderIdsToUpdate)
            ->update(['status' => 'PAGADA']);
        }

        // 5️⃣ IMPRIMIMOS RESULTADOS
        // LOGGER("RELACIÓN DE ÓRDENES DE PAGO ENCONTRADAS Y ACTUALIZADAS A 'PAGADA'");
        // $payments->each(function ($p) {
        //     LOGGER("| {$p->id} | {$p->supplier} | {$p->status} | {$p->orders} LIKE {$p->matched_id_order} | {$p->folio} | {$p->payment} |");
        // });

        // Crear el registro de pago
        $payment = PaymentSuppliers::create($validated);
    
        if ($payment) {
            return response()->json(['message' => 'Pago actualizado correctamente'], 200);
        }
    
        return response()->json(['error' => 'Error al crear el pago'], 500);
    }
    

    
}
