<?php

namespace App\Http\Controllers;

use App\Models\BillingData;
use App\Models\PaymentOrder;
use App\Models\PaymentSuppliers;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
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
        // Convertir la cadena de órdenes en un array
        $billingIds = explode(',', $request->input('billings'));

        // Validar los datos principales
        $validated = $request->validate([
            'supplier' => 'required|integer|exists:suppliers,id',
            'billings' => 'required|string',  
            'quality' => 'required|numeric',  
            'user' => 'required|exists:users,id',
            'date' => 'required|date',
        ]);

        // Validar manualmente que cada ID de factura exista en BillingData
        $billingIds = array_map('trim', $billingIds); // Asegurarse de quitar espacios en blanco
        $validBillings = BillingData::whereIn('id', $billingIds)->pluck('id')->toArray();

        if (count($validBillings) !== count($billingIds)) {
            return response()->json(['error' => 'Algunas facturas no existen.'], 422);
        }

        // Actualizar el estado de cada factura a PAGADA == 1
        BillingData::whereIn('id', $billingIds)->update(['payment' => 1]);


        // Get related purchase order IDs
        $purchaseOrderIds = BillingData::whereIn('id', $billingIds)
            ->pluck('id_order')
            ->unique();

         // Para cada OC, verificamos si todas sus facturas están pagadas y actualizamos el estado de la OP
         foreach ($purchaseOrderIds as $purchaseOrderId) {
            $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
            

            if ($purchaseOrder) {
                // Verificar si la OC pertenece a una OP y si todas las facturas están pagadas
                $paymentOrder = PaymentOrder::where('orders', 'LIKE', '%' . $purchaseOrderId . '%')->first();

                if ($paymentOrder) {                    

                    // Consulta con inner join para verificar si existen facturas con payment == 0
                    $unpaidBillingsExist = DB::table('purchase_orders')
                        ->join('billing_data', 'purchase_orders.id', '=', 'billing_data.id_order')
                        ->whereIn('purchase_orders.id', explode(',', $paymentOrder->orders))
                        ->where('billing_data.payment', 0)
                        ->exists();
                        
                    if (!$unpaidBillingsExist) {
                        // Si todas las facturas están pagadas, cambiar el estado de la OP a 'PAGADA'
                        $paymentOrder->update(['status' => 'PAGADA']);
                    }
                }
            }
        }

        // Crear la nuevo de pago
        $payment = PaymentSuppliers::create($validated);
        
        if ($payment) {
            return response()->json(['message' => 'Pago actualizado correctamente'], 200);
        } else {
            return response()->json(['error' => 'Error al crear el pago'], 500);
        }
    }
}
