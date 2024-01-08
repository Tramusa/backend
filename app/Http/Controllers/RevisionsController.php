<?php

namespace App\Http\Controllers;

use App\Models\Autobuses;
use App\Models\Dollys;
use App\Models\Earrings;
use App\Models\Maquinarias;
use App\Models\Revisions;
use App\Models\Sprinters;
use App\Models\Toneles;
use App\Models\Tortons;
use App\Models\Tractocamiones;
use App\Models\Utilitarios;
use App\Models\Volteos;
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
            $revision->unit = $unit->no_economic;    
                   
            $responsible = DB::table('users')->select('name')->where('id', $id_responsible)->first();
            $revision->responsible = $responsible->name;
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
            $tablaUnidad = $tablas[$type];

            // Actualiza el estado de la unidad a "revision" en la tabla correspondiente
            if (!empty($tablaUnidad)) {
                DB::table($tablaUnidad)->where('id', $unitId)->update(['status' => 'inspection']);
            }

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
        if ($request->input('Document') == 'F-05-10 CONSUMO DE COMBUSTIBLE') {
            # code...
        }else{
            $infoRevision = $request->revision;
            $dataVerificar = $request->except(['revision', 'Document']); // Excluir 'revision' del array $data

            foreach ($dataVerificar as $key => $value) {
                if ($value == 'NO') {
                    $description = 'No cumple con ( '.$key.' )';
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
                            ]
                        );//GENERAMOS LOS PENDIENTES UNO A UNO 
                        $earrings->save();
                    } 
                }
            }
            //CAMBIAR STATUS
            Revisions::find($infoRevision['id'])->update(['status'=> 2]);
            switch ($infoRevision['type']) {
                case 1:
                    $unit = Tractocamiones::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;   
                case 3:
                    $unit = Dollys::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;
                case 4:       
                    $unit = Volteos::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;
                case 5:       
                    $unit = Toneles::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;
                case 6:       
                    $unit = Tortons::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;
                case 7:       
                    $unit = Autobuses::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;
                case 8:       
                    $unit = Sprinters::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;
                case 9:       
                    $unit = Utilitarios::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;
                case 10:       
                    $unit = Maquinarias::find($infoRevision['unit']);
                    $unit->update(['status' => 'available']);
                    break;
                default:            
                    $unit = null;
                    break;
            }

            //AQUI SE DEBE GENERAR EL PDF
            $data = $request->except(['revision']); // Excluir 'revision' del array $data
            if ($unit) {
                $data['unit'] = $unit->toArray(); // Agregar información de la unidad al array $data
            }
            $pdfContent = $this->PDF_FM($data);
            $id = $request->revision['id'];
            Storage::disk('public')->put('Revisions/'.$request->input('Document').'- Folio N°'. $id . '.pdf', $pdfContent);
            return response()->json(['message' => 'Revision terminada existosamente.']);
        }
    }

    public function show($id)
    {
        $revision = Revisions::find($id);

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
        //
    }

    public function destroy($id)
    {
        //
    }
}
