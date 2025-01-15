<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\Suppliers;
use Illuminate\Http\Request;

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
                    return $order->billing->total ?? 0;
                });
    
                // Agregar los datos al balance
$balance[] = [
    'supplier' => $supplier,
    'total_payments' => $totalAmount,
    'bank_details' => $supplier->bankDetails->isNotEmpty() ? $supplier->bankDetails->map(function ($bank) {
        return [
            'bank' => $bank->banck ?? 'N/A',   // Si 'banck' es nulo, se usa 'N/A'
            'account' => $bank->account ?? 'N/A', // Si 'account' es nulo, se usa 'N/A'
            'clabe' => $bank->clabe ?? 'N/A',   // Si 'clabe' es nulo, se usa 'N/A'
        ];
    }) : [], // Si no tiene detalles bancarios, devuelve un array vacío
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
                // Load purchase orders for each approved payment order
                $approvedPayments = $approvedPayments->map(function ($payment) {
                    return [
                        'payment' => $payment,
                        'purchaseOrders' => $payment->purchaseOrders(), // Load associated purchase orders
                    ];
                })->collect(); // Convert to a collection to use collection methods

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
