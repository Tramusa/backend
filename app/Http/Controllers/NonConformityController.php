<?php

namespace App\Http\Controllers;

use App\Models\NonConformity;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

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

    public function generarPDF(Request $request)
    {
        try {
            $id = $request->id;

            $nonConformity = NonConformity::with('responsible')->findOrFail($id);

            /* ================= DATOS FICTICIOS ================= */
            $causes = [
                [
                    'cause' => 'Falta de seguimiento al procedimiento operativo',
                    'actions' => [
                        [
                            'action' => 'Capacitar al personal involucrado',
                            'responsible' => 'Juan Pérez',
                            'date_commitment' => '2026-05-20',

                            'activities' => [
                                [
                                    'activity' => 'Programar capacitación',
                                    'responsible' => 'Luis Torres',
                                    'date_commitment' => '2026-05-10'
                                ],

                                [
                                    'activity' => 'Evaluar conocimientos',
                                    'responsible' => 'María López',
                                    'date_commitment' => '2026-05-18'
                                ]
                            ]
                        ]
                    ]
                ],

                [
                    'cause' => 'No existe evidencia documental',

                    'actions' => [

                        [
                            'action' => 'Implementar nuevo formato de control',
                            'responsible' => 'Carlos Medina',
                            'date_commitment' => '2026-05-25',

                            'activities' => [
                                [
                                    'activity' => 'Diseñar formato',
                                    'responsible' => 'Ana Ruiz',
                                    'date_commitment' => '2026-05-15'
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            /* ================= ARRAY GENERAL ================= */
            $data = [

                'number' => $nonConformity->number,
                'date' => $nonConformity->date,
                'date_commitment' => $nonConformity->date_commitment,
                'problem' => $nonConformity->problem,
                'detected' => $nonConformity->detected,
                'affects' => $nonConformity->affects,
                'area' => $nonConformity->area,
                'status' => $nonConformity->status,

                'responsible' => [
                    'name' => $nonConformity->responsible->name ?? '',
                    'a_paterno' => $nonConformity->responsible->a_paterno ?? '',
                ],

                'causes' => $causes
            ];

            /* ================= LOGO ================= */
            $logoImage = $this->getImageBase64(public_path('imgPDF/logo.png'));

            /* ================= HTML ================= */
            $html = view('PLAN DE ACCION', [
                'data' => $data,
                'logoImage' => $logoImage
            ])->render();

            /* ================= PDF ================= */
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function generarCaratulaPDF(Request $request)
    {
        try {
            $id = $request->id;

            $nonConformity = NonConformity::with([
                'responsibleUser',
                'evaluation',
                'actionPlanCauses',
                'actionPlanCauses.responsible',
                'actionPlanCauses.correctiveActions',
                'actionPlanCauses.correctiveActions.responsible',
                'actionPlanCauses.correctiveActions.activities',
                'actionPlanCauses.correctiveActions.activities.responsible',
            ])->findOrFail($id);

            logger($nonConformity->responsibleUser);
            logger($nonConformity);


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
}