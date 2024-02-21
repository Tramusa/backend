<?php

namespace App\Http\Controllers;

use App\Models\MissingDocs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MissingDocsController extends Controller
{
    public function index()
    {
        $missings = MissingDocs::where('status', 1)->get();
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($missings as $missing) {
            $id_unit = $missing->unit;
            
            $unit = DB::table($tablas[$missing->type])->select('no_economic')->where('id', $id_unit)->first();
            $missing->unit = $unit->no_economic;
               
            $missing->type = $tablas[$missing->type];
        }
        return response()->json($missings); 
    }

    public function update($id)
    {
        $missingDoc = MissingDocs::find($id);

        if (!$missingDoc) {
            return response()->json(['error' => 'Documento no encontrado'], 404);
        }

        $missingDoc->attended = auth()->id();
        $missingDoc->status = 0; // Suponiendo que 1 significa "atendido"
        $missingDoc->date_attended = now(); // Fecha actual

        $missingDoc->save();

        return response()->json(['message' => 'Documento actualizado exitosamente']);
    
    }

    public function destroy($id)
    {
        //
    }
}
