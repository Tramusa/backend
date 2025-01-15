<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;

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
                ->get(); // Sin cargar las relaciones de forma anticipada con 'with()'
    
            // Si el proveedor tiene órdenes aprobadas
            if ($approvedPayments->isNotEmpty()) {
                // Cargar las órdenes de compra manualmente
                $approvedPayments->each(function ($paymentData) {
                    $paymentData->purchaseOrders = $paymentData->purchaseOrders()->map(function ($purchaseOrder) {
                        // Factura (billing)
                        $billing = $purchaseOrder->billing(); // Aquí obtenemos la factura
                        $purchaseOrder->billing = $billing ?: ''; // Asignamos la factura al objeto de la orden de compra
                        return $purchaseOrder;
                    });
                });
    
                // Filtrar las órdenes de compra con facturas no pagadas
                $unpaidOrders = $approvedPayments->flatMap(function ($payment) {
                    return $payment->purchaseOrders->filter(function ($purchaseOrder) {
                        return $purchaseOrder->billing && $purchaseOrder->billing->payment == 0;
                    });
                });             

                // Calcular el total de las facturas no pagadas
                $totalAmount = $unpaidOrders->sum(function ($order) {
                    return $order->total ?? 0;
                });
    
                // Agregar los datos al balance
                $balance[] = [
                    'supplier' => $supplier,
                    'total_payments' => $totalAmount,
                    'bank_details' => $supplier->bankDetails->isNotEmpty() 
                        ? $supplier->bankDetails->map(function ($bank) {
                            // Verificar si existen detalles bancarios y asignar valores en consecuencia
                            return [
                                'bank' => isset($bank->banck) ? $bank->banck : '',  // Si no existe, asigna una cadena vacía
                                'account' => isset($bank->account) ? $bank->account : '',  // Si no existe, asigna una cadena vacía
                                'clabe' => isset($bank->clabe) ? $bank->clabe : '',  // Si no existe, asigna una cadena vacía
                            ];
                        }) 
                        : [['bank' => '', 'account' => '', 'clabe' => '']],  // Si no tiene detalles bancarios, asigna valores vacíos
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
                 // Cargar las órdenes de compra manualmente
                 $approvedPayments->each(function ($paymentData) {
                    $paymentData->purchaseOrders = $paymentData->purchaseOrders()->map(function ($purchaseOrder) {
                        // Factura (billing)
                        $billing = $purchaseOrder->billing(); // Aquí obtenemos la factura
                        $purchaseOrder->billing = $billing ?: ''; // Asignamos la factura al objeto de la orden de compra
                        return $purchaseOrder;
                    });
                });
                
                // Filter purchase orders with unpaid invoices (where payment == 0)
                $orders = $approvedPayments->flatMap(function ($paymentOrder) {
                    return collect($paymentOrder['purchaseOrders'])->filter(function ($purchaseOrder) {
                        return $purchaseOrder->billing && $purchaseOrder->billing->payment == 0;
                    });
                });

                // Calculate the total for unpaid invoices only
                $totalAmount = $orders->sum(function ($item) {
                    return $item->total ?? 0;
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

}
