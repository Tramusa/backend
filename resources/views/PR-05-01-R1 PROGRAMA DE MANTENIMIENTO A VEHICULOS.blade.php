<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>PROGRAMA DE MANTENIMIENTO A VEHÍCULOS</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
        }

        table {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }

        .header-table td {
            border: 1px solid #000;
        }

        .logo {
            max-height: 55px;
        }

        .company {
            font-size: 14px;
            font-weight: bold;
            color: #1E4E79;
            text-align: center;
        }

        .title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }

        .subtitle {
            font-size: 9px;
            background: #F4B083;
            text-align: center;
            padding: 4px;
            font-weight: bold;
        }

        .menu {
            background: #1E4E79;
            color: white;
            font-weight: bold;
        }

        .unit-header th {
            background: #54afffff;
            color: #000;
            font-size: 10px;
            text-align: center;
            padding-left: 6px;
        }

        .activity {
            text-align: left;
            background: #cfcfcfff;
        }

        .month-grey {
            background: #9e9d9c;
        }

        .month-white {
            background: #ffffff;
        }

        .current-week {
            background: #E66A00 !important;
            color: white;
            font-weight: bold;
            border: 2px solid #000;
        }

        .info-table {
            width: 35%;
            float: right;
            margin-bottom: 8px;
        }

        .x-cell {
            background-color: #77c970ff;
            color: #000;
            font-weight: bold;
        }

        /* Fila inactiva completa */
        .inactive-row {
            background-color: #fee2e2; /* rojo claro */
        }

        .inactive-row td {
            color: #b91c1c; /* texto rojo */
            font-weight: bold;
            text-align: left;
        }

        /* Etiqueta "INACTIVA" dentro de la celda de actividad */
        .inactive-label {
            font-size: 8px;
            font-weight: 600;
            background-color: #fecaca; /* rojo más claro */
            color: #b91c1c;
            padding: 1px 4px;
            border-radius: 3px;
            margin-left: 5px;
        }

        .current-week-body {
            background: #E66A00;
            color: white;
            font-weight: bold;
        }
        .x-cell-done {
            background-color: #1bad51; /* verde */
            color: #000;
            font-weight: bold;
        }

        .x-cell-pending {
            background-color: #f97316; /* naranja */
            color: #000;
            font-weight: bold;
        }

    </style>
</head>

<body>
    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td width="20%" align="center">
                @if($logoImage)
                    <img class="logo" src="{{ $logoImage }}">
                @endif
            </td>
            <td width="80%">
                <div class="company">TRAMUSA CARRIER S.A. DE C.V.</div>
                <div class="title">PROGRAMA DE MANTENIMIENTO A VEHÍCULOS</div>
                <div class="subtitle">
                    ÁREA: MANTENIMIENTO | PR-05-01-R1 | PERIODICIDAD: ANUAL
                </div>
            </td>
        </tr>
    </table>

    <!-- FECHA -->
    <table class="info-table">
        <tr>
            <th class="menu">Fecha</th>
            <th class="menu">Semana</th>
        </tr>
        <tr>
            <td>{{ $fecha }}</td>
            <td>{{ $currentWeek }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    @php
        $months = [
            ['name'=>'ENE','weeks'=>4,'class'=>'month-white'],
            ['name'=>'FEB','weeks'=>5,'class'=>'month-grey'],
            ['name'=>'MAR','weeks'=>4,'class'=>'month-white'],
            ['name'=>'ABR','weeks'=>4,'class'=>'month-grey'],
            ['name'=>'MAY','weeks'=>5,'class'=>'month-white'],
            ['name'=>'JUN','weeks'=>4,'class'=>'month-grey'],
            ['name'=>'JUL','weeks'=>5,'class'=>'month-white'],
            ['name'=>'AGO','weeks'=>4,'class'=>'month-grey'],
            ['name'=>'SEP','weeks'=>4,'class'=>'month-white'],
            ['name'=>'OCT','weeks'=>4,'class'=>'month-grey'],
            ['name'=>'NOV','weeks'=>4,'class'=>'month-white'],
            ['name'=>'DIC','weeks'=>5,'class'=>'month-grey'],
        ];

        $weekClasses = [];
        $week = 1;

        foreach ($months as $month) {
            for ($i = 0; $i < $month['weeks']; $i++) {
                $weekClasses[$week] = $month['class'];
                $week++;
            }
        }
    @endphp

    <table>
        <!-- MESES -->
        <tr class="menu">
            <th rowspan="2" class="menu">ACTIVIDAD</th>
            @foreach($months as $month)
                <th colspan="{{ $month['weeks'] }}">{{ $month['name'] }}</th>
            @endforeach
        </tr>

        <!-- SEMANAS -->
        <tr>
            @for($i = 1; $i <= 52; $i++)
                <th class="{{ ($i == $currentWeek ? 'current-week ' : '') . ($weekClasses[$i] ?? '') }}">
                    {{ $i }}
                </th>
            @endfor
        </tr>

        @php $prevUnit = null; @endphp

        @foreach($activities as $activity)

            @if($prevUnit !== $activity['no_economic'])
                <tr class="unit-header">
                    <th colspan="53">Unidad: {{ $activity['no_economic'] }}</th>
                </tr>
            @endif

            <!-- Fila de actividad -->
            <tr class="{{ $activity['active'] === 0 ? 'inactive-row' : '' }}">
                <!-- Celda de actividad -->
                <td class="{{ $activity['active'] === 0 ? '' : 'activity' }}">
                    {{ $activity['activity'] }}
                    @if($activity['active'] === 0)
                        <span class="inactive-label">INACTIVA</span>
                    @endif
                </td>

                <!-- Celdas de semanas -->
                @php
                    $weeksNumbers = collect($activity['weeks'])->pluck('week')->toArray();
                    $weeksStatus  = collect($activity['weeks'])->keyBy('week');
                @endphp

                @for($i = 1; $i <= 52; $i++)
                    @php
                        $exists = in_array($i, $weeksNumbers);
                        $status = $weeksStatus[$i]['status'] ?? null;
                    @endphp

                    <td class="
                        @if($activity['active'] === 0)
                            inactive-row
                        @elseif($exists)
                            {{ ($status === 'done' || $status === 'late') ? 'x-cell-done' : 'x-cell-pending' }}
                        @elseif($i == $currentWeek)
                            current-week-body
                        @else
                            {{ $weekClasses[$i] }}
                        @endif
                    ">
                        @if($activity['active'] !== 0 && $exists)
                            X
                        @endif
                    </td>
                @endfor
            </tr>

            @php $prevUnit = $activity['no_economic']; @endphp

        @endforeach
    </table>
</body>
</html>