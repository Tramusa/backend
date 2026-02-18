<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KpisController extends Controller
{
    public function kpi1(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;

        // Estados que cuentan como realizados
        $estatusRealizados = ['done', 'late'];

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Consulta base + agregar MES a cada registro
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
            ->map(function ($item) {
                $fecha = Carbon::createFromDate($item->year, 1, 1)
                    ->addWeeks($item->week - 1);

                $item->mes = $fecha->month;              // 🔥 número de mes
                $item->mes_nombre = $fecha->format('F'); // opcional
                return $item;
            });

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Inicializar estructura por logística
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

            // ===== MESES (GENERAL POR LOGÍSTICA) =====
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

            // ===== UNIDADES =====
            $unidades = $items
                ->groupBy('no_economic')
                ->map(function ($unidadItems) use ($estatusRealizados) {

                    $programadas = $unidadItems->count();

                    $realizadas = $unidadItems
                        ->whereIn('status', $estatusRealizados)
                        ->count();

                    // 🔥 KPI POR MES DENTRO DE LA UNIDAD
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

    public function kpi3(Request $request)
    {
        $year = $request->year ?? Carbon::now()->year;

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Traer órdenes TERMINADAS (status = 4) + MES
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
                'u.no_economic',
                'u.logistic',
                'u.type'
            )
            ->distinct()
            ->get();

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
        | 3️⃣ Calcular KPI3
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

            $meses = collect(range(1, 12))->map(function ($mes) use ($items) {

                $itemsMes = $items->where('mes', $mes);

                $realizadasMes = $itemsMes->count();

                $retrabajosMes = 0;

                // generar retrabajos aleatorio por unidad dentro del mes
                $itemsMes->groupBy('no_economic')->each(function () use (&$retrabajosMes) {
                    $retrabajosMes += rand(0, 2);
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
                ->groupBy('no_economic')
                ->map(function ($unidadItems) use (&$retrabajosGeneral) {

                    $realizadasUnidad = $unidadItems->count();

                    $retrabajos = rand(0, 2);
                    $retrabajosGeneral += $retrabajos;

                    $kpi3 = $realizadasUnidad > 0
                        ? round((($realizadasUnidad - $retrabajos) / $realizadasUnidad) * 100, 2)
                        : 0;

                    /*
                    |--------------------------------------------------------------------------
                    | 🔥 MESES DENTRO DE LA UNIDAD
                    |--------------------------------------------------------------------------
                    */

                    $mesesUnidad = collect(range(1, 12))->map(function ($mes) use ($unidadItems) {

                        $itemsMes = $unidadItems->where('mes', $mes);

                        $realizadasMes = $itemsMes->count();

                        $retrabajosMes = $realizadasMes > 0 ? rand(0, 2) : 0;

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

                    return [
                        'no_economico' => $unidadItems->first()->no_economic,
                        'type' => $unidadItems->first()->type,
                        'realizadas' => $realizadasUnidad,
                        'retrabajos' => $retrabajos,
                        'kpi3' => $kpi3,
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

}
