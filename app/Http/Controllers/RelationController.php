<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use App\Models\Ishikawa;
use App\Models\NonConformity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelationController extends Controller
{
    public function show($id)
    {
        $ishikawa = Ishikawa::where(
            'non_conformity_id',
            $id
        )
        ->with('causes')
        ->firstOrFail();

        $relation = Relation::where(
            'non_conformity_id',
            $id
        )->first();

        return response()->json([

            'causes' => $ishikawa
                ->causes
                ->pluck('description')
                ->values(),

            'matrix' => $relation?->matrix ?? [],

        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'non_conformity_id' => 'required|exists:non_conformities,id',

            'matrix' => 'required|array',

            'finish' => 'nullable|boolean',

        ]);

        DB::transaction(function () use ($data) {

            Relation::updateOrCreate(

                [
                    'non_conformity_id' => $data['non_conformity_id'],
                ],

                [
                    'matrix' => $data['matrix'],
                ]

            );

            /*----- Si se finaliza pasa al siguiente proceso (Pareto)------------*/

            if (!empty($data['finish'])) {

                NonConformity::findOrFail(
                    $data['non_conformity_id']
                )->update([

                    'status' => 'pareto',

                ]);

            }

        });

        return response()->json([

            'message' => !empty($data['finish'])
                ? 'Diagrama de relación finalizado correctamente.'
                : 'Diagrama de relación guardado correctamente.',

        ]);
    }

    public function destroy($id)
    {
        $relation = Relation::findOrFail($id);

        $relation->delete();

        return response()->json([

            'message' => 'Registro eliminado correctamente.'

        ]);
    }
}