<?php

namespace App\Http\Controllers;

use App\Models\DetailsRequisitions;
use App\Models\Requisitions;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RequisitionsController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el estado de las requisiciones desde la solicitud, si existe
        $status = $request->query('status');
    
        // Construir la consulta
        $query = Requisitions::query();
    
        // Filtrar por estado si se proporciona
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filtrar las requisiciones con id diferente de 0 y cargar las relaciones necesarias
        $requisitions = $query->where('id', '!=', 0)
            ->with([
                'work_areaInfo',
                'collaboratorInfo',
                'parent_accountInfo',
                'title_accountInfo',
                'mayor_accountInfo',
                'subtitle_accountInfo'
            ])
            ->get();
    
        // Recorrer cada orden de compra para agregar la URL del comprobante
        foreach ($requisitions as $requisition) {
            $requisition->comprobante_url = $requisition->comprobante
                ? asset(Storage::url($requisition->comprobante))
                : null; // O manejar el caso donde no hay comprobante
        }

        // Devolver la respuesta JSON con las requisiciones filtradas
        return response()->json($requisitions);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        // Decodificar el JSON de dataAdd
        $dataAdd = json_decode($request->dataAdd, true);

        // Combinar dataAdd con otros datos del request
        $data = array_merge($request->all(), $dataAdd);

        // Asignar el ID del usuario y el estado 'PENDIENTE'
        $data['id_user'] = $user->id;
        $data['status'] = 'PENDIENTE';
        
      
        // Crear la nueva requisición
        $requisition = new Requisitions($data);
        $requisition->save();

        // Actualizar los productos o servicios con id_requisition = 0
        DetailsRequisitions::where('id_requisition', 0)
            ->where('id_user', $user->id)
            ->update(['id_requisition' => $requisition->id]);

       // Manejar el archivo PDF si está presente
        if ($request->hasFile('pdf')) {
            // Subir el archivo a la carpeta 'public/Comprobantes'
            $path = $request->file('pdf')->store('Comprobantes', 'public');
            $requisition->comprobante = $path; // Guardar la ruta del archivo en 'comprobante'
            $requisition->save(); // Guardar los cambios en la requisición
        }
        
        // Generar el PDF y devolverlo
        return $this->generarPDF($requisition->id);
    }


    public function generarPDF($requisition){
        $pdfContent = $this->PDF($requisition);

        Storage::disk('public')->put('requisitions/Requisicion N°'. ($requisition) . '.pdf', $pdfContent);

        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');// Devolver el contenido del PDF
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }

    private function PDF($requisition)
    {       
        $requisitionData = Requisitions::where('id', $requisition)
                    ->with('work_areaInfo') // Eager load  relationship
                    ->with('collaboratorInfo') // Eager load  relationship
                    ->with('parent_accountInfo') // Eager load  relationship
                    ->with('title_accountInfo') // Eager load  relationship
                    ->with('mayor_accountInfo') // Eager load  relationship
                    ->with('subtitle_accountInfo') // Eager load  relationship
                    ->with('user_authorized') // Eager load  relationship
                    ->with('user_analyze') // Eager load  relationship
                    ->first();

        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);// Convertir las imágenes a base64
        $user = Auth::user();

        // Fetch details requisitions for the logged-in user where id_product is not 0
        $detailsRequisitions = DetailsRequisitions::where('id_user', $user->id)
                                                  ->where('id_requisition', $requisition)
                                                  ->get();

        $data = [
            'logoImage' => $logoImage,
            'Data' => $requisitionData,
            'detailsRequisitions' => $detailsRequisitions,
            'fecha' => Carbon::parse($requisitionData->date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'),
        ];

        $html = view('F-04-02 REQUISICION DE SUMINISTROS', $data)->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->output();                     
    }

    public function show($id)
    {
        $requisition = Requisitions::find($id);
        return response()->json($requisition);
    }

    public function update(Request $request, $id)
    {
        // Find the requisition by ID, or return a 404 error if not found
        $requisition = Requisitions::findOrFail($id);
        // Filtrar solo los campos planos que quieres actualizar
        $data = $request->only([
            'date',
            'date_atended',
            'date_authorized',
            'id',
            'id_collaborator',
            'id_mayor_account',
            'id_parent_account',
            'id_subtitle_account',
            'id_title_account',
            'id_user',
            'id_work_area',
            'observations',
            'status',
            'updated_at'
        ]);

        // Actualizar la requisición con los datos filtrados
        $requisition->update($data);
        
        return response()->json(['message' => 'Requisicion actualizado exitosamente.'], 201);
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
        $requisition = Requisitions::findOrFail($id);
        $requisition->status = $request->input('status');
        $requisition->date_authorized = $request->input('status') === 'APROBADA' ? now() : null;
        $requisition->authorized = $request->input('status') === 'APROBADA' ? $user->id : null;
        $requisition->save();

        return response()->json(['message' => 'Estatus actualizado correctamente']);
    }

    public function destroy($id)
    {
        Requisitions::destroy($id);

        return response()->json(['message' => 'Requisicion eliminado exitosamente.'], 201);
    }
}
