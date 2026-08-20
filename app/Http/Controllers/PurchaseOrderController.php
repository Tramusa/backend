<?php

namespace App\Http\Controllers;

use App\Models\BillingData;
use App\Models\DetailsRequisitions;
use App\Models\PurchaseOrder;
use App\Models\Requisitions;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el estado de las ordenes de compra desde la solicitud, si existe
        $status = $request->query('status');
        $supplierId = $request->query('supplier');

        // Construir la consulta
        $query = PurchaseOrder::query();

        if ($supplierId) {
            $query->where('id_supplier', $supplierId);
        }

        // Filtrar por estado si se proporciona
        if ($status) {
            $query->where('status', $status);
        }

        // Filtrar las ordenes de compra con id diferente de 0 y cargar las relaciones necesarias
        $purchaseOrders = $query->with([
                'requisition' => function ($query) {
                    $query->with(['work_areaInfo', 'collaboratorInfo']);
                },
                'supplier',
                'performInfo',
                'authorizeInfo'
            ])->get();

        // Recorrer cada orden de compra para agregar la URL del comprobante y la factura
        foreach ($purchaseOrders as $order) {
            // Comprobante
            if ($order->requisition && $order->requisition->comprobante) {
                $order->requisition->comprobante_url =  asset(Storage::url($order->requisition->comprobante));
            } else {
                $order->requisition->comprobante_url = null;
            }

            // Factura (billing)
            $billing = $order->billing(); // Aquí obtenemos la factura
            if ($billing) {
                $order->billing = $billing; // Añadimos la información de la factura a la orden
            }
        }

        // Devolver la respuesta JSON con las ordenes de compra filtradas
        return response()->json($purchaseOrders);
    }
    
    public function store(Request $request)
    {
        // ============= VALIDACIONES ====================
        $request->validate([
            'id_requisition'      => 'required|integer',
            'date_order'          => 'required|date',
            'id_supplier'         => 'required|integer',
            'total'               => 'required|numeric',
            'Billing.folio'       => 'required|string|max:255',
            'Billing.date'        => 'required|date',
            'products'            => 'required|array|min:1',
        ], [
            'Billing.folio.required' => 'El folio de la factura es obligatorio.',
            'Billing.date.required'  => 'La fecha de la factura es obligatoria.',
            'products.required'      => 'La orden debe tener al menos un producto.',
            'products.min'            => 'La orden debe tener al menos un producto.',
        ]);

        try {

            return DB::transaction(function () use ($request) {
                // =========== DATOS ===================
                $orderData = $request->only(
                    'id_requisition',
                    'date_order',
                    'id_supplier',
                    'additional',
                    'total'
                );
                $user = Auth::user();
                $orderData['perform'] = $user->id;
                $billingData = $request->input('Billing');
                // ============== BLOQUEAR LA REQUISICIÓN ==================
                $requisition = Requisitions::where('id', $orderData['id_requisition'])
                ->lockForUpdate()
                ->first();

                if (!$requisition) {
                    throw new \Exception('La requisición no existe.');
                }

                // =============== VERIFICAR QUE NO TENGA YA UNA ORDEN ======================
                $existingPurchaseOrder = PurchaseOrder::where('id_requisition', $orderData['id_requisition'])
                ->where('status', '<>', 'CANCELADA')
                ->lockForUpdate()
                ->first();

                if ($existingPurchaseOrder) {
                    return response()->json([
                        'error' =>
                            'Esta requisición ya tiene una orden de compra registrada. ' .
                            'No se puede generar otra.'
                    ], 409);
                }

                // ================== VERIFICAR FACTURA =======================
                $folio = trim($billingData['folio'] ?? '');

                if ($folio === '') {
                    throw new \Exception('El folio de la factura es obligatorio.');
                }

                // =================  BUSCAR FACTURA EXISTENTE ========================
                $billing = BillingData::where('folio', $folio)
                    ->where('id_supplier', $orderData['id_supplier'])
                    ->whereNull('id_paymentOrder')
                    ->where('payment', 0)
                    ->lockForUpdate()
                    ->first();

                // =============== CREAR LA ORDEN =========================
                $order = PurchaseOrder::create($orderData);
                if (!$order) {
                    throw new \Exception('No fue posible crear la orden de compra.'                    );
                }

                // ===================  REGISTRAR FACTURA =======================
                if ($billing) {
                    $orderIds = array_filter(explode(',', $billing->id_order));

                    if (!in_array(
                        (string) $order->id,
                        array_map('strval', $orderIds),
                        true
                    )) {
                        $orderIds[] = $order->id;
                        $billing->id_order = implode(',', $orderIds);
                        if (!$billing->save()) {
                            throw new \Exception('No fue posible actualizar el registro de la factura.');
                        }
                    }
                } else {
                    $billingData['id_order'] = $order->id;
                    $billingData['id_supplier'] = $orderData['id_supplier'];
                    $newBilling = BillingData::create($billingData);
                    if (!$newBilling) {
                        throw new \Exception('No fue posible registrar la factura.');
                    }
                }

                // =================  ACTUALIZAR REQUISICIÓN ======================
                $requisition->status = 'ORDEN COMPRA';
                $requisition->date_atended = now();
                $requisition->analyze = $user->id;
                if (!$requisition->save()) {
                    throw new \Exception('No fue posible actualizar la requisición.');
                }

                // ================== GENERAR PDF =====================
                return $this->generarPDF($order->id);
            });

        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e;

        } catch (\Throwable $e) {

            \Log::error(
                'Error al crear orden de compra',
                [
                    'user_id' => Auth::id(),
                    'requisition_id' => $request->id_requisition,
                    'supplier_id' => $request->id_supplier,
                    'folio' => $request->input('Billing.folio'),
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json(['error' => 'No se pudo crear la orden de compra. ' .$e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    { 
        PurchaseOrder::find($id)->update($request->all());//ACTUALIZAMOS LA INFO
        
        return response()->json(['message' => 'actualizado exitosamente.']);
    }

    public function generarPDF($order){
        $pdfContent = $this->PDF($order);
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');// Devolver el contenido del PDF
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }

    private function PDF($order)
    {       
        $orderData = PurchaseOrder::where('id', $order)
                ->with([
                    'requisition.products',
                    'requisition.work_areaInfo',
                    'requisition.collaboratorInfo',
                    'requisition.parent_accountInfo',
                    'requisition.title_accountInfo',
                    'requisition.subtitle_accountInfo',
                    'requisition.mayor_accountInfo',
                    'requisition.user_authorized',
                    'supplier',
                    'performInfo',
                    'authorizeInfo',
                ])
                ->first();
          
        $isMultiservicios =
            trim($orderData->requisition->company_name ?? '') ===
            'Multiservicios Murillo SA de CV';

        if ($isMultiservicios) {
            $logoImagePath = public_path('imgPDF/logo_multiservicios.png');

            $view = 'F-04-02 MMU ORDEN DE COMPRA';
        } else {
            $logoImagePath = public_path('imgPDF/logo.png');

            $view = 'F-04-03 R4 ORDEN DE COMPRA';
        }

        $logoImage = $this->getImageBase64($logoImagePath);
        
        $data = [
            'logoImage' => $logoImage,
            'Data' => $orderData,
            'fecha' => Carbon::parse($orderData->date_order)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
        ];

        $html = view($view, $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();                     
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:APROBADA,CANCELADA,PENDIENTE',
        ]);
        // Verifica si el usuario está autenticado
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        $order = PurchaseOrder::findOrFail($id);
        $order->status = $request->input('status');
        $order->authorize = $request->input('status') === 'APROBADA' ? $user->id : null;
        $order->cancel_reason = $request->input('reason');

        // Si el estado es CANCELADA, actualiza también el estado de la requisición relacionada
        if ($request->input('status') === 'CANCELADA') {
            $order->authorize = null; // Borra cualquier autorización anterior

            // Encuentra la requisición relacionada con esta orden
            $requisition = Requisitions::where('id', $order->id_requisition)->first();
            
            // Si existe la requisición, actualiza su estado
            if ($requisition) {
                $requisition->status = 'PENDIENTE';
                $requisition->save();
            }
             // Buscar facturas que contengan esta orden en id_order
            $billings = BillingData::where('id_order', 'LIKE', "%{$id}%")->get();

            foreach ($billings as $billing) {
                $orderIds = explode(',', $billing->id_order);
                
                // Eliminar la orden cancelada del array
                $orderIds = array_filter($orderIds, fn($orderId) => $orderId != $id);

                if (empty($orderIds)) {
                    // Si no quedan órdenes, eliminar la factura
                    $billing->delete();
                } else {
                    // Si quedan órdenes, actualizar la factura
                    $billing->id_order = implode(',', $orderIds);
                    $billing->save();
                }
            }
        }elseif ($request->input('status') === 'PENDIENTE'){
            $order->authorize = null; // Borra cualquier autorización anterior
        }

        $order->save();

        return response()->json(['message' => 'Estatus actualizado correctamente']);
    }

    public function updatePdf(Request $request)
    {
        // Validar que la requisición existe
        $request->validate([
            'requisitionId' => 'required|integer|exists:requisitions,id',
        ]);

        // Validar archivo PDF si se proporciona
        $file = $request->file('file');
        if ($file) {
            $request->validate([
                'file' => 'file|mimes:pdf|max:2048',
            ]);
        }

        // Obtener la requisición
        $requisition = Requisitions::findOrFail($request->input('requisitionId'));

        // Si se proporciona un archivo PDF
        if ($file) {
            // Eliminar el archivo anterior si existe
            if ($requisition->comprobante) {
                Storage::delete($requisition->comprobante);
            }

            // Guardar el nuevo archivo PDF
            $path = $file->store('Comprobantes', 'public');
            $requisition->comprobante = $path;
            $requisition->save(); // Guardar cambios en la requisición
        }

        // Validar los datos de Billing (folio, fecha, forma de pago, etc.)
        $billingData = $request->only(['folio', 'date', 'payment_form', 'payment_method']);
        
        if (!empty($billingData)) {
            $request->validate([
                'folio' => 'nullable|string|max:255',
                'date' => 'nullable|date',
                'payment_form' => 'nullable|string|max:255',
                'payment_method' => 'nullable|string|max:255',
            ]);

            // Obtener los datos de BillingData
            $billing = BillingData::where('id', $request->id)->first();

            // Verificar si se encontró el BillingData con el ID proporcionado
            if ($billing) {
                // Actualizar los campos de BillingData
                $billing->update([
                    'folio' => $billingData['folio'] ?? $billing->folio,
                    'date' => $billingData['date'] ?? $billing->date,
                    'payment_form' => $billingData['payment_form'] ?? $billing->payment_form,
                    'payment_method' => $billingData['payment_method'] ?? $billing->payment_method,
                ]);
            } else {
                // Si no se encuentra el BillingData con el ID, podemos devolver un error
                return response()->json(['message' => 'No se encontraron datos de facturación con ese ID'], 404);
            }
        }

        return response()->json(['message' => 'PDF y/o datos de facturación actualizados exitosamente']);
    }

}
