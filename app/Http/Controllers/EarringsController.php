<?php

namespace App\Http\Controllers;

use App\Models\Earrings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EarringsController extends Controller
{
    public function index()
    {
        $earrings = DB::table('earrings')->where('status', 1)->get();
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        foreach ($earrings as $earring) {
            $id_unit = $earring->unit;
            if ($earring->type == 3) {
                $unit = DB::table('dollys')->select('no_seriously')->where('id', $id_unit)->first();
                $earring->unit = $unit->no_seriously;
            } else {
                $unit = DB::table($tablas[$earring->type])->select('no_economic')->where('id', $id_unit)->first();
                $earring->unit = $unit->no_economic;
            }    
            $earring->type = $tablas[$earring->type];
        }
        return response()->json($earrings);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $earring = Earrings::find($id);
        $id_unit = $earring->unit;
        $tablas = ['', 'tractocamiones', 'remolques', 'dollys', 'volteos', 'toneles', 'tortons', 'autobuses', 'sprinters', 'utilitarios', 'maquinarias'];

        if ($earring->type == 3) {
            $unit = DB::table('dollys')->select('no_seriously')->where('id', $id_unit)->first();
            $earring->unit = $unit->no_seriously;
        } else {
            $unit = DB::table($tablas[$earring->type])->select('no_economic')->where('id', $id_unit)->first();
            $earring->unit = $unit->no_economic;
        }
        $earring->type = $tablas[$earring->type];
        return response()->json($earring);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {   
        Earrings::find($id)->update(['description'=> $request->description] ); 
    }

    public function destroy($id)
    {
        //
    }
}
