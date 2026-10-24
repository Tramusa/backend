<?php

namespace App\Http\Controllers;

use App\Models\Earrings;
use App\Models\OrderDetail;
use App\Models\Orders;
use App\Models\ProgramsMttoVehicleSchedule;
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
        $selectedEarrings = $request->input('selectedEarrings');

        if (!$selectedEarrings || count($selectedEarrings) === 0) {
            return response()->json(['message' => 'No se seleccionaron fallas'], 400);
        }

        $earrings = Earrings::whereIn('id', $selectedEarrings)->get();

        if ($earrings->count() !== count($selectedEarrings)) {
            return response()->json(['message' => 'Una o más fallas no existen'], 404);
        }

        // 🔒 Tomar unidad, type y type_mtto base
        $baseUnit = $earrings->first()->unit;
        $baseType = $earrings->first()->type;
        $baseMtto = $earrings->first()->type_mtto;

        // 🔎 Verificar que TODAS tengan mismo unit, type y type_mtto
        $invalidMix = $earrings->contains(function ($earring) use ($baseUnit, $baseType, $baseMtto) {
            return $earring->unit !== $baseUnit || $earring->type !== $baseType
                || $earring->type_mtto !== $baseMtto;
        });

        if ($invalidMix) {
            return response()->json(['message' => 'No se pueden mezclar fallas de diferentes unidades o mantenimientos en una misma orden'], 409);
        }

        // 🔒 Validar que ninguna esté en proceso
        if ($earrings->where('status', 2)->count() > 0) {
            return response()->json(['message' => 'Una o más fallas ya están en proceso'], 409);
        }

        // ========================= CREAR ORDEN ========================
        DB::beginTransaction();

        try {
            $order = Orders::create(['date' => now(), 'created_by' => auth()->id(),]);
            // ==========================================================
            // CREAR DETALLES + CAMBIAR FALLAS A EN PROCESO
            // ==========================================================
            foreach ($earrings as $earring) {
                OrderDetail::create(['id_order' => $order->id, 'id_earring' => $earring->id]);
                // 🔵 Falla -> EN PROCESO
                $earring->update(['status' => 2]);
            }

            // ==========================================================
            // ACTUALIZAR CRONOGRAMA -> PROCESS
            // ==========================================================
            // Obtener los schedule_id de las fallas seleccionadas
            $scheduleIds = $earrings
                ->pluck('schedule_id')
                ->filter()
                ->unique()
                ->values();

            if ($scheduleIds->isNotEmpty()) {
                ProgramsMttoVehicleSchedule::whereIn('id', $scheduleIds)->update(['status' => 'process']);
            }

            DB::commit();
            return response()->json(['message' => 'Orden creada correctamente', 'order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear la orden: ' . $e->getMessage() ], 500);
        }
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
    {
        $query = DB::table('orders')
            ->join('users', 'orders.operator', '=', 'users.id')
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.id_order')
            ->leftJoin('earrings', 'order_details.id_earring', '=', 'earrings.id')
            ->leftJoin('units_all as ua', function ($join) {
                $join->on('ua.unit_id', '=', 'earrings.unit')
                    ->on('ua.type', '=', 'earrings.type');
            })
            ->leftJoin('order_annexes', 'orders.id', '=', 'order_annexes.order_id')
            ->where('orders.status', 4)
            ->selectRaw("
                orders.id,
                orders.date_attended,
                orders.date_in,
                MAX(orders.repair) AS repair,
                MAX(orders.odometro) AS odometro,
                users.name,
                users.a_paterno,
                users.a_materno,
                MAX(earrings.type_mtto) AS type_mtto,
                MAX(ua.no_economic) AS unidad,
                MAX(orders.total_mano) AS total_mano,
                MAX(orders.total_parts) AS total_parts,
                GROUP_CONCAT(DISTINCT earrings.description SEPARATOR ', ') AS fallas,
                MAX(order_annexes.status) AS evidence_status
            ")
            ->groupBy(
                'orders.id',
                'orders.date_attended',
                'orders.date_in',
                'users.name',
                'users.a_paterno',
                'users.a_materno'
            );

        if ($request->filled('id')) {
            $query->where('orders.id', 'like', "%{$request->id}%");
        }

        if ($request->filled('date')) {
            $query->where('orders.date_attended', 'like', "%{$request->date}%");
        }

        if ($request->filled('unit')) {
            $query->where('ua.no_economic', 'like', "%{$request->unit}%");
        }

        if ($request->filled('operator')) {
            $query->whereRaw(
                "LOWER(CONCAT(users.name,' ',users.a_paterno,' ',users.a_materno)) LIKE ?",
                ['%' . strtolower($request->operator) . '%']
            );
        }

        if ($request->filled('type')) {
            $query->where('earrings.type_mtto', 'like', "%{$request->type}%");
        }

        if ($request->filled('falla')) {
            $query->where('earrings.description', 'like', "%{$request->falla}%");
        }

        if ($request->filled('descripcion')) {
            $query->where('orders.repair', 'like', "%{$request->descripcion}%");
        }

        return response()->json(
            $query->orderBy('orders.date_attended', 'desc')
                ->paginate(60)
        );
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

        $status = (int) $request->input('data.status');
        $action = $request->input('data.action');
        $formData = $request->input('data.form', []);

        if (!is_array($formData)) {
            $formData = [];
        }

        /* ------------------------------------------------------------------------
        | VALIDAR ANTES DE FINALIZAR
        |--------------------------------------------------------------------------
        | Para finalizar definitivamente, primero debe haberse registrado date_attended al terminar la reparación. */
        $orderEarrings = collect();

        if ($status === 4) {
            if (empty($order->date_attended)) {
                return response()->json(['message' => 'Primero debes terminar la reparación antes de finalizar la orden.'], 422);
            }

            $earringIds = OrderDetail::where('id_order', $order->id)->pluck('id_earring');

            $orderEarrings = Earrings::whereIn('id', $earringIds)->get();

            if ($orderEarrings->isEmpty()) {
                return response()->json(['message' => 'La orden no tiene fallas asociadas.'], 422);
            }

            $firstEarring = $orderEarrings->first();
            // Los tipos 3, 4 y 5 no requieren odómetro.
            $requiresOdometer = !in_array((int) $firstEarring->type, [3, 4, 5], true);

            $requiresBatteryInspection = $orderEarrings->contains(function ($earring) {
                return preg_match('/bater[ií]a/i', $earring->description ?? '');
            });

            $rules = [
                'data.form.requisitions' => ['required', 'string'],
                'data.form.repair' => ['required', 'string'],
                'data.form.spare_parts' => ['required', 'string'],
                'data.form.operator' => ['required', 'integer', 'exists:users,id'],
                'data.form.total_mano' => ['required', 'numeric', 'min:0'],
                'data.form.total_parts' => ['required', 'numeric', 'min:0'],
            ];

            if ($requiresOdometer) {
                $rules['data.form.odometro'] = ['required', 'numeric', 'min:0'];
            }

            if ($requiresBatteryInspection) {
                $rules['data.form.voltaje'] = ['required', 'numeric', 'min:0'];

                $rules['data.form.arranque'] = ['required', 'in:Sí, arranca sin dificultad,Arranca con dificultad,No arranca'];
            }
            // Laravel responderá con HTTP 422 si falta información obligatoria.
            $request->validate($rules);
        }

        /*------------------------------------------------------------------------
        | ACTUALIZAR ODÓMETRO
        |--------------------------------------------------------------------------
        | También se permite actualizarlo al terminar la reparación. Si se está guardando un avance y el campo está vacío, se omite.*/
        $finishRepair = $status === 3 && $action === 'finish_repair';

        if ($finishRepair) {
            $request->validate([
                'data.form.operator' => ['required', 'integer', 'exists:users,id',],
                'data.form.repair' => ['required', 'string',],
                'data.form.spare_parts' => ['required', 'string',],
            ], [
                'data.form.operator.required' => 'Debes seleccionar un operador.',
                'data.form.operator.integer' => 'El operador seleccionado no es válido.',
                'data.form.operator.exists' => 'El operador seleccionado no existe.',
                'data.form.repair.required' => 'Debes capturar el resumen de reparación.',
                'data.form.repair.string' => 'El resumen de reparación debe ser texto.',
                'data.form.spare_parts.required' => 'Debes capturar la lista de refacciones.',
                'data.form.spare_parts.string' => 'La lista de refacciones debe ser texto.',
            ]);

            // Rechazar cadenas que solo contienen espacios.
            $form = $request->input('data.form', []);

            if (trim($form['repair']) === '') {
                return response()->json(['message' => 'Debes capturar el resumen de reparación.'], 422);
            }

            if (trim($form['spare_parts']) === '') {
                return response()->json(['message' => 'Debes capturar la lista de refacciones.'], 422);
            }
        }

        if (in_array($status, [0, 4], true) || $finishRepair) {
            $odometroInput = $formData['odometro'] ?? null;

            if (is_numeric($odometroInput)) {
                $odometro = (int) $odometroInput;

                if (($status === 4 && $odometroInput !== '') || ($status === 0 && $odometro > 0) || ($finishRepair && $odometro > 0)) {
                    $detail = OrderDetail::where('id_order', $order->id)->first();

                    if (!$detail) {
                        return response()->json(['message' => 'No se encontró el detalle de la orden.'], 422);
                    }

                    $earring = Earrings::find($detail->id_earring);

                    if (!$earring) {
                        return response()->json(['message' => 'No se encontró la unidad.'], 422);
                    }

                    $tables = [1 => 'tractocamiones', 2 => 'remolques', 3 => 'dollys', 4 => 'volteos', 5 => 'toneles', 6 => 'tortons', 7 => 'autobuses', 8 => 'sprinters', 9 => 'utilitarios', 10 => 'maquinarias',];

                    $table = $tables[$earring->type] ?? null;

                    if (!$table) {
                        return response()->json(['message' => 'Tipo de unidad inválido.'], 422);
                    }

                    $currentOdometer = (int) DB::table($table)
                        ->where('id', $earring->unit)
                        ->value('odometro');

                    if ($odometro < $currentOdometer) {
                        return response()->json(['message' => 'El odómetro no puede ser menor al actual de la unidad.'], 422);
                    }

                    DB::table($table)
                        ->where('id', $earring->unit)
                        ->update(['odometro' => $odometro]);

                    $order->odometro = $odometro;
                }
            } elseif ($status === 4 && $requiresOdometer) {
                return response()->json(['message' => 'Debes indicar el odómetro de la unidad.'], 422);
            }
        }

        /*-----------------------------------------------------------------------
        | GUARDAR CAMBIOS SIN TERMINAR LA REPARACIÓN
        |--------------------------------------------------------------------------
        | Tu botón Guardar cambios envía status 0. Se conservan el status 3 y la fecha de atención existente.*/
        if ($status === 0) {
            $perform = $formData['perform'] ?? null;
            // No sobrescribir el mecánico con una selección vacía.
            unset($formData['perform']);

            $order->fill($formData);
            $order->status = 3;

            if (!empty($perform)) {
                $order->perform = (int) $perform;
            } elseif (empty($order->perform)) {
                $order->perform = Auth::id();
            }
        /*-----------------------------------------------------------------------
        | AUTORIZAR ORDEN
        |------------------------------------------------------------------------*/
        } elseif ($status === 2) {
            $order->status = 2;
            $order->authorize = Auth::id();
        /*-----------------------------------------------------------------------
        | PASAR A PROCESO O TERMINAR REPARACIÓN
        |----------------------------------------------------------------------*/
        } elseif ($status === 3 && $action === 'finish_repair') {
            // PRIMERA ETAPA: guardar la información de reparación.
            $perform = $formData['perform'] ?? null;

            unset($formData['perform']);

            $order->fill($formData);
            $order->status = 3;
            // Guardar fecha solo la primera vez.
            if (empty($order->date_attended)) {
                $order->date_attended = now();
            }
            // Conservar el mecánico asignado previamente.
            if (!empty($perform)) {
                $order->perform = (int) $perform;
            } elseif (empty($order->perform)) {
                $order->perform = Auth::id();
            }
        } elseif ($status === 3) {
            // Asignar la fecha de inicio solo al pasar a proceso.
            $order->status = 3;
            $order->date_in = now();

            $perform = $request->input('data.perform');

            $order->perform = !empty($perform) ? (int) $perform : Auth::id();
        /*------------------------------------------------------------------------
        | FINALIZAR ORDEN
        |------------------------------------------------------------------*/
        } elseif ($status === 4) {
            // La validación anterior garantiza que date_attended ya exista.
            $perform = $formData['perform'] ?? null;

            unset($formData['perform']);

            $order->fill($formData);
            // No volver a modificar date_attended.
            $order->status = 4;

            if (!empty($perform)) {
                $order->perform = (int) $perform;
            } elseif (empty($order->perform)) {
                $order->perform = Auth::id();
            }
            // Finalizar las fallas asociadas.
            $earringIds = OrderDetail::where('id_order', $order->id)->pluck('id_earring');

            Earrings::whereIn('id', $earringIds)->update(['status' => 0]);

            // Actualizar los cronogramas relacionados.
            $doneDate = Carbon::now();
            $doneWeek = $doneDate->isoWeek();
            $doneYear = $doneDate->isoWeekYear();

            $scheduleIds = $orderEarrings
                ->pluck('schedule_id')
                ->filter()
                ->unique();

            $schedules = ProgramsMttoVehicleSchedule::whereIn('id', $scheduleIds)->get();

            foreach ($schedules as $schedule) {
                if ((int) $schedule->week === (int) $doneWeek && (int) $schedule->year === (int) $doneYear) {
                    $schedule->status = 'done';
                } else {
                    $schedule->status = 'late';
                }

                $schedule->executed_at = $doneDate;
                $schedule->save();
            }

        } else {
            return response()->json(['message' => 'El estado solicitado no es válido.'], 422);
        }
        /*----------------------------------------------------------------------
        | GUARDAR ORDEN
        |--------------------------------------------------------------------------*/
        try {
            $order->save();

            return response()->json([
                'message' => $finishRepair
                    ? 'Reparación registrada correctamente. La orden continúa en proceso.'
                    : ($status === 4 ? 'Orden finalizada correctamente.' : 'Orden actualizada correctamente.'),
                'order' => $order->fresh(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar orden', [
                'order_id' => $id,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Error al actualizar la orden.'], 500);
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
            $fm = $firstEarring->fm; // Extract nummber fisico mecanica
            $type_mtto = strtoupper(trim($firstEarring->type_mtto)); // Extract type_mtto

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
            'type_unit' => $type,
            'type_mtto' => $type_mtto,
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
