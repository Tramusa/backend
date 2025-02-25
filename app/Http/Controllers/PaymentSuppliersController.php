<?php

namespace App\Http\Controllers;

use App\Models\BillingData;
use App\Models\PaymentSuppliers;
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
            return response()->json(['error' => 'No hay facturas válidas con estado en 0.'], 422);
        }
    
        // Actualizar el estado de las facturas a PAGADA
        BillingData::whereIn('id', $validBillings)->update(['payment' => 1]);
    
        // Obtener órdenes de pago relacionadas con las facturas enviadas
        $paymentOrderIds = BillingData::whereIn('id', $validBillings)
            ->pluck('id_paymentOrder')
            ->unique()
            ->toArray();
    
        $ordersToUpdate = [];
    
        // Verificar si todas las facturas de cada orden de pago están pagadas
        foreach ($paymentOrderIds as $paymentOrderId) {
            $pendingBillings = BillingData::where('id_paymentOrder', $paymentOrderId)
                ->where('payment', 0)
                ->exists();
    
            if (!$pendingBillings) {
                $ordersToUpdate[] = $paymentOrderId;
            }
        }
    
        // Actualizar las órdenes de pago a `PAGADA` si todas sus facturas están pagadas
        if (!empty($ordersToUpdate)) {
            DB::table('payment_orders')
                ->whereIn('id', $ordersToUpdate)
                ->update(['status' => 'PAGADA']);
        }
    
        // Crear el registro de pago
        $payment = PaymentSuppliers::create($validated);
    
        if ($payment) {
            return response()->json(['message' => 'Pago actualizado correctamente'], 200);
        }
    
        return response()->json(['error' => 'Error al crear el pago'], 500);
    }
    
}
