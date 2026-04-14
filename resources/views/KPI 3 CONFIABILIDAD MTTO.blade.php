<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>KPI3 - CONFIABILIDAD MTTO</title>

    <style>
        body {
            width: 19cm;
            height: 29.7cm;
            margin: 0 auto;
            font-size: 11px;
            font-family: "Arial Narrow", Arial, sans-serif;
        }

        /* ===== HEADER ===== */
        .header-box {
            width: 100%;
            border: 3px solid #BFBFBF;
            display: table;
            margin-bottom: 20px;
        }

        .header-row {
            display: table-row;
        }

        .header-logo {
            width: 22%;
            display: table-cell;
            border-right: 3px solid #BFBFBF;
            text-align: center;
            padding: 10px;
        }

        .header-logo img {
            width: 100%;
        }

        .header-title {
            width: 78%;
            display: table-cell;
            text-align: center;
            padding: 10px;
        }

        .company-name {
            color: #0073B5;
            font-weight: bold;
            font-size: 16px;
        }

        .document-title {
            font-weight: bold;
            font-size: 14px;
        }

        .header-bar {
            background: #F4B083;
            color: #fff;
            padding: 6px;
            font-size: 11px;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1E4E79;
            color: #fff;
            padding: 6px;
            border: 1px solid #000;
        }

        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        /* ===== BADGES ===== */
        .badge {
            padding: 3px 8px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            min-width: 60px;
            color: #fff;
        }

        .badge-green { background: #28a745; }
        .badge-yellow { background: #ffc107; color: #000; }
        .badge-red { background: #dc3545; }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 10px;
            font-size: 10px;
            text-align: right;
        }
    </style>
</head>

<body>

@php
    $meses = [
        1=>'ENERO',2=>'FEBRERO',3=>'MARZO',4=>'ABRIL',
        5=>'MAYO',6=>'JUNIO',7=>'JULIO',8=>'AGOSTO',
        9=>'SEPTIEMBRE',10=>'OCTUBRE',11=>'NOVIEMBRE',12=>'DICIEMBRE'
    ];

    if (strtolower($month) === 'anual') {
        $periodoTexto = 'ANUAL';
    } elseif (is_numeric($month) && isset($meses[(int)$month])) {
        $periodoTexto = $meses[(int)$month];
    } else {
        $periodoTexto = strtoupper($month);
    }
@endphp

<!-- ===== HEADER ===== -->
<div class="header-box">
    <div class="header-row">

        <div class="header-logo">
            <img src="{{ $logoImage }}">
        </div>

        <div class="header-title">
            <div class="company-name">
                TRAMUSA CARRIER S.A. DE C.V.
            </div>

            <div class="document-title">
                CONFIABILIDAD DE ACCIONES DE MANTENIMIENTO
            </div>

            <div class="header-bar">
                <strong>
                    PERIODO: {{ $periodoTexto }} |
                    LOGÍSTICA: {{ strtoupper($logistic) }} |
                    FECHA: {{ $fecha }}
                </strong>
            </div>
        </div>

    </div>
</div>

<!-- ===== TABLA ===== -->
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Unidad</th>
            <th>Realizadas</th>
            <th>Retrabajos</th>
            <th>% Confiabilidad</th>
        </tr>
    </thead>

    <tbody>
        @foreach($units as $i => $u)

            @php
                $kpi = $u['kpi3'] ?? 0;

                $badge = 'badge-red';
                if ($kpi >= 90) {
                    $badge = 'badge-green';
                } elseif ($kpi >= 85) {
                    $badge = 'badge-yellow';
                }
            @endphp

            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $u['no_economico'] }}</td>
                <td>{{ $u['realizadas'] }}</td>
                <td>{{ $u['retrabajos'] }}</td>
                <td>
                    <span class="badge {{ $badge }}">
                        {{ number_format($kpi, 2) }}%
                    </span>
                </td>
            </tr>

        @endforeach
    </tbody>
</table>

<br><br>

<!-- ===== GRAFICA ===== -->
<h3 style="text-align:center; margin-bottom:10px;">
    CUMPLIMIENTO MENSUAL (Meta 90%)
</h3>

<div style="width:100%; text-align:center;">

    <div style=" width:100%; height:200px; position:relative; border:1px solid #ccc; background:#f5f5f5; overflow:hidden;">

        <!-- META -->
        <div style="position:absolute; top:20%; left:0; width:100%; border-top:2px solid red; z-index:10;">
            <span style=" position:absolute; right:5px; top:-8px; font-size:10px; color:red; background:#f5f5f5; padding:0 2px;">
                Meta 90%
            </span>
        </div>

        @foreach ($months as $m)

            @php
                $percent = $m['kpi3'] ?? 0;
                $height = min($percent, 100);

                $color = '#28a745';
                if ($percent < 85) {
                    $color = '#dc3545';
                } elseif ($percent < 90) {
                    $color = '#ffc107';
                }
            @endphp

            <div style=" display:inline-block; width:5%; height:100%; margin:0 6px; position:relative; vertical-align:bottom;">

                <div style=" position:absolute; bottom:0; width:100%; height:{{ $height }}%; background:{{ $color }}; color:#fff; font-size:10px; text-align:center; z-index:5;">
                    <span style=" position:absolute; top:40%; left:0; right:0; font-weight:bold;">
                        {{ number_format($percent, 0) }}%
                    </span>
                </div>

            </div>

        @endforeach

    </div>

    <!-- ETIQUETAS -->
    <div style="margin-top:8px;">

        @foreach ($months as $m)

            <div style=" display:inline-block; width:5%; margin:0 6px; text-align:center; font-size:11px;">
                <div style="font-weight:bold;">
                    {{ strtoupper($m['mes']) }}
                </div>

                <div style="font-size:9px; color:#555;">
                    {{ $m['realizadas'] ?? 0 }}/{{ $m['total'] ?? 0 }}
                </div>
            </div>

        @endforeach

    </div>

</div>

<!-- ===== FOOTER ===== -->
<div class="footer">
    Total de unidades: {{ count($units) }}
</div>

</body>
</html>