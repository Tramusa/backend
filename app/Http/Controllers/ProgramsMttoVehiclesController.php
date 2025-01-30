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

    
    public function generarPDF(Request $request)
    {
        $activities = $request->input('activities');
    
        if (!$activities || empty($activities)) {
            return response()->json(['message' => 'No se proporcionaron actividades'], 400);
        }

        // Ordenar las actividades por no_economic
        $activities = collect($activities)->sortBy('no_economic')->values()->all();
    
        // Obtener la fecha actual en español
        $fechaActual = Carbon::now();
        $fecha = $fechaActual->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        $currentWeek = $fechaActual->week;
    
        // Convertir el logo a base64
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);
    
        // Preparar los datos para la vista
        $data = [
            'logoImage' => $logoImage,
            'Activitys' => $activities,
            'fecha' => $fecha,
            'currentWeek' => $currentWeek,
        ];
    
        // Renderizar la vista con los datos
        $html = view('PR-05-01-R1 PROGRAMA DE MANTENIMIENTO A VEHICULOS', $data)->render();
    
        // Configuración de Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $pdfContent = $dompdf->output();   
    
        // Generar un nombre único para el archivo PDF
        $filename = 'Programa_Mantenimiento_' . now()->format('Ymd_His') . '.pdf';
    
        // Guardar el PDF en el almacenamiento
        Storage::disk('public')->put('pdfs/' . $filename, $pdfContent);
    
        // Devolver el contenido del PDF
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }
}
