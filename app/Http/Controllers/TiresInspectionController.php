<?php

namespace App\Http\Controllers;

use App\Models\HistoryTire;
use App\Models\Inspections;
use App\Models\TireInspection;
use App\Models\TireInspectionDetail;
use App\Models\TiresControl;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Log\Logger;

class TiresInspectionController extends Controller
{
    public function index()
    {
        return TireInspection::with('user') // Trae el usuario relacionado
            ->leftJoin('units_all', function ($join) {
                $join->on('units_all.unit_id', '=', 'tire_inspections.unit_id')
                    ->on('units_all.type', '=', 'tire_inspections.type');
            })
            ->select(
                'tire_inspections.*',
                'units_all.no_economic'
            )
            ->orderBy('tire_inspections.id', 'desc')
            ->get();
    }

    public function show($id)
    {
        return TireInspection::with('details')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required',
            'type' => 'required',
            'inspection_date' => 'required|date',
            'status' => 'required',
            'details' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            $inspection = TireInspection::create([
                'unit_id' => $request->unit_id,
                'type' => $request->type,
                'status' => $request->status,
                'inspection_date' => $request->inspection_date,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->details as $detail) {

                TireInspectionDetail::create([
                    'inspection_id' => $inspection->id,
                    'tire_id' => $detail['tire_id'],
                    'psi' => $detail['psi'] ?? null,

                    'internal_1' => $detail['internal_1'] ?? null,
                    'internal_2' => $detail['internal_2'] ?? null,
                    'internal_3' => $detail['internal_3'] ?? null,

                    'center_1' => $detail['center_1'] ?? null,
                    'center_2' => $detail['center_2'] ?? null,
                    'center_3' => $detail['center_3'] ?? null,

                    'external_1' => $detail['external_1'] ?? null,
                    'external_2' => $detail['external_2'] ?? null,
                    'external_3' => $detail['external_3'] ?? null,

                    'average' => $detail['average'] ?? null,
                    'observations' => $detail['observations'] ?? null,
                ]);

                // 🔥 SOLO SI FINALIZA
                if ((int)$request->status === 1) {

                    // actualizar media
                    if (!empty($detail['average'])) {
                        TiresControl::where('id', $detail['tire_id'])
                            ->update(['tread_depth' => $detail['average']]);
                    }

                    // historial
                    HistoryTire::create([
                        'tire_ctrl' => $detail['tire_id'],
                        'activity' => 'Revisión',
                        'details' =>
                            'Revisión finalizada | PSI: ' . ($detail['psi'] ?? 'N/A') .
                            ' | Media: ' . ($detail['average'] ?? 'N/A') .
                            ' | Obs: ' . ($detail['observations'] ?? 'Sin observaciones'),
                        'date' => now(),
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Revisión creada correctamente',
                'inspection' => $inspection->load('details')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error al crear la revisión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $inspection = TireInspection::findOrFail($id);

        $request->validate([
            'unit_id' => 'required',
            'type' => 'required',
            'inspection_date' => 'required|date',
            'status' => 'required',
            'details' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            // 🔥 detectar si se está finalizando AHORA
            $wasPending = (int)$inspection->status === 0;
            $isFinalizingNow = $wasPending && (int)$request->status === 1;

            // actualizar encabezado
            $inspection->update([
                'unit_id' => $request->unit_id,
                'type' => $request->type,
                'status' => $request->status,
                'inspection_date' => $request->inspection_date,
            ]);

            foreach ($request->details as $detail) {

                $existingDetail = TireInspectionDetail::where('inspection_id', $inspection->id)
                    ->where('tire_id', $detail['tire_id'])
                    ->first();

                if ($existingDetail) {

                    $existingDetail->update([
                        'psi' => $detail['psi'] ?? $existingDetail->psi,

                        'internal_1' => $detail['internal_1'] ?? $existingDetail->internal_1,
                        'internal_2' => $detail['internal_2'] ?? $existingDetail->internal_2,
                        'internal_3' => $detail['internal_3'] ?? $existingDetail->internal_3,

                        'center_1' => $detail['center_1'] ?? $existingDetail->center_1,
                        'center_2' => $detail['center_2'] ?? $existingDetail->center_2,
                        'center_3' => $detail['center_3'] ?? $existingDetail->center_3,

                        'external_1' => $detail['external_1'] ?? $existingDetail->external_1,
                        'external_2' => $detail['external_2'] ?? $existingDetail->external_2,
                        'external_3' => $detail['external_3'] ?? $existingDetail->external_3,

                        'average' => $detail['average'] ?? $existingDetail->average,
                        'observations' => $detail['observations'] ?? $existingDetail->observations,
                    ]);

                } else {

                    TireInspectionDetail::create([
                        'inspection_id' => $inspection->id,
                        'tire_id' => $detail['tire_id'],
                        'psi' => $detail['psi'] ?? null,

                        'internal_1' => $detail['internal_1'] ?? null,
                        'internal_2' => $detail['internal_2'] ?? null,
                        'internal_3' => $detail['internal_3'] ?? null,

                        'center_1' => $detail['center_1'] ?? null,
                        'center_2' => $detail['center_2'] ?? null,
                        'center_3' => $detail['center_3'] ?? null,

                        'external_1' => $detail['external_1'] ?? null,
                        'external_2' => $detail['external_2'] ?? null,
                        'external_3' => $detail['external_3'] ?? null,

                        'average' => $detail['average'] ?? null,
                        'observations' => $detail['observations'] ?? null,
                    ]);
                }

                // 🔥 SOLO SI SE ESTÁ FINALIZANDO AHORA (NO REPETIR)
                if ($isFinalizingNow) {

                    if (!empty($detail['average'])) {
                        TiresControl::where('id', $detail['tire_id'])
                            ->update(['tread_depth' => $detail['average']]);
                    }

                    HistoryTire::create([
                        'tire_ctrl' => $detail['tire_id'],
                        'activity' => 'Revisión',
                        'details' =>
                            'Revisión finalizada | PSI: ' . ($detail['psi'] ?? 'N/A') .
                            ' | Media: ' . ($detail['average'] ?? 'N/A') .
                            ' | Obs: ' . ($detail['observations'] ?? 'Sin observaciones'),
                        'date' => now(),
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Revisión actualizada correctamente',
                'inspection' => $inspection->load('details')
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Error al actualizar la revisión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $inspection = TireInspection::with('details')->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | 🔥 HISTORIAL (DESACTIVADO POR AHORA)
            |--------------------------------------------------------------------------
            | Aquí se registraba que se eliminó la revisión por cada llanta.
            |
            foreach ($inspection->details as $detail) {
                HistoryTire::create([
                    'tire_ctrl' => $detail->tire_id,
                    'activity' => 'REVISION_ELIMINADA',
                    'details' => 'Se eliminó una revisión de llanta (ID inspección: ' . $inspection->id . ')',
                    'date' => now(),
                    'user_id' => Auth::id(),
                ]);
            }
            */

            // 🔥 BORRAR DETALLES
            TireInspectionDetail::where('inspection_id', $id)->delete();

            // 🔥 BORRAR ENCABEZADO
            $inspection->delete();

            DB::commit();

            return response()->json([
                'message' => 'Revisión eliminada correctamente'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Error al eliminar la revisión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTiresByUnit($type, $unit)
    {
        return TiresControl::where('type_unit', $type)
            ->where('assignment', $unit)
            ->where('status', '!=', 'scrapped')
            ->orderBy('position')
            ->get();
    }

    private function getImageBase64($path)
    {
        // 1️⃣ Si viene una ruta absoluta (public_path)
        if (file_exists($path)) {
            $file = file_get_contents($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        // 2️⃣ Si viene una ruta de Storage (public/...)
        if (Storage::exists($path)) {
            $file = Storage::get($path);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            return 'data:image/' . $extension . ';base64,' . base64_encode($file);
        }

        return null;
    }

    public function generateInspectionPDF($id)
    {
        try {

            // 🔥 INSPECCIÓN
            $inspection = TireInspection::with([
                'details.tire',
                'user'
            ])->findOrFail($id);

            // 🔥 UNIDAD
            $unit = DB::table('units_all')
                ->where('unit_id', $inspection->unit_id)
                ->where('type', $inspection->type)
                ->first();

            // 🔥 IMÁGENES
            $logo = $this->getImageBase64(public_path('imgPDF/logo.png'));
            $img1 = $this->getImageBase64(public_path('imgPDF/tire-guide-1.jpg'));
            $img2 = $this->getImageBase64(public_path('imgPDF/tire-guide-2.jpg'));
            
            // 🔥 DATA
            $data = [
                'inspection' => $inspection,
                'unit' => $unit,
                'logo' => $logo,
                'img1' => $img1,
                'img2' => $img2,
            ];

            // 🔥 HTML
            $html = view('F-05-23 R2 INSPECCION DE NEUMATICOS', $data)->render();

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al generar PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}