<?php

namespace App\Http\Controllers;

use App\Models\ProgramsMttoVehicles;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProgramsMttoVehiclesController extends Controller
{
    public function index()
    {
        $programs = ProgramsMttoVehicles::query()
            ->join('units_all as u', function ($join) {
                $join->on('u.unit_id', '=', 'programs_mtto_vehicles.unit')
                    ->whereColumn('u.type', 'programs_mtto_vehicles.type');
            })
            ->with(['schedules' => function ($q) {
                $q->select('program_mtto_vehicle_id', 'week', 'status');
            }])
            ->orderBy('programs_mtto_vehicles.type')
            ->orderBy('u.no_economic')
            ->orderBy('programs_mtto_vehicles.activity')
            ->get();

        $grouped = [];

        foreach ($programs as $program) {
            $key = $program->type . '-' . $program->unit_id;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'type' => $program->type,
                    'unit_id' => $program->unit_id,
                    'no_economic' => $program->no_economic,
                    'activities' => [],
                ];
            }

            $grouped[$key]['activities'][] = [
                'id' => $program->id,
                'name' => $program->activity,
                'active' => $program->active,
                'weeks' => $program->schedules->map(function ($s) {
                                return [
                                    'week' => $s->week,
                                    'status' => $s->status, // done | late | pending
                                ];
                            })->values(),
            ];
        }

        return response()->json(array_values($grouped));
    }

    public function show($id)
    {
        $program = ProgramsMttoVehicles::query()
            ->join('units_all as u', function ($join) {
                $join->on('u.unit_id', '=', 'programs_mtto_vehicles.unit')
                    ->whereColumn('u.type', 'programs_mtto_vehicles.type');
            })
            ->with(['schedules' => function ($q) {
                $q->select('program_mtto_vehicle_id', 'week', 'status');
            }])
            ->where('programs_mtto_vehicles.id', $id)
            ->firstOrFail();

        return response()->json([
            'type' => $program->type,
            'unit_id' => $program->unit_id,
            'no_economic' => $program->no_economic,
            'activities' => [
                [
                    'id' => $program->id,
                    'name' => $program->activity,
                    'active' => $program->active,
                    'weeks' => $program->schedules->map(function ($s) {
                                    return [
                                        'week' => $s->week,
                                        'status' => $s->status, // done | late | pending
                                    ];
                                })->values(),
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|integer',
            'unit' => 'required|integer',
            'activity' => 'required|string|max:255',
        ]);

        $activity = ProgramsMttoVehicles::create([
            'type' => $request->type,
            'unit' => $request->unit,
            'activity' => $request->activity,
            'active' => true
        ]);

        return response()->json([
            'message' => 'Actividad registrada con éxito',
            'id' => $activity->id
        ], 201);
    }
    
    public function update(Request $request, $id)
    {
        ProgramsMttoVehicles::find($id)->update($request->all()); 
        return response()->json(['message' => 'Actividad actualizada exitosamente.'], 201);
    }
    
    public function destroy($id)
    {
        ProgramsMttoVehicles::find($id)->delete();
        return response()->json(['message' => 'Actividad eliminada exitosamente.'], 201);
    }

    public function generarPDF(Request $request)
    {
        $activities = $request->input('activities');

        if (!$activities || empty($activities)) {
            return response()->json(['message' => 'No se proporcionaron actividades'], 400);
        }

        // Ordenar por unidad
        $activities = collect($activities)
            ->sortBy('no_economic')
            ->values()
            ->all();

        // Fecha actual
        $fechaActual = Carbon::now();
        $fecha = $fechaActual->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
        $currentWeek = $fechaActual->week;

        // Logo en base64
        $logoImagePath = public_path('imgPDF/logo.png');
        $logoImage = $this->getImageBase64($logoImagePath);

        $data = [
            'logoImage'   => $logoImage,
            'activities'  => $activities,
            'fecha'       => $fecha,
            'currentWeek' => $currentWeek,
        ];

        // Renderizar la vista con los datos
        $html = view('PR-05-01-R1 PROGRAMA DE MANTENIMIENTO A VEHICULOS', $data)->render();
    
        // Configuración de Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $pdfContent = $dompdf->output();   
    
        // Generar un nombre único para el archivo PDF
        $filename = 'Programa_Mantenimiento_' . now()->format('Ymd_His') . '.pdf';
    
        // Guardar el PDF en el almacenamiento
        Storage::disk('public')->put('pdfs/' . $filename, $pdfContent);
    
        // Devolver el contenido del PDF
        return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
    }

    public function generarEXCEL(Request $request)
    {
        $rows = collect($request->activities)->map(function ($act) {

            $row = [$act['activity']];

            $weeks = collect($act['weeks']);
            $weeksByNumber = $weeks->keyBy('week');

            for ($i = 1; $i <= 52; $i++) {

                if ($weeksByNumber->has($i)) {

                    $status = $weeksByNumber[$i]['status'];

                    $row[] = ($status === 'done' || $status === 'late')
                        ? 'FF22C55E'   // verde
                        : 'FFFF9800';  // naranja

                } else {
                    $row[] = '';
                }
            }


            $row[] = $act['active'];
            $row[] = $act['unidad'] ?? '';
            $row[] = $act['no_economic'] ?? '';

            return $row;
        })->toArray();

        return Excel::download(new class($rows) implements WithEvents, WithColumnWidths {

            private $rows;

            public function __construct($rows)
            {
                $this->rows = $rows;
            }

            public function registerEvents(): array
            {
                return [
                    AfterSheet::class => function (AfterSheet $event) {

                        $sheet = $event->sheet->getDelegate();

                        /* ==========  HEADER SUPERIOR (IGUAL AL PDF) ============= */
                        $sheet->getRowDimension(1)->setRowHeight(50);
                        $sheet->getRowDimension(2)->setRowHeight(20);
                        $sheet->getRowDimension(3)->setRowHeight(15);

                        /* ---------- LOGO (SOLO COLUMNA A, 3 FILAS) ---------- */
                        $sheet->mergeCells('A1:A3');

                        $drawing = new Drawing();
                        $drawing->setName('Logo');
                        $drawing->setPath(public_path('imgPDF/logo.png'));
                        $drawing->setHeight(95);
                        $drawing->setCoordinates('A1');
                        $drawing->setOffsetX(5);
                        $drawing->setOffsetY(5);
                        $drawing->setWorksheet($sheet);

                        $sheet->getStyle('A1:A3')->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                            ->setVertical(Alignment::VERTICAL_CENTER);

                        /* ---------- EMPRESA ---------- */
                        $sheet->mergeCells('B1:BA1');
                        $sheet->setCellValue('B1', 'TRAMUSA CARRIER S.A. DE C.V.');
                        $sheet->getStyle('B1')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 14,
                                'color' => ['argb' => 'FF1E4E79']
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER
                            ],
                        ]);

                        /* ---------- TÍTULO ---------- */
                        $sheet->mergeCells('B2:BA2');
                        $sheet->setCellValue('B2', 'PROGRAMA DE MANTENIMIENTO A VEHÍCULOS');
                        $sheet->getStyle('B2')->applyFromArray([
                            'font' => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);

                        /* ---------- SUBTÍTULO ---------- */
                        $sheet->mergeCells('B3:BA3');
                        $sheet->setCellValue('B3', 'ÁREA: MANTENIMIENTO | PR-05-01-R1 | PERIODICIDAD: ANUAL');
                        $sheet->getStyle('B3')->applyFromArray([
                            'font' => ['bold' => true, 'size' => 10],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFF4B083']
                            ],
                        ]);

                        /* ---------- BORDES DEL HEADER ---------- */
                        $sheet->getStyle('A1:BA3')->getBorders()->getAllBorders()
                            ->setBorderStyle(Border::BORDER_THIN);

                        /* ======= ENCABEZADO MESES / SEMANAS ========== */

                        $sheet->mergeCells('A5:A6');
                        $sheet->setCellValue('A5', 'Actividad');
                        $sheet->getStyle('A5')->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FF1F4E78']
                            ],
                        ]);

                        $months = [
                            'ENE' => 5, 'FEB' => 4, 'MAR' => 4, 'ABR' => 4,
                            'MAY' => 4, 'JUN' => 5, 'JUL' => 4, 'AGO' => 4,
                            'SEP' => 5, 'OCT' => 4, 'NOV' => 4, 'DIC' => 5
                        ];

                        $col = 2;
                        foreach ($months as $month => $weeks) {
                            $start = Coordinate::stringFromColumnIndex($col);
                            $end   = Coordinate::stringFromColumnIndex($col + $weeks - 1);

                            $sheet->mergeCells("{$start}5:{$end}5");
                            $sheet->setCellValue("{$start}5", $month);

                            $sheet->getStyle("{$start}5")->applyFromArray([
                                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['argb' => 'FF1F4E78']
                                ],
                            ]);

                            $col += $weeks;
                        }

                        for ($i = 1; $i <= 52; $i++) {
                            $col = Coordinate::stringFromColumnIndex($i + 1);
                            $sheet->setCellValue("{$col}6", $i);
                            $sheet->getStyle("{$col}6")->getAlignment()
                                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        }

                        /* ========= DATOS POR UNIDAD ============== */

                        $startRow = 7;

                        $grouped = collect($this->rows)->groupBy(function ($row) {
                            return ($row[54] ?? '') . '|' . ($row[55] ?? '');
                        });

                        foreach ($grouped as $key => $activities) {

                            [$unidad, $eco] = explode('|', $key);
                            $lastCol = Coordinate::stringFromColumnIndex(53);

                            $sheet->mergeCells("A{$startRow}:{$lastCol}{$startRow}");
                            $sheet->setCellValue("A{$startRow}", "Unidad: {$eco}");
                            $sheet->getStyle("A{$startRow}")->applyFromArray([
                                'font' => ['bold' => true, 'size' => 13],
                                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => ['argb' => 'FF5FBAFC']
                                ],
                            ]);

                            $startRow++;

                            foreach ($activities as $row) {

                                $dataRow = array_slice($row, 0, 56);
                                $sheet->fromArray([$dataRow], null, "A{$startRow}", false);

                                $isActive = $row[53] ?? true;

                                if (!$isActive) {
                                    $sheet->setCellValue(
                                        "A{$startRow}",
                                        $sheet->getCell("A{$startRow}")->getValue() . ' (INACTIVA)'
                                    );

                                    $sheet->getStyle("A{$startRow}:{$lastCol}{$startRow}")
                                        ->applyFromArray([
                                            'font' => ['bold' => true, 'color' => ['argb' => 'FF8B0000']],
                                            'fill' => [
                                                'fillType' => Fill::FILL_SOLID,
                                                'startColor' => ['argb' => 'FFFCC6BB']
                                            ]
                                        ]);
                                }

                                for ($c = 2; $c <= 53; $c++) {

                                    $col  = Coordinate::stringFromColumnIndex($c);
                                    $cell = "{$col}{$startRow}";
                                    $color = $sheet->getCell($cell)->getValue();

                                    if ($color) {

                                        // Cambiamos el texto del color por X
                                        $sheet->setCellValue($cell, 'X');

                                        $sheet->getStyle($cell)->applyFromArray([
                                            'font' => [
                                                'bold' => true,
                                                'color' => ['argb' => 'FF000000']
                                            ],
                                            'alignment' => [
                                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                                'vertical' => Alignment::VERTICAL_CENTER
                                            ],
                                            'fill' => [
                                                'fillType' => Fill::FILL_SOLID,
                                                'startColor' => ['argb' => $color] // 👈 usa el color real
                                            ],
                                            'borders' => [
                                                'outline' => [
                                                    'borderStyle' => Border::BORDER_MEDIUM,
                                                    'color' => ['argb' => 'FF000000']
                                                ]
                                            ]
                                        ]);
                                    }
                                }

                                $startRow++;
                            }
                        }

                        /* ================= BORDES GENERALES ================= */
                        $lastRow = $sheet->getHighestRow();
                        $lastCol = Coordinate::stringFromColumnIndex(53);

                        $sheet->getStyle("A5:{$lastCol}{$lastRow}")
                            ->getBorders()->getAllBorders()
                            ->setBorderStyle(Border::BORDER_THIN);
                    }
                ];
            }

            public function columnWidths(): array
            {
                $widths = ['A' => 45];
                for ($i = 1; $i <= 52; $i++) {
                    $widths[Coordinate::stringFromColumnIndex($i + 1)] = 3;
                }
                return $widths;
            }

        }, 'Programa_Mantenimiento.xlsx');
    }

    private function getImageBase64($imagePath)
    {
        $file = file_get_contents($imagePath);
        $base64 = base64_encode($file);
        return 'data:image/png;base64,' . $base64;
    }
}