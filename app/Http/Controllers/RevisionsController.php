<?php

namespace App\Http\Controllers;

use App\Models\Earrings;
use App\Models\RevCombustibleDetaills;
use App\Models\Revisions;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RevisionsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // Obtén el usuario autenticado
        
        if ($user && ($user->rol === 'Administrador' || strpos($user->rol, 'Coordinador') !== false)) {
            $revisions = DB::table('revisions')->where('status', 1)->get();
        } else {
            $revisions = DB::table('revisions')->where('status', 1)->where('responsible', $user->id)->get();
        }
        
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($revisions as $revision) {
            $id_unit = $revision->unit;
            $id_responsible = $revision->responsible;
            
            $unit = DB::table($tablas[$revision->type])->select('no_economic')->where('id', $id_unit)->first();
            
            if ($unit && $unit->no_economic) {
                $revision->no_economic = $unit->no_economic;
            } else {
                $revision->no_economic = null; // or any default value or handle accordingly
            }

            $responsible = DB::table('users')->select('name')->where('id', $id_responsible)->first();
            
            if ($responsible) {
                $revision->responsible = $responsible->name;
            } else {
                $revision->responsible = null; // or any default value or handle accordingly
            }
        }

        return response()->json($revisions);
    }


    public function revisionsReport($id)
    {
        
        if ($id == 1) {
            $revisions = DB::table('revisions')
                            ->where('status', 2)
                            ->where('is', 'fisico mecanica')
                            ->orderBy('end_date', 'desc')
                            ->get();
        } else if($id == 3){
            $revisions = DB::table('revisions')
                            ->where('status', 2)
                            ->where('is', 'combustible')
                            ->orderBy('end_date', 'desc')
                            ->get();
        }
        
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        $nameDocs  = ['', 'F-05-16 CONDICIONES FISICO MECANICAS DE TRACTOCAMION', '', 'Dolly', 'F-05-18 CONDICIONES FISICO MECANICAS VOLTEO', 'F-05-17 CONDICIONES FISICO MECANICAS TONEL', 'Torton', 'F-05-03 R1 CONDICIONES FISICO-MECANICAS DE TRANSPORTE DE PERSONAL', 'F-05-15 CONDICIONES FISICO MECANICAS VEHICULOS LIGEROS', 'F-05-15 CONDICIONES FISICO MECANICAS VEHICULOS LIGEROS', 'Maquinaria'];
        foreach ($revisions as $revision) {
            $id_unit = $revision->unit;
            $id_responsible = $revision->responsible;
            
            $unit = DB::table($tablas[$revision->type])->select('no_economic')->where('id', $id_unit)->first();
            
            if ($unit && $unit->no_economic) {
                $revision->no_economic = $unit->no_economic;
            } else {
                $revision->no_economic = null; // or any default value or handle accordingly
            }

            $responsible = DB::table('users')->select('name')->where('id', $id_responsible)->first();
            
            if ($responsible) {
                $revision->responsible = $responsible->name;
            } else {
                $revision->responsible = null; // or any default value or handle accordingly
            }

            if ($id == 1) {
                $PDF = $nameDocs[$revision->type] . "- Folio N°{$revision->id}.pdf";
            } elseif ($id == 3) {
                $PDF = "F-05-10 REVISION DE CONSUMO DE COMBUSTIBLE - Folio N°{$revision->id}.pdf";
            }
            $revision->pdf = asset(Storage::url('public/Revisions/'.$PDF));
        }

        return response()->json($revisions);
    }

    public function create(Request $request)
    {
        try {
            // Obtén el tipo y el ID de la unidad de la solicitud
            $type = $request->input('type');
            $unitId = $request->input('unit');

            // Determina la tabla correspondiente según el tipo de unidad
            $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
            
            DB::table($tablas[$type])->where('id', $unitId)->update(['status' => 'inspection']);
            
            // Guarda la revision
            $program = new Revisions($request->all());
            $program->save();

            return response()->json(['message' => 'Revision generada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al generar la revision.'], 500);
        }
    }

    public function finish(Request $request)
    {
        $infoRevision = $request->revision;
        $dataVerificar = $request->except(['revision', 'Document']); // Excluir 'revision' del array $data
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
        
        $updateData = ['status' => 'available'];
        if ($request->filled('odometro')) {
             // Recuperar el valor actual del odómetro
            $currentOdometer = DB::table($tablas[$infoRevision['type']])->where('id', $infoRevision['unit'])->value('odometro');

            // Verificar si el nuevo odómetro es menor que el actual
            if ($request->filled('odometro') && $request->input('odometro') < $currentOdometer) {
                return response()->json(['error' => 'El Odómetro no puede ser menor que el actual.'], 422);
            }
            $updateData['odometro'] = $request->input('odometro');
        }

        $folio = $infoRevision['id'];
           
        foreach ($dataVerificar as $key => $value) {
            if ($value == 'NO') {
                if (array_key_exists('Observacion-'.$key, $dataVerificar)) {
                    $description = 'No cumple con: '.$key.' ( '.$dataVerificar['Observacion-'.$key].' )';
                } else {
                    $description = 'No cumple con: '.$key;
                }
                // Verificar si la descripción ya existe en los pendientes registrados
                $existingEarring = Earrings::where('description', $description)->where('status', 1)->where('type', $infoRevision['type'])->where('unit', $infoRevision['unit'])->first();
                if ($existingEarring) {
                    continue; // La descripción ya existe, pasa al siguiente pendiente
                }else{
                    $earrings = new Earrings(
                        [
                            'unit' => $infoRevision['unit'],
                            'type' => $infoRevision['type'],
                            'description' => $description,
                            'fm' => $folio,
                        ]
                    );//GENERAMOS LOS PENDIENTES UNO A UNO 
                    $earrings->save();
                } 
            }else if ($key == 'observation') {
                $existingEarring = Earrings::where('description', $value)->where('status', 1)->where('type', $infoRevision['type'])->where('unit', $infoRevision['unit'])->first();
                if ($existingEarring) {
                    continue; // La descripción ya existe, pasa al siguiente pendiente
                }else{
                    $earrings = new Earrings(
                        [
                            'unit' => $infoRevision['unit'],
                            'type' => $infoRevision['type'],
                            'description' => $value,
                            'fm' => $folio,
                        ]
                    );//GENERAMOS LOS PENDIENTES UNO A UNO 
                    $earrings->save();
                }
            }
        }
        //CAMBIAR STATUS Fecha y odometro
        $odometro = ($request->filled('odometro')) ? $request->input('odometro') : 0;

        Revisions::find($infoRevision['id'])->update([
            'status' => 2,
            'end_date' => Carbon::now(), // Agregar la fecha de hoy
            'odometro' => $odometro, // Agregar el odómetro
        ]);

        // ACTUALIZAR LA UNIDAD
        DB::table($tablas[$infoRevision['type']])->where('id', $infoRevision['unit'])->update($updateData);
    
        $data = $request->except(['revision']); // Excluir 'revision' del array $data

        $unit = DB::table($tablas[$infoRevision['type']])->where('id', $infoRevision['unit'])->first();
        if ($unit) {
            $data['unit'] = $unit;
        }

        $responsible = DB::table('users')
            ->where('id', $infoRevision['responsible'])
            ->select('name', 'a_paterno', 'a_materno')
            ->first();

        if ($responsible) {
            $data['operator'] = $responsible; // Add unit information to the $data array
        }

        $auxiliar = DB::table('users')
            ->where('rol', 'Auxiliar Mantenimiento')
            ->select('name', 'a_paterno', 'a_materno')
            ->first();

        if ($auxiliar) {
            $data['auxiliar'] = $auxiliar; // Add unit information to the $data array
        }

        $id = $request->revision['id'];

        if ($id) {
            $data['folio'] = $id; // Agregar información de folio al array $data
        }

        //AQUI SE DEBE GENERAR EL PDF
        $pdfContent = $this->PDF_FM($data);
        Storage::disk('public')->put('Revisions/'.$request->input('Document').'- Folio N°'. $id . '.pdf', $pdfContent);
        return response()->json(['message' => 'Revision terminada existosamente.']);
    }

    public function show($id)
    {
        $revision = Revisions::find($id);

        if (!$revision) {
            return response()->json(['error' => 'Revision not found'], 404);
        }

        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        if (!isset($tablas[$revision->type])) {
            return response()->json(['error' => 'Invalid revision type'], 400);
        }

        $unit = DB::table($tablas[$revision->type])->select('no_economic')->where('id', $revision->unit)->first();

        if (!$unit) {
            return response()->json(['error' => 'Unit not found'], 404);
        }

        if ($unit->no_economic) {
            $revision->no_economic = $unit->no_economic;
        } else {
            $revision->no_economic = null; // or any default value or handle accordingly
        }

        return response()->json($revision);
    }
    
    private function PDF_FM ($data)
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

    public function update(Request $request, $id)
    {
        //Logger($request);

        $detalles = $request->except(['unit', 'revision', 'status']);

        foreach ($detalles as $columna => $data) {
            // Check if the entry exists in RevCombustibleDetails for the given revision_id and columna
            $existingEntry = RevCombustibleDetaills::where('revision_id', $id)
                ->where('columna', $columna)
                ->first();
    
            if ($existingEntry) {
                // If the entry exists, update it
                $existingEntry->update($data);
            } else {
                // If the entry doesn't exist, create a new one
                RevCombustibleDetaills::create(array_merge(['revision_id' => $id, 'columna' => $columna], $data));
            }
        }

         // Update the status of the revision
        $end_date = Carbon::now();
        $revision = Revisions::find($id);
        if ($revision) {
            $revision->update(['status' => $request->status, 'end_date' => $end_date]);
        }

        if ($request->status == 2) {//SOLO SI ES status == 2 FINALIZADO
             //AQUI SE DEBE GENERAR EL PDF
             $data = $request->all();

             // ACTUALIZAR LA UNIDAD
            $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];
            DB::table($tablas[$revision['type']])->where('id', $revision['unit'])->update(['status' => 'available']);

             // Actualizar la fecha final en el arreglo de revisión en los datos de la solicitud
             $data['revision']['end_date'] = $end_date;
         
             // Generar el PDF con la fecha final actualizada
             $pdfContent = $this->PDF_COMBUSTIBLE($data);
             Storage::disk('public')->put('Revisions/F-05-10 REVISION DE CONSUMO DE COMBUSTIBLE - Folio N°'. $id . '.pdf', $pdfContent);
        }        

        // Return a exito! message as JSON
        return response()->json(['message' => 'Revisión actualizada con exito!...']);
    }

    private function PDF_COMBUSTIBLE ($data)
    {        
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64

        $responsible = DB::table('users')
                ->where('id', $data['revision']['responsible'])
                ->select('name', 'a_paterno', 'a_materno')
                ->first();
        $coordinador = DB::table('users')
                ->where('rol', 'Coordinador Mantenimiento')
                ->select('name', 'a_paterno', 'a_materno')
                ->first();

        $data = [
            'logoImage' => $logoImage,
            'data' => $data,
            'operator' => $responsible,
            'coordinador' => $coordinador,
        ];

        $html = view('F-05-10 REVISION DE CONSUMO DE COMBUSTIBLE', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        return $dompdf->output(); 
    }

    public function showDetails($id)
    {
        $formList = [];
        $data = [];

        $details = RevCombustibleDetaills::where('revision_id', $id)->get();

        foreach ($details as $detail) {
            // Extract relevant data excluding 'id' and 'revision_id'
            $columna = $detail->columna;
            $detailData = $detail->only(['name', 'distancia_tablero', 'combustible_cargado', 'factor_correcion', 'distancia_ecm', 'combustible_usado', 'rendimiento_combustible', 'ecm_real', 'peso_bruto', 'tiempo', 'consumo_ralenti', 'tiempo_ralenti', 'consumo_pto', 'tiempo_pto']); 

            $formList[] = $columna;

            $data[$columna] = $detailData;
        }

        $response = [
            'formList' => $formList,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function destroy($id)
    {
        //
    }
}
