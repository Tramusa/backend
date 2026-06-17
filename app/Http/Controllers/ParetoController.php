<?php

namespace App\Http\Controllers;

use App\Models\ActionPlanCause;
use App\Models\Ishikawa;
use App\Models\IshikawaCause;
use App\Models\Relation;
use App\Models\NonConformity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParetoController extends Controller
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

        $matrix = $relation?->matrix ?? [];

        $causes = $ishikawa
            ->causes
            ->sortBy('id')
            ->values();

        $result = [];

        foreach ($causes as $index => $cause) {

            $total = 0;

            // Relaciones donde aparece en la fila
            for ($col = 0; $col < $index; $col++) {
                if (!empty($matrix["{$index}-{$col}"])) {
                    $total++;
                }
            }

            // Relaciones donde aparece en la columna
            for ($row = $index + 1; $row < count($causes); $row++ ) {
                if (!empty($matrix["{$row}-{$index}"])) {
                    $total++;
                }
            }

            $selected = ActionPlanCause::where('non_conformity_id',$id)
            ->where(
                'ishikawa_cause_id',
                $cause->id
            )->exists();

            $result[] = [
                'id' => $cause->id,
                'category' => $cause->category,
                'description' => $cause->description,
                'relations' => $total,
                'selected' => $selected,
            ];

        }

        usort($result, function ($a, $b) {
            return $b['relations'] <=> $a['relations'];
        });

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'non_conformity_id' => 'required|exists:non_conformities,id',

            'finish' => 'nullable|boolean',

            'causes' => 'nullable|array',

            'causes.*' => 'exists:ishikawa_causes,id',

        ]);

        DB::transaction(function () use ($data) {

            // Elimina las causas previamente seleccionadas
            ActionPlanCause::where(
                'non_conformity_id',
                $data['non_conformity_id']
            )->delete();

            // Guarda las nuevas causas seleccionadas
            foreach ($data['causes'] ?? [] as $causeId) {

                $ishikawaCause = IshikawaCause::findOrFail($causeId);

                ActionPlanCause::create([

                    'non_conformity_id' => $data['non_conformity_id'],

                    'ishikawa_cause_id' => $causeId,

                    'main_cause' => $ishikawaCause->description,

                ]);

            }

            // Si finaliza, cambia el estado
            if (!empty($data['finish'])) {

                NonConformity::findOrFail(
                    $data['non_conformity_id']
                )->update([

                    'status' => 'action_plan_pending',

                ]);

            }

        });

        return response()->json([

            'message' => !empty($data['finish'])
                ? 'Pareto finalizado correctamente.'
                : 'Pareto guardado correctamente.',

        ]);
    }
}