<?php

namespace App\Http\Controllers;

use App\Models\Retrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KpisController extends Controller
{
    public function kpi1(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;

        // Estados válidos como realizados
        $estatusRealizados = ['done', 'late'];

        /*
        |--------------------------------------------------------------------------
        | 📅 Relación MES → SEMANAS OPERATIVAS
        |--------------------------------------------------------------------------
        */

        $months = [
            'ENE' => 5, 'FEB' => 4, 'MAR' => 4, 'ABR' => 4,
            'MAY' => 4, 'JUN' => 5, 'JUL' => 4, 'AGO' => 4,
            'SEP' => 5, 'OCT' => 4, 'NOV' => 4, 'DIC' => 5
        ];

        /*
        |--------------------------------------------------------------------------
        | 🔥 Función Semana → Mes
        |--------------------------------------------------------------------------
        */

        $obtenerMesPorSemana = function ($week) use ($months) {

            $acumulado = 0;
            $numeroMes = 1;

            foreach ($months as $mes => $semanas) {

                $acumulado += $semanas;

                if ($week <= $acumulado) {
                    return $numeroMes;
                }

                $numeroMes++;
            }

            return 12;
        };

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Consulta base
        |--------------------------------------------------------------------------
        */

        $registros = DB::table('programs_mtto_vehicle_schedule as s')
            ->join('programs_mtto_vehicles as v', 'v.id', '=', 's.program_mtto_vehicle_id')
            ->join('units_all as u', function ($join) {
                $join->on('u.unit_id', '=', 'v.unit')
                    ->on('u.type', '=', 'v.type');
            })
            ->where('s.year', $year)
            ->select(
                's.*',
                'u.no_economic',
                'u.logistic',
                'u.type'
            )
            ->get()
            ->map(function ($item) use ($obtenerMesPorSemana) {

                // ✅ MES basado en SEMANA
                $item->mes = $obtenerMesPorSemana($item->week);

                return $item;
            });

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Separar por logística
        |--------------------------------------------------------------------------
        */

        $logisticas = [
            'personal' => collect(),
            'cc' => collect(),
            'utilitarios' => collect(),
        ];

        foreach ($registros as $registro) {

            $logistica = strtolower($registro->logistic);

            if (str_contains($logistica, 'personal')) {
                $logisticas['personal']->push($registro);
            }

            if (str_contains($logistica, 'cc')) {
                $logisticas['cc']->push($registro);
            }

            if (str_contains($logistica, 'utilitario')) {
                $logisticas['utilitarios']->push($registro);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Calcular KPIs
        |--------------------------------------------------------------------------
        */

        $resultado = [];

        foreach ($logisticas as $nombre => $items) {

            // ===== GENERAL =====
            $totalProgramadas = $items->count();

            $totalRealizadas = $items
                ->whereIn('status', $estatusRealizados)
                ->count();

            $kpiGeneral = $totalProgramadas > 0
                ? round(($totalRealizadas / $totalProgramadas) * 100, 2)
                : 0;

            /*
            |--------------------------------------------------------------------------
            | KPI POR MES
            |--------------------------------------------------------------------------
            */

            $meses = collect(range(1, 12))->map(function ($mes) use ($items, $estatusRealizados) {

                $itemsMes = $items->where('mes', $mes);

                $realizadasMes = $itemsMes
                    ->whereIn('status', $estatusRealizados);

                return [
                    'mes' => $mes,
                    'total_programadas' => $itemsMes->count(),
                    'realizadas' => $realizadasMes->count(),
                    'kpi1' => $itemsMes->count() > 0
                        ? round(($realizadasMes->count() / $itemsMes->count()) * 100, 2)
                        : 0
                ];
            });

            /*
            |--------------------------------------------------------------------------
            | KPI POR UNIDAD
            |--------------------------------------------------------------------------
            */

            $unidades = $items
                ->groupBy('no_economic')
                ->map(function ($unidadItems) use ($estatusRealizados) {

                    $programadas = $unidadItems->count();

                    $realizadas = $unidadItems
                        ->whereIn('status', $estatusRealizados)
                        ->count();

                    $mesesUnidad = collect(range(1, 12))->map(function ($mes) use ($unidadItems, $estatusRealizados) {

                        $itemsMes = $unidadItems->where('mes', $mes);

                        $realizadasMes = $itemsMes
                            ->whereIn('status', $estatusRealizados);

                        return [
                            'mes' => $mes,
                            'total_programadas' => $itemsMes->count(),
                            'realizadas' => $realizadasMes->count(),
                            'kpi1' => $itemsMes->count() > 0
                                ? round(($realizadasMes->count() / $itemsMes->count()) * 100, 2)
                                : 0
                        ];
                    });

                    return [
                        'no_economico' => $unidadItems->first()->no_economic,
                        'type' => $unidadItems->first()->type,
                        'total_programadas' => $programadas,
                        'realizadas' => $realizadas,
                        'kpi1' => $programadas > 0
                            ? round(($realizadas / $programadas) * 100, 2)
                            : 0,
                        'meses' => $mesesUnidad
                    ];
                })
                ->values();

            $resultado[$nombre] = [
                'general' => [
                    'total_programadas' => $totalProgramadas,
                    'realizadas' => $totalRealizadas,
                    'kpi1' => $kpiGeneral
                ],
                'meses' => $meses,
                'unidades' => $unidades
            ];
        }

        return response()->json([
            'kpi1' => $resultado
        ]);
    }

    public function kpi2(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ ÓRDENES TERMINADAS (SIN DUPLICAR HORAS)
        |--------------------------------------------------------------------------
        */

        $ordenes = DB::table('orders as o')
            ->join('order_details as od', 'od.id_order', '=', 'o.id')
            ->join('earrings as e', 'e.id', '=', 'od.id_earring')
            ->join('units_all as u', function ($join) {
                $join->on('u.unit_id', '=', 'e.unit')
                    ->on('u.type', '=', 'e.type');
            })
            ->whereYear('o.date', $year)
            ->where('o.status', 4)
            ->select(
                DB::raw('MONTH(o.date) as mes'),
                'o.id as order_id',
                'u.unit_id',
                'u.no_economic',
                'u.logistic',
                'u.type',
                DB::raw("
                    GREATEST(
                        TIMESTAMPDIFF(HOUR, o.date_in, o.date_attended),
                        0
                    ) as horas_mtto
                ")
            )
            ->groupBy(
                'o.id',
                'mes',
                'u.unit_id',
                'u.no_economic',
                'u.logistic',
                'u.type',
                'o.date_in',
                'o.date_attended'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ AGRUPAR POR LOGÍSTICA
        |--------------------------------------------------------------------------
        */

        $logisticas = [
            'personal' => collect(),
            'cc' => collect(),
            'utilitarios' => collect(),
        ];

        foreach ($ordenes as $orden) {

            $logistica = strtolower($orden->logistic);

            if (str_contains($logistica, 'personal'))
                $logisticas['personal']->push($orden);

            if (str_contains($logistica, 'cc'))
                $logisticas['cc']->push($orden);

            if (str_contains($logistica, 'utilitario'))
                $logisticas['utilitarios']->push($orden);
        }

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ KPI2
        |--------------------------------------------------------------------------
        */

        $resultado = [];

        foreach ($logisticas as $nombre => $items) {

            $unidades = $items
                ->groupBy('unit_id')
                ->map(function ($unidadItems) {

                    $first = $unidadItems->first();
                    $logistic = strtolower($first->logistic);

                    $horasProgramadasMes =
                        (str_contains($logistic,'personal') ||
                        str_contains($logistic,'utilitario'))
                            ? 97
                            : 100;

                    /*
                    |--------------------------------------------------------------------------
                    | 🔹 MESES POR UNIDAD
                    |--------------------------------------------------------------------------
                    */

                    $mesesUnidad = collect(range(1,12))->map(
                        function ($mes) use ($unidadItems,$horasProgramadasMes){

                        $itemsMes = $unidadItems->where('mes',$mes);

                        $horasMtto = $itemsMes->sum('horas_mtto');

                        // ✅ YA PERMITIMOS NEGATIVOS
                        $horasDisponibles =
                            $horasProgramadasMes - $horasMtto;

                        $percent =
                            $horasProgramadasMes > 0
                            ? round(
                                ($horasDisponibles /
                                $horasProgramadasMes) * 100,2)
                            : 0;

                        return [
                            'mes'=>$mes,
                            'horas_programadas'=>$horasProgramadasMes,
                            'horas_mtto'=>$horasMtto,
                            'horas_espera'=>0,
                            'horas_disponibles'=>$horasDisponibles,
                            'percent'=>$percent
                        ];
                    });

                    /*
                    |--------------------------------------------------------------------------
                    | 🔹 TOTAL ANUAL UNIDAD
                    |--------------------------------------------------------------------------
                    */

                    $horasProgramadasAnual =
                        $horasProgramadasMes * 12;

                    $horasMttoAnual =
                        $unidadItems->sum('horas_mtto');

                    $horasDisponiblesAnual =
                        $horasProgramadasAnual - $horasMttoAnual;

                    $percentAnual =
                        $horasProgramadasAnual > 0
                        ? round(
                            ($horasDisponiblesAnual /
                            $horasProgramadasAnual)*100,2)
                        : 0;

                    return [
                        'unit_id'=>$first->unit_id,
                        'no_economico'=>$first->no_economic,
                        'type'=>$first->type,
                        'horas_programadas'=>$horasProgramadasAnual,
                        'horas_mtto'=>$horasMttoAnual,
                        'horas_espera'=>0,
                        'horas_disponibles'=>$horasDisponiblesAnual,
                        'percent'=>$percentAnual,
                        'meses'=>$mesesUnidad
                    ];
                })
                ->values();

            /*
            |--------------------------------------------------------------------------
            | 🔹 GENERAL ANUAL
            |--------------------------------------------------------------------------
            */

            $totalProgramadas =
                $unidades->sum('horas_programadas');

            $totalMtto =
                $unidades->sum('horas_mtto');

            $totalDisponibles =
                $totalProgramadas - $totalMtto;

            $kpiGeneral =
                $totalProgramadas>0
                ? round(
                    ($totalDisponibles/$totalProgramadas)*100,2)
                : 0;

            /*
            |--------------------------------------------------------------------------
            | 🔹 GENERAL POR MES (MATEMÁTICAMENTE CORRECTO)
            |--------------------------------------------------------------------------
            */

            $meses = collect(range(1,12))->map(
                function($mes) use($unidades){

                $prog=0;
                $mtto=0;

                foreach($unidades as $u){

                    $m=$u['meses']->firstWhere('mes',$mes);

                    $prog += $m['horas_programadas'];
                    $mtto += $m['horas_mtto'];
                }

                $disp = $prog - $mtto;

                $percent =
                    $prog>0
                    ? round(($disp/$prog)*100,2)
                    : 0;

                return [
                    'mes'=>$mes,
                    'horas_programadas'=>$prog,
                    'horas_mtto'=>$mtto,
                    'horas_disponibles'=>$disp,
                    'percent'=>$percent
                ];
            });

            $resultado[$nombre]=[
                'general'=>[
                    'horas_programadas'=>$totalProgramadas,
                    'horas_mtto'=>$totalMtto,
                    'horas_disponibles'=>$totalDisponibles,
                    'kpi2'=>$kpiGeneral
                ],
                'meses'=>$meses,
                'unidades'=>$unidades
            ];
        }

        return response()->json(['kpi2'=>$resultado]);
    }

    public function kpi3(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ ÓRDENES TERMINADAS
        |--------------------------------------------------------------------------
        */

        $ordenes = DB::table('orders as o')
            ->join('order_details as od', 'od.id_order', '=', 'o.id')
            ->join('earrings as e', 'e.id', '=', 'od.id_earring')
            ->join('units_all as u', function ($join) {
                $join->on('u.unit_id', '=', 'e.unit')
                    ->on('u.type', '=', 'e.type');
            })
            ->whereYear('o.date', $year)
            ->where('o.status', 4)
            ->select(
                'o.id',
                DB::raw('MONTH(o.date) as mes'),
                'u.unit_id', // 🔥 IMPORTANTE
                'u.no_economic',
                'u.logistic',
                'u.type'
            )
            ->distinct()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ RETRABAJOS AGRUPADOS (unit_id_type_mes)
        |--------------------------------------------------------------------------
        */

        $retrabajosTabla = DB::table('retrabajos')
            ->where('year', $year)
            ->get()
            ->groupBy(function ($item) {
                return $item->unit . '_' . $item->type . '_' . $item->mes;
            });

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ SEPARAR POR LOGÍSTICA
        |--------------------------------------------------------------------------
        */

        $logisticas = [
            'personal' => collect(),
            'cc' => collect(),
            'utilitarios' => collect(),
        ];

        foreach ($ordenes as $orden) {

            $logistica = strtolower($orden->logistic);

            if (str_contains($logistica, 'personal')) {
                $logisticas['personal']->push($orden);
            }

            if (str_contains($logistica, 'cc')) {
                $logisticas['cc']->push($orden);
            }

            if (str_contains($logistica, 'utilitario')) {
                $logisticas['utilitarios']->push($orden);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ CALCULAR KPI3
        |--------------------------------------------------------------------------
        */

        $resultado = [];

        foreach ($logisticas as $nombre => $items) {

            $realizadas = $items->count();
            $retrabajosGeneral = 0;

            /*
            |--------------------------------------------------------------------------
            | 🔥 GENERAL POR MES
            |--------------------------------------------------------------------------
            */

            $meses = collect(range(1, 12))->map(function ($mes) use ($items, $retrabajosTabla) {

                $itemsMes = $items->where('mes', $mes);
                $realizadasMes = $itemsMes->count();
                $retrabajosMes = 0;

                $itemsMes->groupBy('unit_id')->each(function ($unidadItems) use (&$retrabajosMes, $retrabajosTabla, $mes) {

                    $unit = $unidadItems->first()->unit_id;
                    $type = $unidadItems->first()->type;

                    $key = $unit . '_' . $type . '_' . $mes;

                    if (isset($retrabajosTabla[$key])) {
                        $retrabajosMes += $retrabajosTabla[$key]->sum('cantidad');
                    }
                });

                $kpiMes = $realizadasMes > 0
                    ? round((($realizadasMes - $retrabajosMes) / $realizadasMes) * 100, 2)
                    : 0;

                return [
                    'mes' => $mes,
                    'realizadas' => $realizadasMes,
                    'retrabajos' => $retrabajosMes,
                    'kpi3' => $kpiMes
                ];
            });

            /*
            |--------------------------------------------------------------------------
            | 🔥 POR UNIDAD + MESES
            |--------------------------------------------------------------------------
            */

            $unidades = $items
                ->groupBy('unit_id')
                ->map(function ($unidadItems) use (&$retrabajosGeneral, $retrabajosTabla) {

                    $realizadasUnidad = $unidadItems->count();
                    $unit = $unidadItems->first()->unit_id;
                    $type = $unidadItems->first()->type;
                    $noEconomic = $unidadItems->first()->no_economic;

                    $retrabajosUnidad = 0;

                    $mesesUnidad = collect(range(1, 12))->map(function ($mes) use ($unidadItems, $retrabajosTabla, $unit, $type, &$retrabajosUnidad) {

                        $itemsMes = $unidadItems->where('mes', $mes);
                        $realizadasMes = $itemsMes->count();

                        $key = $unit . '_' . $type . '_' . $mes;

                        $retrabajosMes = 0;

                        if (isset($retrabajosTabla[$key])) {
                            $retrabajosMes = $retrabajosTabla[$key]->sum('cantidad');
                            $retrabajosUnidad += $retrabajosMes;
                        }

                        $kpiMes = $realizadasMes > 0
                            ? round((($realizadasMes - $retrabajosMes) / $realizadasMes) * 100, 2)
                            : 0;

                        return [
                            'mes' => $mes,
                            'realizadas' => $realizadasMes,
                            'retrabajos' => $retrabajosMes,
                            'kpi3' => $kpiMes
                        ];
                    });

                    $retrabajosGeneral += $retrabajosUnidad;

                    $kpiUnidad = $realizadasUnidad > 0
                        ? round((($realizadasUnidad - $retrabajosUnidad) / $realizadasUnidad) * 100, 2)
                        : 0;

                    return [
                        'unit_id' => $unit,
                        'no_economico' => $noEconomic,
                        'type' => $type,
                        'realizadas' => $realizadasUnidad,
                        'retrabajos' => $retrabajosUnidad,
                        'kpi3' => $kpiUnidad,
                        'meses' => $mesesUnidad
                    ];
                })
                ->values();

            /*
            |--------------------------------------------------------------------------
            | 🔥 KPI GENERAL ANUAL
            |--------------------------------------------------------------------------
            */

            $kpiGeneral = $realizadas > 0
                ? round((($realizadas - $retrabajosGeneral) / $realizadas) * 100, 2)
                : 0;

            $resultado[$nombre] = [
                'general' => [
                    'realizadas' => $realizadas,
                    'retrabajos' => $retrabajosGeneral,
                    'kpi3' => $kpiGeneral
                ],
                'meses' => $meses,
                'unidades' => $unidades
            ];
        }

        return response()->json([
            'kpi3' => $resultado
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|integer',
            'type'    => 'required|integer',
            'mes'     => 'required|integer|min:1|max:12',
            'year'    => 'required|integer',
            'cantidad'=> 'required|integer|min:0',
        ]);

        $retrabajo = Retrabajo::where([
            'unit' => $request->unit,
            'type'    => $request->type,
            'mes'     => $request->mes,
            'year'    => $request->year,
        ])->first();

        if ($retrabajo) {

            // ✅ Si existe → solo actualiza cantidad
            $retrabajo->update([
                'cantidad' => $request->cantidad
            ]);

            return response()->json([
                'message' => 'Retrabajo actualizado correctamente',
                'data' => $retrabajo
            ], 200);

        } else {

            // ✅ Si no existe → crear registro completo
            $nuevo = Retrabajo::create([
                'unit' => $request->unit,
                'type'    => $request->type,
                'mes'     => $request->mes,
                'year'    => $request->year,
                'cantidad'=> $request->cantidad,
            ]);

            return response()->json([
                'message' => 'Retrabajo creado correctamente',
                'data' => $nuevo
            ], 201);
        }
    }

}