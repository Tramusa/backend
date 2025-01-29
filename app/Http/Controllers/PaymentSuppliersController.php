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
        // Obtener todos los pagos que pertenecen al proveedor con el id proporcionado
        $payments = PaymentSuppliers::where('supplier', $id)->get();

        // Retornar los pagos en formato JSON
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
        $validBillings = BillingData::whereIn('id', $billingIds)->pluck('id')->toArray();
    
        if (count($validBillings) !== count($billingIds)) {
            return response()->json(['error' => 'Algunas facturas no existen.'], 422);
        }
    
        // Actualizar el estado de cada factura a PAGADA
        BillingData::whereIn('id', $billingIds)->update(['payment' => 1]);

        // Obtener los IDs únicos de las órdenes de compra relacionadas
        $purchaseOrderIds = BillingData::whereIn('id', $billingIds)
            ->pluck('id_order')
            ->unique();

    
        foreach ($purchaseOrderIds as $purchaseOrderId) {
            // Verificar si la orden de compra pertenece a una orden de pago
            $paymentOrder = PaymentOrder::whereRaw("REPLACE(orders, ' ', '') LIKE ?", ['%' . $purchaseOrderId . '%'])->first();

            if ($paymentOrder) {
                // Verificar si existen facturas no pagadas en las órdenes de compra relacionadas
                $unpaidBillingsExist = BillingData::whereIn('id_order', explode(',', $paymentOrder->orders))
                    ->where('payment', 0)
                    ->where('id_supplier', $paymentOrder->supplier) // Verificar el proveedor
                    ->exists();
    
                // Actualizar estado de la orden de pago si todas las facturas están pagadas
                if (!$unpaidBillingsExist) {
                    $paymentOrder->update(['status' => 'PAGADA']);
                }
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
