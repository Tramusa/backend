<?php

namespace App\Http\Controllers;

use App\Models\ActionPlanCause;
use App\Models\NonConformity;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ActionPlanController extends Controller
{
    public function show($id)
    {
        $nonConformity = NonConformity::with([
            'responsible',
            'actionPlanCauses.responsible',
            'actionPlanCauses.correctiveActions.responsible',
            'actionPlanCauses.correctiveActions.activities.responsible',
        ])->findOrFail($id);

        return response()->json($nonConformity);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'non_conformity_id' => 'required|exists:non_conformities,id',
            'finish' => 'nullable|boolean',

            'causes' => 'required|array|min:1',

            'causes.*.ishikawa_cause_id' => 'nullable|integer',
            'causes.*.main_cause' => 'nullable|string',
            'causes.*.commitment_date' => 'nullable|date',
            'causes.*.responsible_id' => 'nullable|exists:users,id',

            'causes.*.actions' => 'required|array|min:1',
            'causes.*.actions.*.corrective_action' => 'required|string',
            'causes.*.actions.*.commitment_date' => 'nullable|date',
            'causes.*.actions.*.responsible_id' => 'nullable|exists:users,id',

            'causes.*.actions.*.activities' => 'nullable|array',
            'causes.*.actions.*.activities.*.activity' => 'nullable|string',
            'causes.*.actions.*.activities.*.commitment_date' => 'nullable|date',
            'causes.*.actions.*.activities.*.responsible_id' => 'nullable|exists:users,id',
        ]);

        $nonConformity = NonConformity::findOrFail($data['non_conformity_id']);

        DB::transaction(function () use ($data, $nonConformity) {
            $nonConformity->actionPlanCauses()->delete();

            foreach ($data['causes'] as $causeData) {
                $cause = $nonConformity->actionPlanCauses()->create([
                    'ishikawa_cause_id' => $causeData['ishikawa_cause_id'] ?? null,
                    'main_cause' => $causeData['main_cause'] ?? null,
                    'commitment_date' => $causeData['commitment_date'] ?? null,
                    'responsible_id' => $causeData['responsible_id'] ?? null,
                ]);

                foreach ($causeData['actions'] as $actionData) {
                    $action = $cause->correctiveActions()->create([
                        'corrective_action' => $actionData['corrective_action'],
                        'commitment_date' => $actionData['commitment_date'] ?? null,
                        'responsible_id' => $actionData['responsible_id'] ?? null,
                        'status' => 'pending',
                    ]);

                    foreach ($actionData['activities'] ?? [] as $activityData) {
                        if (empty($activityData['activity'])) {
                            continue;
                        }

                        $action->activities()->create([
                            'activity' => $activityData['activity'],
                            'commitment_date' => $activityData['commitment_date'] ?? null,
                            'responsible_id' => $activityData['responsible_id'] ?? null,
                        ]);
                    }
                }
            }

            $nonConformity->update([
                'status' => !empty($data['finish'])
                    ? 'finished'
                    : 'action_plan_pending',
            ]);
        });

        return response()->json([
            'message' => !empty($data['finish'])
                ? 'Plan de acción finalizado correctamente'
                : 'Plan de acción guardado correctamente',
        ]);
    }

    private function getImageBase64($path)
    {
        if (file_exists($path)) {
            $file = file_get_contents($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        if (Storage::exists($path)) {
            $file = Storage::get($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        return null;
    }

    private function PDF($nonConformityId)
    {
        $nonConformity = NonConformity::with([
            'responsible',
            'actionPlanCauses.responsible',
            'actionPlanCauses.correctiveActions.responsible',
            'actionPlanCauses.correctiveActions.activities.responsible',
        ])->findOrFail($nonConformityId);

        $logoImage = $this->getImageBase64(public_path('imgPDF/logo.png'));

        $html = view('F-02-19 PLAN DE ACCION', [
            'nonConformity' => $nonConformity,
            'logoImage' => $logoImage,
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    public function generarPDF($nonConformityId)
    {
        $pdfContent = $this->PDF($nonConformityId);

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf');
    }
}