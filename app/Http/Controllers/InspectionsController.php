<?php

namespace App\Http\Controllers;

use App\Models\Inspections;
use App\Models\MissingDocs;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InspectionsController extends Controller
{
    
    public function index(Request $request)
    {
        $tipo = $request->query('tipo'); // personal | cc | utilitario
        $tipo = $request->query('tipo');

if (is_array($tipo)) {
    $tipo = reset($tipo); // toma el primer valor si es array
}

$tipo = strtolower(trim($tipo)); // limpiar espacios y forzar minúsculas
logger($tipo);
        $query = DB::table('inspections')
            ->join('units_all', function ($join) {
                $join->on('units_all.unit_id', '=', 'inspections.unit')
                    ->on('units_all.type', '=', 'inspections.type');
            })
            ->join('users', 'users.id', '=', 'inspections.responsible')
            ->select(
                'inspections.id',
                'inspections.type',
                'inspections.is',
                'inspections.status',
                'inspections.created_at',
                'units_all.no_economic',
                'units_all.logistic',
                'units_all.customer',
                'users.name as responsible'
            )
            ->where('inspections.status', 1);

        // 🚚 FILTRO POR LOGÍSTICA
        if ($tipo === 'personal') {
            logger('entra aqui tiene que separar personal');

            $query->where('units_all.logistic', 'Logistica Personal');
        }

        if ($tipo === 'cc') {
            logger('entra aqui tiene que separar cc');

            $query->where('units_all.logistic', 'Logistica cc');
        }

        if ($tipo === 'utilitario') {
            logger('entra aqui tiene que separar UTILITARIO');
            $query->where('units_all.logistic', 'Utilitarios');
        }

        return response()->json($query->get());
    }

    public function countByLogistic()
    {
        $counts = DB::table('inspections')
            ->join('units_all', function ($join) {
                $join->on('units_all.unit_id', '=', 'inspections.unit')
                    ->on('units_all.type', '=', 'inspections.type');
            })
            ->where('inspections.status', 1)
            ->select(DB::raw(
                "SUM(CASE WHEN units_all.logistic = 'Logistica Personal' THEN 1 ELSE 0 END) AS personal,
                SUM(CASE WHEN units_all.logistic = 'Logistica cc' THEN 1 ELSE 0 END) AS cc,
                SUM(CASE WHEN units_all.logistic = 'Utilitarios' THEN 1 ELSE 0 END) AS utilitario"
            ))
            ->first();

        return response()->json($counts);
    }


    public function inspectionsReport($id)
    {
        if ($id == 2) {
            $inspections = DB::table('inspections')
                            ->where('status', 2)
                            ->where('is', 'documentos')
                            ->orderBy('id', 'desc')
                            ->get();
        } else {
            //
        }

        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        $nameDocs = ['', 'F-05-07 INSPECCION DOCUMENTOS LEGALES DE TRACTOCAMION', '', 'Dolly', 'F-05-27 INSPECCION DOCUMENTOS LEGALES GONDOLA', 'F-05-08-R1 INSPECCIONES DOCUMENTOS LEGALES DE TANQUE', 'Torton', 'F-05-22 INSPECCION DOCUMENTOS LEGALES DE AUTOBUS', 'F-05-21 INSPECCION DOCUMENTOS LEGALES DE SPRINTER', 'F-05-28 INSPECCION DOCUMENTOS LEGALES DE VEHICULOS UTILITARIOS', 'Maquinaria'];
        
        foreach ($inspections as $inspection) {
            $id_unit = $inspection->unit;
            $id_responsible = $inspection->responsible;
            
            $unit = DB::table($tablas[$inspection->type])->select('no_economic')->where('id', $id_unit)->first();
            $inspection->unit = $unit->no_economic;    
                   
            $responsible = DB::table('users')->select('name')->where('id', $id_responsible)->first();
            $inspection->responsible = $responsible->name;

            $PDF = $nameDocs[$inspection->type] . "- Folio N°{$inspection->id}.pdf";
            $inspection->pdf = asset(Storage::url('public/Inspections/'.$PDF));
        }
        return response()->json($inspections);
    }

    public function create(Request $request)
    {
        try {
            // Obtén el tipo y el ID de la unidad de la solicitud
            $type = $request->input('type');
            $unitId = $request->input('unit');

            // Determina la tabla correspondiente según el tipo de unidad
            $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
            $tablaUnidad = $tablas[$type];

            // Actualiza el estado de la unidad a "inspection" en la tabla correspondiente
            if (!empty($tablaUnidad)) {
                DB::table($tablaUnidad)->where('id', $unitId)->update(['status' => 'inspection']);
            }

            // Guarda la inspección
            $program = new Inspections($request->all());
            $program->save();

            return response()->json(['message' => 'Inspección generada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al generar la inspección.'], 500);
        }
    }

    public function show($id)
    {
        $inspection = Inspections::find($id);

        if (!$inspection) {
            return response()->json(['error' => 'Revision not found'], 404);
        }

        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        if (!isset($tablas[$inspection->type])) {
            return response()->json(['error' => 'Invalid ins$inspection type'], 400);
        }

        $unit = DB::table($tablas[$inspection->type])->where('id', $inspection->unit)->first();

        if (!$unit) {
            return response()->json(['error' => 'Unidad no encontrada'], 404);
        }

        if ($unit) {
            $inspection->unit = $unit;
        } else {
            $inspection->unit = null; // or any default value or handle accordingly
        }

        return response()->json($inspection);
    }


    public function finish(Request $request)
    {
        if ($request->input('Document') == 'F-05-26 EQUIPO GUARDIAN Y CIRCUITO CERRADO') {
            # code...
        }else if($request->input('Document') == 'F-05-23 R2 DE NEUMATICOS'){
            # code...
        }else if($request->input('Document') == 'F-05-25 ALINEACION DE REMOLQUES'){
            # code...
        }else{
            $infoInspection = $request->inspection;
            $dataVerificar = $request->except(['inspection', 'Document']); // Excluir 'revision' del array $data
            $data = $request->except(['inspection']); // Excluir 'revision' del array $data
            $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
           
            // Actualización de Inspections
            Inspections::find($infoInspection['id'])->update([
                'status' => 2,
                'end_date' => now(), // Puedes usar la función now() en lugar de Carbon::now()
            ]);
            
            $unit = $infoInspection['unit'];            
            if (!$unit) {
                return response()->json(['error' => 'La unidad no fue encontrada.'], 404);
            }
            $data['unit'] = $unit;

            $coordinator = DB::table('users')->where('rol', 'Coordinador Mantenimiento')->first();
            if ($coordinator) {
                $data['coordinador'] = $coordinator; // Agregar información de la unidad al array $data
            }

            $folio = $infoInspection['id'];

            foreach ($dataVerificar as $key => $value) {
                if ($value == 'NO') {
                    $observa = 'ob'.$key;
                    // Verificar si la clave $observa existe en $dataVerificar antes de usarla
                    if (!array_key_exists($observa, $dataVerificar)) {
                        $description = 'Documento faltante: '.$key;
                    }else{
                        $description = 'Documento faltante: '.$key.' ('.$dataVerificar[$observa].')';
                    }

                    // Verificar si la descripción ya existe en los pendientes registrados
                    $existingEarring = MissingDocs::where('description', $description)->where('status', 1)->where('type', $infoInspection['type'])->where('unit', $infoInspection['unit']['id'])->first();
                    if (!$existingEarring) {
                        $missingData = [
                            'type' => $infoInspection['type'],
                            'unit' => $infoInspection['unit']['id'],
                            'description' => $description,
                            'date' => Carbon::now(),
                            'inspection' => $folio,
                        ];           
                        $missing = new MissingDocs($missingData);
                        $missing->save();
                    }
                }
            }

            // Actualización de la Unidad
            $unitId = $unit['id'];
            $unitTable = $tablas[$infoInspection['type']];
            DB::table($unitTable)->where('id', $unitId)->update(['status' => 'available']);

            $data['folio'] = $folio; // Agregar información de folio al array $data

            $operator = DB::table('users')->where('id', $infoInspection['responsible'])->first();
            if ($operator) {
                $data['operator'] = $operator; // Agregar información de la unidad al array $data
            }
            
            // AQUÍ SE DEBE GENERAR EL PDF
            $pdfContent = $this->PDF_DocumentosLegales($data);
            Storage::disk('public')->put('Inspections/'.$data['Document'].'- Folio N°'. $folio . '.pdf', $pdfContent);
                 
            return response()->json(['message' => 'Inspección completada exitosamente.']);
        }
    }

    private function PDF_DocumentosLegales ($data)
    {
        $document = $data['Document'];
        
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64

        $data = [
            'logoImage' => $logoImage,
            'data' => $data,
            'fecha' => now(),
        ];

        $html = view($document, $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return $dompdf->output(); 
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }
}