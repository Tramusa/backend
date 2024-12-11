<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class BalanceSuppliersController extends Controller
{
    public function index()
    {
        $balance = []; // Array donde se almacenarán los datos de proveedores con órdenes aprobadas

        // Recorremos todos los proveedores
        // Obtener todos los proveedores junto con sus detalles bancarios
        $suppliers = Suppliers::with('bankDetails')->get();

        foreach ($suppliers as $supplier) {
            // Filtramos las órdenes de pago aprobadas para cada proveedor
            $approvedPayments = PaymentOrder::where('supplier', $supplier->id)
                ->where('status', 'APROBADA')
                ->get();

            // Si el proveedor tiene órdenes aprobadas, calculamos el total
            if ($approvedPayments->isNotEmpty()) {
                $approvedPayments = $approvedPayments->map(function ($payment) {
                    return [
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

                // Agregamos al array balance los datos del proveedor y el total
                $balance[] = [
                    'supplier' => $supplier,
                    'total_payments' => $totalAmount,
                    'bank_details' => $supplier->bankDetails->map(function ($bank) {
                        return [
                            'banck' => $bank->banck,
                            'account' => $bank->account,
                            'clabe' => $bank->clabe,
                        ];
                    }),
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
