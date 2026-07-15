<?php

namespace App\Http\Controllers;

use App\Models\NonConformity;
use App\Models\Sisegac;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NonConformityController extends Controller
{
    /* ================= LIST ================= */
    public function index()
    {
        $data = NonConformity::with('responsibleUser')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($data);
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $request->validate([

            'number' => 'required|unique:non_conformities',
            'date' => 'required|date',
            'date_commitment' => 'nullable|date',
            'problem' => 'required',
            'detected' => 'required',
            'affects' => 'required',
            'responsible' => 'required',
            'area' => 'required',
            'type' => 'required|in:non_conformity,opportunity_improvement',

        ]);

        $item = NonConformity::create([

            'number' => $request->number,
            'date' => $request->date,
            'date_commitment' =>
                $request->date_commitment,
            'problem' => $request->problem,
            'detected' => $request->detected,
            'affects' => $request->affects,
            'responsible' => $request->responsible,
            'area' => $request->area,
            'type' => $request->type,
            'status' => 'evaluation_pending'

        ]);

        return response()->json([
            'message' => 'Registro creado correctamente',
            'data' => $item
        ], 201);
    }

    /* ================= SHOW ================= */
    public function show($id)
    {
        $item = NonConformity::findOrFail($id);
        return response()->json($item);
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, $id)
    {
        $item = NonConformity::findOrFail($id);

        $request->validate([
            'number' => 'required|unique:non_conformities,number,' . $id,
            'date' => 'required|date',
            'date_commitment' => 'nullable|date',
            'problem' => 'required',
            'detected' => 'required',
            'affects' => 'required',
            'responsible' => 'required',
            'area' => 'required',
            'type' => 'required|in:non_conformity,opportunity_improvement',
        ]);

        $item->update($request->all());

        return response()->json([
            'message' => 'No conformidad actualizada',
            'data' => $item
        ]);
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $item = NonConformity::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'No conformidad eliminada'
        ]);
    }

    public function getImageBase64($path)
    {
        if(!file_exists($path)){
            return null;
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public function generarCaratulaPDF(Request $request)
    {
        try {
            $id = $request->id;

            $nonConformity = NonConformity::with([
                'responsibleUser',
                'evaluation',
                'ishikawa',
                'ishikawa.causes',
                'actionPlanCauses',
                'actionPlanCauses.responsible',
                'actionPlanCauses.correctiveActions',
                'actionPlanCauses.correctiveActions.responsible',
                'actionPlanCauses.correctiveActions.activities',
                'actionPlanCauses.correctiveActions.activities.responsible',
            ])->findOrFail($id);

            /* ================= LOGO ================= */
            $logoImage = $this->getImageBase64(public_path('imgPDF/logo.png'));

            /* ================= HTML ================= */
            $html = view('F-10-05 REPORTE DE ACCIONES',
                compact(
                    'nonConformity',
                    'logoImage'
                )
            )->render();


            /* ================= PDF ================= */
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200)->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(),], 500);
        }
    }

    public function generarGeneralPDF($id)
    {
        try {

            $nonConformity = NonConformity::with([
                'responsibleUser',
                'evaluation',

                'ishikawa',
                'ishikawa.causes',

                'relation',

                'actionPlanCauses',
                'actionPlanCauses.responsible',
                'actionPlanCauses.correctiveActions',
                'actionPlanCauses.correctiveActions.responsible',
                'actionPlanCauses.correctiveActions.activities',
                'actionPlanCauses.correctiveActions.activities.responsible',

            ])->findOrFail($id);

            /* =======================================================
            | RELACIÓN
            =======================================================*/

            $causes = [];
            $matrix = [];
            $totals = [];
            $ranking = [];

            /* =======================================================
            | PARETO
            =======================================================*/

            $pareto = [];
            $totalFrequency = 0;

            if ($nonConformity->relation && $nonConformity->ishikawa) {

                /* Todas las causas */
                $causes = $nonConformity
                    ->ishikawa
                    ->causes
                    ->pluck('description')
                    ->values()
                    ->toArray();

                /* Matriz */
                $matrix = $nonConformity->relation->matrix ?? [];

                /* Inicializar */
                $totals = array_fill(0, count($causes), 0);

                foreach ($matrix as $key => $value) {

                    if (!$value) {
                        continue;
                    }

                    [$row,$col] = explode('-', $key);

                    $totals[$row]++;
                    $totals[$col]++;

                }

                /* Ranking */

                foreach ($causes as $i => $cause) {

                    $ranking[] = [

                        'no'    => $i + 1,

                        'cause' => $cause,

                        'total' => $totals[$i],

                    ];

                }

                usort($ranking,function($a,$b){

                    return $b['total'] <=> $a['total'];

                });

                /* ================= PARETO ================= */

                $totalFrequency = array_sum($totals);

                $accumulated = 0;

                $selected = true;

                foreach($ranking as $row){

                    $accumulated += $row['total'];

                    $percent = $totalFrequency > 0
                        ? ($row['total'] / $totalFrequency) * 100
                        : 0;

                    $accumulatedPercent = $totalFrequency > 0
                        ? ($accumulated / $totalFrequency) * 100
                        : 0;

                    if($accumulatedPercent > 80){
                        $selected = false;
                    }

                    $pareto[] = [

                        'number' => $row['no'],

                        'description' => $row['cause'],

                        'frequency' => $row['total'],

                        'accumulated' => $accumulated,

                        'percent' => $percent,

                        'accumulatedPercent' => $accumulatedPercent,

                        'selected' => $selected,

                    ];

                }

            }

            /* =======================================================
            | IMÁGENES
            =======================================================*/

            $logoImage = $this->getImageBase64(
                public_path('imgPDF/logo.png')
            );

            $diagrama = $this->getImageBase64(
                public_path('imgPDF/diagrama.png')
            );

            /* =======================================================
            | PDF
            =======================================================*/

            $html = view(
                'pdf.GENERAL DOC ACR',
                compact(
                    'nonConformity',

                    'logoImage',
                    'diagrama',

                    'causes',
                    'matrix',
                    'totals',
                    'ranking',

                    'pareto',
                    'totalFrequency'
                )
            )->render();

            $dompdf = new Dompdf();

            $dompdf->loadHtml($html);

            $dompdf->setPaper('A4','portrait');

            $dompdf->render();

            return response(
                $dompdf->output(),
                200
            )->header(
                'Content-Type',
                'application/pdf'
            );

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ],500);

        }
    }

    public function sisegac()
    {
        $records = NonConformity::with([
            'actionPlanCauses.correctiveActions.responsible',
            'actionPlanCauses.correctiveActions.sisegac',

            'actionPlanCauses.correctiveActions.activities.responsible',
            'actionPlanCauses.correctiveActions.activities.sisegac',
        ])
        ->orderBy('date', 'desc')
        ->get();

        $detections = [
            'AUDITORIA INTERNA DE CALIDAD' => 'AI',
            'AUDITORIA DE SERVICIO'        => 'AS',
            'QUEJA'                        => 'Q',
            'PRODUCTO NO CONFORME'         => 'PNC',
            'REVISIÓN DE INDICADORES'      => 'KPI',
            'IMPACTO AMBIENTAL'            => 'SGA',
            'INCIDENTE'                    => 'IS',
        ];

        $result = [];

        foreach ($records as $nonConformity) {

            $rows = [];

            foreach ($nonConformity->actionPlanCauses as $cause) {

                foreach ($cause->correctiveActions as $action) {

                    $sisegac = $action->sisegac;

                    /* ================= ACCION ================= */
                    $rows[] = [
                        'id'            => $action->id,
                        'type'          => 'action',
                        'description'   => $action->corrective_action,
                        'responsible'   => optional($action->responsible)->name,
                        'commitment'    => $action->commitment_date,

                        'typeCode'      => $sisegac?->type,

                        'jan' => $sisegac?->jan,
                        'feb' => $sisegac?->feb,
                        'mar' => $sisegac?->mar,
                        'apr' => $sisegac?->apr,
                        'may' => $sisegac?->may,
                        'jun' => $sisegac?->jun,
                        'jul' => $sisegac?->jul,
                        'aug' => $sisegac?->aug,
                        'sep' => $sisegac?->sep,
                        'oct' => $sisegac?->oct,
                        'nov' => $sisegac?->nov,
                        'dec' => $sisegac?->dec,

                        'months' => [
                            $sisegac?->jan ?? '',
                            $sisegac?->feb ?? '',
                            $sisegac?->mar ?? '',
                            $sisegac?->apr ?? '',
                            $sisegac?->may ?? '',
                            $sisegac?->jun ?? '',
                            $sisegac?->jul ?? '',
                            $sisegac?->aug ?? '',
                            $sisegac?->sep ?? '',
                            $sisegac?->oct ?? '',
                            $sisegac?->nov ?? '',
                            $sisegac?->dec ?? '',
                        ],

                        'progress'      => $sisegac?->progress ?? 0,
                        'closeDate'     => optional($sisegac?->close_date)
                                                ?->format('Y-m-d'),
                        'verifyDate'    => optional($sisegac?->next_verification)
                                                ?->format('Y-m-d'),

                        'overdue'       => $sisegac?->overdue ?? false,
                        'closed'        => $sisegac?->closed ?? false,
                        'recurrent'     => $sisegac?->recurrent ?? false,
                        'observations'  => $sisegac?->observations ?? '',
                    ];

                    /* ================= ACTIVIDADES ================= */
                    foreach ($action->activities as $activity) {

                        $sisegac = $activity->sisegac;

                        $rows[] = [
                            'id'            => $activity->id,
                            'type'          => 'activity',
                            'description'   => $activity->activity,
                            'responsible'   => optional($activity->responsible)->name,
                            'commitment'    => $activity->commitment_date,

                            'typeCode'      => $sisegac?->type,

                            'jan' => $sisegac?->jan,
                            'feb' => $sisegac?->feb,
                            'mar' => $sisegac?->mar,
                            'apr' => $sisegac?->apr,
                            'may' => $sisegac?->may,
                            'jun' => $sisegac?->jun,
                            'jul' => $sisegac?->jul,
                            'aug' => $sisegac?->aug,
                            'sep' => $sisegac?->sep,
                            'oct' => $sisegac?->oct,
                            'nov' => $sisegac?->nov,
                            'dec' => $sisegac?->dec,

                            'months' => [
                                $sisegac?->jan ?? '',
                                $sisegac?->feb ?? '',
                                $sisegac?->mar ?? '',
                                $sisegac?->apr ?? '',
                                $sisegac?->may ?? '',
                                $sisegac?->jun ?? '',
                                $sisegac?->jul ?? '',
                                $sisegac?->aug ?? '',
                                $sisegac?->sep ?? '',
                                $sisegac?->oct ?? '',
                                $sisegac?->nov ?? '',
                                $sisegac?->dec ?? '',
                            ],

                            'progress'      => $sisegac?->progress ?? 0,
                            'closeDate'     => optional($sisegac?->close_date)
                                                    ?->format('Y-m-d'),
                            'verifyDate'    => optional($sisegac?->next_verification)
                                                    ?->format('Y-m-d'),

                            'overdue'       => $sisegac?->overdue ?? false,
                            'closed'        => $sisegac?->closed ?? false,
                            'recurrent'     => $sisegac?->recurrent ?? false,
                            'observations'  => $sisegac?->observations ?? '',
                        ];
                    }
                }
            }

            $result[] = [
                'id' => $nonConformity->id,

                'folio' => $nonConformity->number,

                'problem' => $nonConformity->problem,

                'detection' => [
                    'short' =>
                        $detections[$nonConformity->detected]
                        ?? $nonConformity->detected,

                    'full' => $nonConformity->detected,
                ],

                'date' => $nonConformity->date,

                'rows' => $rows,
            ];
        }

        return response()->json($result);
    }

    public function saveSisegac(Request $request)
    {
        DB::beginTransaction();

        try {

            foreach ($request->records as $nonConformity) {

                foreach ($nonConformity['rows'] as $row) {

                    $data = [
                        'type' => $row['typeCode'] ?? null,

                        'jan' => $row['jan'] ?? null,
                        'feb' => $row['feb'] ?? null,
                        'mar' => $row['mar'] ?? null,
                        'apr' => $row['apr'] ?? null,
                        'may' => $row['may'] ?? null,
                        'jun' => $row['jun'] ?? null,
                        'jul' => $row['jul'] ?? null,
                        'aug' => $row['aug'] ?? null,
                        'sep' => $row['sep'] ?? null,
                        'oct' => $row['oct'] ?? null,
                        'nov' => $row['nov'] ?? null,
                        'dec' => $row['dec'] ?? null,

                        'progress' => $row['progress'] ?? 0,
                        'close_date' => $row['closeDate'] ?? null,
                        'next_verification' => $row['verifyDate'] ?? null,
                        'overdue' => $row['overdue'] ?? false,
                        'closed' => $row['closed'] ?? false,
                        'recurrent' => $row['recurrent'] ?? false,
                        'observations' => $row['observations'] ?? null,
                    ];

                    if ($row['type'] == 'action') {

                        Sisegac::updateOrCreate(
                            [ 'corrective_action_id' => $row['id'] ],

                            array_merge(
                                $data, [ 'activity_id' => null ]
                            )
                        );
                    }

                    if ($row['type'] == 'activity') {

                        Sisegac::updateOrCreate(
                            [ 'activity_id' => $row['id'] ],

                            array_merge(
                                $data, [ 'corrective_action_id' => null ]
                            )
                        );
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Guardado correctamente'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ],500);
        }
    }
}