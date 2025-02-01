<?php

namespace App\Http\Controllers;

use App\Models\BillingData;
use App\Models\PaymentOrder;
use App\Models\PaymentSuppliers;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;

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
    
        // Obtener solo las facturas con `payment == 1`
        $validBillings = BillingData::whereIn('id', $billingIds)
            ->where('payment', 0)
            ->pluck('id')
            ->toArray();
    
        if (empty($validBillings)) {
            return response()->json(['error' => 'No hay facturas válidas con estado en 1.'], 422);
        }
    
        // Actualizar el estado de las facturas a PAGADA
        BillingData::whereIn('id', $validBillings)->update(['payment' => 1]);
    
        // Obtener los IDs únicos de las órdenes de compra relacionadas
        $purchaseOrderIds = BillingData::whereIn('id', $validBillings)
            ->pluck('id_order')
            ->unique()
            ->toArray();
    
        //logger('Valores de purchaseOrderIds:', $purchaseOrderIds);
    
        // Asegurar que los valores sean un array de enteros (por si vienen como string con comas)
        $cleanedOrderIds = [];
        foreach ($purchaseOrderIds as $orderId) {
            if (str_contains($orderId, ',')) {
                $cleanedOrderIds = array_merge($cleanedOrderIds, array_map('trim', explode(',', $orderId)));
            } else {
                $cleanedOrderIds[] = $orderId;
            }
        }
    
        // Verificar si las órdenes de compra están en órdenes de pago y actualizar su estado
        foreach ($cleanedOrderIds as $purchaseOrderId) {
            $paymentOrder = PaymentOrder::whereRaw("FIND_IN_SET(?, REPLACE(orders, ' ', ''))", [$purchaseOrderId])
                ->where('supplier', $request->supplier)
                ->first();
    
            if ($paymentOrder) {
                //logger('Orden de pago encontrada:', [$paymentOrder]);
    
                // Verificar si existen facturas no pagadas en las órdenes de compra relacionadas
                $unpaidBillingsExist = BillingData::whereIn('id_order', explode(',', str_replace(' ', '', $paymentOrder->orders)))
                    ->where('payment', 0)
                    ->where('id_supplier', $paymentOrder->supplier)
                    ->exists();
    
                // Si todas las facturas están pagadas, marcar la orden de pago como PAGADA
                if (!$unpaidBillingsExist) {
                    //logger('ENTRA - DEBERIA ESTAR PAGADA');
                    $paymentOrder->update(['status' => 'PAGADA']);
                }
            } else {
                //logger("No se encontró una orden de pago para el ID: $purchaseOrderId");
            }
        }
    
        // Crear el registro de pago
        $payment = PaymentSuppliers::create($validated);
    
        if ($payment) {
            return response()->json(['message' => 'Pago actualizado correctamente'], 200);
        }
    
        return response()->json(['error' => 'Error al crear el pago'], 500);
    }
    

    
}
