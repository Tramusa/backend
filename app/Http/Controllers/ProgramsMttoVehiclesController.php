<?php

namespace App\Http\Controllers;

use App\Models\Earrings;
use App\Models\ProgramsMttoVehicles;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramsMttoVehiclesController extends Controller
{
    public function index()
    {        
        $Programs = ProgramsMttoVehicles::all(); 
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($Programs as $Program) {            
            $unit = DB::table($tablas[$Program->type])->select('no_economic')->where('id', $Program->unit)->first();
            
            if ($unit && $unit->no_economic) {
                $Program->no_economic = $unit->no_economic;
            } else {
                $Program->no_economic = null; 
            }
        }        
        return response()->json($Programs); 
    }

    public function store(Request $request)
    {
        $activity = new ProgramsMttoVehicles($request->all());
        
        $currentWeek = date('W'); // SEMANA ACTUAL

        if ($activity->start == $currentWeek) {    
            // Verificar si la descripción ya existe en las FALLAS registradas
            $existingEarring = Earrings::where('description', 'like', '%' . $activity->activity . '%')
                ->where('status', 1)
                ->where('type', $activity->type)
                ->where('unit', $activity->unit)
                ->first();

            if (!$existingEarring) { // Si no existe la falla, crearla
                $data = [
                    'unit' => $activity->unit,
                    'type' => $activity->type,
                    'description' => $activity->activity,
                    'type_mtto' => 'Preventivo',
                ];
                Earrings::create($data);
            }
        } 

        $activity->save();
        return response()->json(['message' => 'Actividad registrada con exito'], 201);
    }

    public function show($id)
    {
        $activity = ProgramsMttoVehicles::find($id);          
          
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        $unit = DB::table($tablas[$activity->type])->select('no_economic')->where('id', $activity->unit)->first();
            
        if ($unit && $unit->no_economic) {
            $activity->no_economic = $unit->no_economic;
        } else {
            $activity->no_economic = null; 
        }
        
        return response()->json($activity); 
    }
    
    public function update(Request $request, $id)
    {
        ProgramsMttoVehicles::find($id)->update($request->all()); 
        return response()->json(['message' => 'Actividad actualizada exitosamente.'], 201);
    }
    
    public function destroy($id)
    {
        ProgramsMttoVehicles::find($id)->delete();
        return response()->json(['message' => 'Actividad eliminada exitosamente.'], 201);
    }

    private function PDF($activity)
    {
        $DataActivity = ProgramsMttoVehicles::find($activity);
        $Activitys = ProgramsMttoVehicles::where('type', $DataActivity->type)->where('unit', $DataActivity->unit)->get();

        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        foreach ($Activitys as $Activity) {            
            $unit = DB::table($tablas[$Activity->type])->select('no_economic')->where('id', $Activity->unit)->first();
            
            if ($unit && $unit->no_economic) {
                $Activity->no_economic = $unit->no_economic;
            } else {
                $Activity->no_economic = null; 
            }
            
            $dates = []; // Arreglo para almacenar las  SEMANAS
            $periodicitySums = [ // Definir la suma  según la periodicidad
                'Quincenal' => 2,
                'Mensual' => 4,
                'Bimestral' => 8,
                'Trimestral' => 12,
                'Cuatrimestral' => 16,
                'Semestral' => 24,
            ];

            $date = $Activity->start;

            if ($Activity->periodicity !== 'Anual') {
                while ($date <= 52) {
                    $dates[] = $date;
                    $date += $periodicitySums[$Activity->periodicity];
                }
            }else{
                $dates[] = $date;
            }

            $Activity->dates = $dates; // Agregar el arreglo de fechas al objeto $Activity
        }  
        
        $currentWeek = date('W'); // SEMANA ACTUAL

        $fechaActual = Carbon::now();// Obtener la fecha actual

        // Obtener el nombre del día de la semana en español
        $nombreDia = $fechaActual->locale('es')->isoFormat('dddd');

        // Obtener el nombre del mes en español
        $nombreMes = $fechaActual->locale('es')->isoFormat('MMMM');

        // Formatear la fecha según el formato deseado
        $fecha = "$nombreDia, {$fechaActual->day} de $nombreMes del {$fechaActual->year}";

        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64
 
        $data = [
            'logoImage' => $logoImage,
            'Activitys' => $Activitys,
            'fecha' => $fecha,
            'currentWeek' => $currentWeek,
        ];

        $html = view('PR-05-01-R1 PROGRAMA DE MANTENIMIENTO A VEHICULOS', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        return $dompdf->output();                     
    }

    public function generarPDF($activity){
        $pdfContent = $this->PDF($activity);
        Storage::disk('public')->put('pdfs/program-mtto'. ($activity) . '.pdf', $pdfContent);
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');// Devolver el contenido del PDF
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }
}
