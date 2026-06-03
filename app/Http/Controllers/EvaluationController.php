<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\NonConformity;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $data = $request->validate([
            'non_conformity_id' => 'required',
            'evaluation_type' => 'required',
            'severity' => 'required',
            'detectability' => 'required',
            'occurrence' => 'required'
        ]);
        
        /*  NPR  */
        $npr = $data['severity'] * $data['detectability'] * $data['occurrence'];

        /*  RESULT  */
        $result = 'correction';
        $requiresAnalysis = false;

        /*  AC  */
        if ($data['evaluation_type'] === 'AC') {
            if ($npr >= 100) {
                $result = 'corrective_action';
                $requiresAnalysis = true;
            }
        }

        /*  AP  */
        if ($data['evaluation_type'] === 'AP') {
            if ($npr >= 100) {
                $result = 'cause_analysis';
                $requiresAnalysis = true;
            }
        }

        /*   SAVE   */
        $evaluation = Evaluation::create([
            ...$data,
            'npr' => $npr,
            'result' => $result,
            'requires_analysis' => $requiresAnalysis,
            'evaluated_by' => auth()->id()
        ]);

        /*   UPDATE FLOW   */
        $nonConformity = NonConformity::findOrFail(
            $data['non_conformity_id']
        );

        if ($requiresAnalysis) {
            $nonConformity->status = 'analysis_pending';
        } else {
            $nonConformity->status = 'action_plan_pending';
        }

        $nonConformity->save();

        return $evaluation;
    }
}