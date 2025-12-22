<?php

namespace App\Http\Controllers;

use App\Models\Earrings;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\Revisions;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
                $earring->status = 2; // En proceso
                $earring->save();
            }
        }
        return response()->json(['message' => 'Order creada correctamente', 'order_id' => $orderId]);
    }

    public function show($type)
    {
        $orders = DB::table('orders')
                    ->where('status', $type)
                    ->get();

        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($orders as $order) {
            $detail = DB::table('order_details')
                        ->join('earrings', 'order_details.id_earring', '=', 'earrings.id')
                        ->where('order_details.id_order', $order->id)
                        ->select('earrings.type', 'earrings.unit')
                        ->first();

            if ($detail) {
                $tableName = $tablas[$detail->type];
                $unit = DB::table($tableName)
                            ->where('id', $detail->unit)
                            ->first();

                // Asegúrate de acceder a la propiedad como objeto
                if ($unit) {
                    $order->no_economic = $unit->no_economic;
                } else {
                    $order->no_economic = null; // o algún valor por defecto
                }
            }
        }    

        return response()->json($orders);
    }

    public function bitacora(Request $request)
    {   /*
        |--------------------------------------------------------------------------
        | QUERY BASE
        |--------------------------------------------------------------------------
        */
        $query = DB::table('orders')
            ->join('users', 'orders.operator', '=', 'users.id')
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.id_order')
            ->leftJoin('earrings', 'order_details.id_earring', '=', 'earrings.id')
            ->leftJoin('revisions', 'earrings.fm', '=', 'revisions.id')
            ->leftJoin('units_all as ua', function ($join) {
                $join->on('ua.unit_id', '=', 'earrings.unit')
                    ->on('ua.type', '=', 'earrings.type');
            })
            ->where('orders.status', 4)
            ->selectRaw("
                orders.id,
                orders.date_attended,
                orders.date_in,
                MAX(orders.repair) AS repair,
                users.name,
                users.a_paterno,
                users.a_materno,
                MAX(earrings.type_mtto) AS type_mtto,
                MAX(revisions.odometro) AS odometro,
                MAX(ua.no_economic) AS unidad,
                SUM(orders.total_mano) AS total_mano,
                SUM(orders.total_parts) AS total_parts,
                GROUP_CONCAT(DISTINCT earrings.description SEPARATOR ', ') AS fallas
            ")
            ->groupBy(
                'orders.id',
                'orders.date_attended',
                'orders.date_in',
                'users.name',
                'users.a_paterno',
                'users.a_materno'
            );


        /*
        |--------------------------------------------------------------------------
        | FILTROS POR COLUMNA
        |--------------------------------------------------------------------------
        */

        // N°
        if ($request->filled('id')) {
            $query->where('orders.id', 'like', "%{$request->id}%");
        }

        // Fecha Finalizado
        if ($request->filled('date')) {
            $query->where('orders.date_attended', 'like', "%{$request->date}%");
        }

        // Unidad
        if ($request->filled('unit')) {
            $query->where('ua.no_economic', 'like', "%{$request->unit}%");
        }

        // Operador
        if ($request->filled('operator')) {
            $query->whereRaw(
                "LOWER(CONCAT(users.name,' ',users.a_paterno,' ',users.a_materno)) LIKE ?",
                ['%' . strtolower($request->operator) . '%']
            );
        }

        // Tipo Mtto
        if ($request->filled('type')) {
            $query->where('earrings.type_mtto', 'like', "%{$request->type}%");
        }

        // Falla
        if ($request->filled('falla')) {
            $query->having('fallas', 'like', "%{$request->falla}%");
        }

        // Descripción / Repair
        if ($request->filled('descripcion')) {
            $query->where('orders.repair', 'like', '%' . $request->descripcion . '%');
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINACIÓN
        |--------------------------------------------------------------------------
        */
        $orders = $query
            ->orderBy('orders.date_attended', 'desc')
            ->paginate(30);

        return response()->json($orders);
    }

    public function showOrder($id)
    {
        // Fetch the order
        $order = Orders::find($id);
        
        // Check if the order exists
        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        // Fetch the operator details from users table
        $operator = User::find($order->operator);

        // Add operator_name if operator exists
        $order->operator_name = $operator ? $operator->name . ' ' . $operator->a_paterno : 'Operador no encontrado';

        // Return the order with operator_name
        return response()->json($order);
    }

    public function orderEarrings($id)
    {
        $earringsInfo = DB::table('order_details')
            ->join('earrings', 'order_details.id_earring', '=', 'earrings.id')
            ->where('order_details.id_order', '=', $id) // Cambio aquí
            ->select('earrings.*')
            ->get();

        if (!$earringsInfo) {            
            return response()->json(['message' => 'Pendientes no encontrados'], 404);
        }else{
            $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

            foreach ($earringsInfo as $earring) {
                $id_unit = $earring->unit;
                
                $unit = DB::table($tablas[$earring->type])->select('no_economic')->where('id', $id_unit)->first();
                $earring->unit = $unit->no_economic;
                
                $earring->type = $tablas[$earring->type];
            }
            return response()->json($earringsInfo);
        }   
    }

    public function update(Request $request, $id)
    {
        $order = Orders::find($id);
        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }
       
        $order->status = $request->input('data.status'); // Actualizar el estado de la orden

        // Verificar el status y actualizar campos correspondientes
        if ($order->status == 0) {
            $order->fill($request->input('data.form')); // Rellenar con datos del formulario
            $order->status = 3; // Cambiar status a 3
        } else if ($order->status == 2) {
            $order->authorize = Auth::id(); // ID del usuario logueado
        } else if ($order->status == 3) {
            $order->date_in = now(); // Fecha y hora actual
        } else if ($order->status == 4){
            $order->repair = $request->input('data.form.repair');
            $order->spare_parts = $request->input('data.form.spare_parts');
            $order->total_parts = $request->input('data.form.total_parts');
            $order->total_mano = $request->input('data.form.total_mano');
            $order->operator = $request->input('data.form.operator');
            $order->date_attended = now();
            $order->perform = Auth::id();
            // 🔥 FINALIZAR FALLAS
            $earringsIds = OrderDetail::where('id_order', $order->id)
                ->pluck('id_earring');

            Earrings::whereIn('id', $earringsIds)
                ->update(['status' => 0]); // Finalizadas
        }

        try {
            $order->save();
            return response()->json(['message' => 'Orden actualizada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar la orden: ' . $e->getMessage()], 500);
        }
    }

    private function PDF($order)
    {
        $orderData = Orders::find($order);//PRIMERO SACAMOS LA INFO DE LA ORDEN
        $operator = User::where('id', $orderData->operator)->first();        
        $autorizo = User::where('id', $orderData->authorize)->first();        
        $realizo = User::where('id', $orderData->perform)->first();     

        $autorizoFirma = ($autorizo && $autorizo->signature)
            ? $this->getImageBase64($autorizo->signature)
            : null;

        $realizoFirma = ($realizo && $realizo->signature)
            ? $this->getImageBase64($realizo->signature)
            : null;
           
        $earringsInfo = DB::table('order_details')
            ->join('earrings', 'order_details.id_earring', '=', 'earrings.id')
            ->where('order_details.id_order', '=', $order)
            ->select('earrings.*')
            ->get(); // DETALLES DE ORDEN

        if (!$earringsInfo->isEmpty()) {
            $firstEarring = $earringsInfo->first();
            $id_unit = $firstEarring->unit; // Extract unit
            $type = $firstEarring->type; // Extract type
            $fm = $firstEarring->fm; // Extract type

            $fallas = '';
            $numError = 1;
            foreach ($earringsInfo as $earring) {
                $fallas .= $numError . ".- " . $earring->description . "<br>";
                $numError++;
            }
        } else {
            $id_unit = null;
            $type = null;
            $fm = null;
            $fallas = 'No hay fallas en esta orden.';
        }

        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        $unit = DB::table($tablas[$type]) 
                ->where('id', $id_unit)
                ->first(); // Obtener el primer resultado

        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64
 
        $data = [
            'logoImage' => $logoImage,
            'orderData' => $orderData,
            'unit' => $unit,
            'fm' => $fm,
            'fallas' => $fallas,
            'operator' => $operator,
            'autorizo' => $autorizo,
            'realizo' => $realizo,

            // FIRMAS
            'autorizoFirma' => $autorizoFirma,
            'realizoFirma'  => $realizoFirma,
        ];

        $html = view('F-05-01-R2 ORDEN DE SERVICIO', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();                     
    }

    public function generarPDF($order)
    {
        $pdfContent = $this->PDF($order);

        Storage::disk('public')->put('orders/Orden N°'. ($order) . '.pdf', $pdfContent);

        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');// Devolver el contenido del PDF
    }

    private function getImageBase64($path)
    {
        // 1️⃣ Si viene una ruta absoluta (public_path)
        if (file_exists($path)) {
            $file = file_get_contents($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        // 2️⃣ Si viene una ruta de Storage (public/...)
        if (Storage::exists($path)) {
            $file = Storage::get($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        return null;
    }

    public function cancel($orderId)
    {
        $order = Orders::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        $earringsInfo = OrderDetail::where('id_order', $orderId)->get();

        if ($earringsInfo->isEmpty()) {            
            return response()->json(['message' => 'Pendientes no encontrados'], 404);
        }

        try {
            foreach ($earringsInfo as $earring) {
                Earrings::where('id', $earring->id_earring)->update(['status' => 1]);
            }

            $order->status = 0; // Actualizar el estado de la orden
            $order->save();

            return response()->json(['message' => 'Orden cancelada correctamente', 'total_earrings' => $earringsInfo->count()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al procesar la cancelación'], 500);
        }
    }
    
    public function delete($id)
    {
        $earring = OrderDetail::where('id_earring', $id)->first();
    
        if (!$earring) {
            return response()->json(['message' => 'Pendiente no encontrado'], 404);
        }        
    
        $earring->delete();

        $earring = Earrings::find($id);
        if ($earring) {            
            $earring->status = 1;// Update the status of the earring to '0'
            $earring->save();
        }
    
        return response()->json(['message' => 'Pendiente eliminado correctamente']);
    }

    public function generatePDFfilter(Request $request)
    {
        try {
            $filteredOrders = $request->input('filteredOrders');
            if (empty($filteredOrders)) {
                return response()->json(['error' => 'No orders selected'], 400);
            }

            $orders = DB::table('orders')
                ->join('users', 'orders.operator', '=', 'users.id')
                ->whereIn('orders.id', $filteredOrders)
                ->where('orders.status', 4)
                ->select('orders.*', 'users.name', 'users.a_paterno', 'users.a_materno')
                ->get();

            if ($orders->isEmpty()) {
                return response()->json(['message' => 'Órdenes no encontradas'], 404);
            } else {
                $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

                foreach ($orders as $order) {
                    $fallas = DB::table('order_details')
                        ->join('earrings', 'order_details.id_earring', '=', 'earrings.id')
                        ->where('order_details.id_order', $order->id)
                        ->select('earrings.description') // Selecciona solo la descripción de la falla
                        ->pluck('description'); // Pluck se utiliza para obtener una lista de valores

                    $order->fallas = $fallas;

                    $firstEarring = DB::table('order_details')
                        ->join('earrings', 'order_details.id_earring', '=', 'earrings.id')
                        ->where('order_details.id_order', $order->id)
                        ->select('earrings.type', 'earrings.unit', 'earrings.type_mtto', 'earrings.fm' )
                        ->first();

                    if ($firstEarring) {                    
                        if (isset($firstEarring->type) && isset($firstEarring->unit)) {
                            $id_unit = $firstEarring->unit;
                            $unit = DB::table($tablas[$firstEarring->type])->select('no_economic')->where('id', $id_unit)->first();
                            $order->no_economic = $unit->no_economic? $unit->no_economic: 'N/A';
                        }

                        $order->type_mtto = $firstEarring->type_mtto;

                        if ($firstEarring->fm != 0) {
                            $revisions = Revisions::where('id', $firstEarring->fm)->first();
                            if ($revisions) {
                                $order->odometro = $revisions->odometro ? $revisions->odometro : 'N/A';
                            }
                        }
                    }
                     // Calcular la diferencia de tiempo en minutos
                     $dateAttended = Carbon::parse($order->date_attended);
                     $dateIn = Carbon::parse($order->date_in);
                     $order->time = $dateAttended->diffInMinutes($dateIn);
                     
                     // 👇 AÑADE ESTO
                     $order->week = $dateAttended->weekOfYear;
                }

                $logoImagePath = public_path('imgPDF/logo.png');
                $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64
 

                $html = view('pdf.orders', [
                    'orders' => $orders,
                    'logoImage' => $logoImage
                ])->render();

                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();

                $pdfContent = $dompdf->output();

                Storage::disk('public')->put('orders/Filter.pdf', $pdfContent);

                return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
            }
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Error generating PDF'], 500);
        }
    }
}