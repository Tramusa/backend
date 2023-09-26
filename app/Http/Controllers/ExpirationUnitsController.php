<?php

namespace App\Http\Controllers;

use App\Models\ExpirationUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpirationUnitsController extends Controller
{
    public function index()
    {
        $expirations = ExpirationUnits::orderBy('type_unit')->get();
        //sacar info de la unidad  donde type_unit es la tabla y unit es el id agregar a expirations
        foreach ($expirations as $expiration) {
            $unitInfo = DB::table($expiration->type_unit)
                ->where('id', $expiration->unit)
                ->get();    
            // Agregar la información de la unidad al registro de expirations
            $expiration->unit = $unitInfo;
        } 
        return response()->json($expirations);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }
 
    public function edit($id)
    {
        //
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
