<?php

namespace App\Http\Controllers;

use App\Models\ConcentradoResolution;
use App\Models\CorrectiveAction;
use App\Models\NonConformity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConcentratedResolutionController extends Controller
{
    public function index()
    {
        return ConcentradoResolution::with('responsible')
        ->orderBy('folio')
        ->orderBy('id')
        ->get();
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $exists = ConcentradoResolution::where('source', 'MANUAL')
                ->where('folio', $request->folio)
                ->where('resolution', $request->resolution)
                ->whereDate('agreement_date', $request->agreement_date)
                ->exists();

            if($exists){
                return response()->json(['message'=> 'El registro ya existe.'],409);
            }

            $record =
                ConcentradoResolution::create([
                    'source'=>'MANUAL',
                    'folio'=>$request->folio,
                    'area'=>$request->area,
                    'resolution'=>$request->resolution,
                    'category'=>$request->category,
                    'agreement_date'=> $request->agreement_date,
                    'support'=> $request->support,
                    'responsible_id'=> $request->responsible_id,
                    'planned_closure'=> $request->planned_closure,
                    'observations'=> $request->observations,
                ]);

            DB::commit();

            return response()->json(['message'=>'Guardado', 'data'=>$record ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message'=>$e->getMessage()],500);
        }
    }
}
