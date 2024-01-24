<?php

namespace App\Http\Controllers;

use App\Models\DocsPDFs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocsPDFsController extends Controller
{
    public function index(Request $request) {
        $dto = $request->query('dto');
        $docsPDFs = DocsPDFs::where('dto', $dto)->get();
        foreach ($docsPDFs as $doc) {
            $doc->location = asset(Storage::url($doc->location));
        }
        return response()->json($docsPDFs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'dto' => 'required',
            'pdf' => 'required|file|mimes:pdf'
        ]);

        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('public/pdfs');  
            
            // Guardar el título y el ID de la unidad
            $title = $request->input('title');
            $dto = $request->input('dto');
            
            DocsPDFs::create([
                'title' => $title,
                'location' => $path,
                'dto' => $dto
            ]);
            
            return response()->json(['message' => 'Archivo PDF subido correctamente']);
        }
        
        return response()->json(['error' => 'No se encontró ningún archivo PDF']);
    }

    public function show($id)
    {
        $doc = DocsPDFs::find($id);

        if (!$doc) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        return response()->json($doc);
    }

    public function update(Request $request, DocsPDFs $docsPDF)
    {
        Logger($request->input('title'));
        Logger($request->all());
        $request->validate([
            'title' => 'required',
            'dto' => 'nullable', // Asegúrate de que la validación de 'dto' es correcta según tus requisitos
        ]);
    
        $dataToUpdate = $request->only(['title', 'dto']);
    
        if ($request->hasFile('pdf')) {
            $path = $request->file('pdf')->store('public/pdfs');
            $dataToUpdate['location'] = $path;
        }
    
        $docsPDF->update($dataToUpdate);
    
        return response()->json(['message' => 'Se actualizo correctamente']);  
    }

    public function destroy($id)
    {
        $document = DocsPDFs::find($id);

        if (!$document) {
            return response()->json(['message' => 'Documento no encontrado'], 404);
        }

        $document->delete();

        return response()->json(['message' => 'Documento eliminado con éxito']);
    }
}
