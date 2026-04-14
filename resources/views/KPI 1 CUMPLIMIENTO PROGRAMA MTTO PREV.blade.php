<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>KPI1-CUMPLIMIENTO PROGRAMA MTTO PREV</title>

<style>
body {
    width: 19cm;
    height: 29.7cm;
    margin: 0 auto;
    font-size: 11px;
    font-family: "Arial Narrow", Arial, sans-serif;
    color: #000;
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
    vertical-align: middle;
    padding: 10px;
}

.header-logo img {
    width: 100%;
}

.header-title {
    width: 78%;
    display: table-cell;
    text-align: center;
    vertical-align: middle;
    padding: 10px 5px;
}

.company-name {
    color: #0073B5;
    font-weight: bold;
    font-size: 16px;
}

.document-title {
    font-weight: bold;
    font-size: 14px;
    margin-top: 4px;
}

.header-bar {
    background: #F4B083;
    color: #fff;
    font-size: 11px;
    padding: 6px;
    text-align: center;
}

/* ===== TABLE ===== */

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #1E4E79;
    color: #fff;
    font-size: 11px;
    padding: 6px;
    border: 1px solid #000;
    text-align: center;
}

td {
    border: 1px solid #000;
    padding: 3px 4px;
    text-align: center;
    font-size: 11px;
}

/* BADGES */

.badge {
    color: #fff;
    padding: 3px 8px;
    border-radius: 5px;
    font-weight: bold;
    display: inline-block;
    min-width: 60px;
}

.badge-green { background: #28a745; }
.badge-yellow { background: #ffc107; color:#000; }
.badge-red { background: #dc3545; }

.footer {
    margin-top: 15px;
    font-size: 10px;
    text-align: right;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header-box">
  
    @php
        $meses = [ 1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE',
        ];

        if (strtolower($month) === 'anual') {
            $periodoTexto = 'ANUAL';
        } elseif (is_numeric($month) && isset($meses[(int)$month])) {
            $periodoTexto = $meses[(int)$month];
        } else {
            $periodoTexto = strtoupper($month);
        }
    @endphp
    <div class="header-row">

        <div class="header-logo">
            <img src="{{ $logoImage }}">
        </div>

        <div class="header-title">
            <div class="company-name">
                TRAMUSA CARRIER S.A. DE C.V.
            </div>

            <div class="document-title">
                CUMPLIMIENTO PROGRAMA MTTO PREVENTIVOS
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

<main>

    <!-- ================= TABLA UNIDADES ================= -->

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Unidad</th>
                <th>Prog.</th>
                <th>Realizadas</th>
                <th>% KPI</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($units as $i => $u)

            @php
            $badgeClass = 'badge-red';
            if ($u['kpi1'] >= 95) $badgeClass = 'badge-green';
            elseif ($u['kpi1'] >= 85) $badgeClass = 'badge-yellow';
            @endphp

                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $u['no_economico'] }}</td>
                    <td>{{ number_format($u['total_programadas'],0) }}</td>
                    <td>{{ number_format($u['realizadas'],0) }}</td>
                    <td>
                        <span class="badge {{ $badgeClass }}">
                            {{ number_format($u['kpi1'],2) }}%
                        </span>
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table><br><br><br><br><br><br>

    <!-- ================= GRAFICA ================= -->

    <h3 style="text-align:center;">
        CUMPLIMIENTO MENSUAL (Meta 90%)
    </h3>

    <div style="width:100%; text-align:center;">
        <div style=" width:100%; height:200px; position:relative; border:1px solid #ccc; overflow:hidden; background:#f5f5f5; ">

            <!-- META -->
            <div style=" position:absolute; top:20%; left:0; width:100%; border-top:2px solid red; z-index:10; ">
                <span style=" position:absolute; right:5px; top:-8px; font-size:10px; color:red; background:#f5f5f5; padding:0 2px; ">
                    Meta 90%
                </span>
            </div>

            @foreach ($months as $m)

                @php
                $percent = $m['kpi1'] ?? 0;
                $height = min($percent,100);

                $color = '#28a745';
                if ($percent < 85) $color = '#dc3545';
                elseif ($percent < 90) $color = '#ffc107';
                @endphp

                <div style=" display:inline-block; width:6%; height:100%; margin:0 4px; position:relative; vertical-align:bottom; ">

                    <div style=" position:absolute; bottom:0; width:100%; height:{{ $height }}%; background:{{ $color }}; color:#fff; font-size:10px; text-align:center; ">

                        <span style="position:absolute; top:40%; left:0; right:0; font-weight:bold;">
                        {{ number_format($percent,0) }}%
                        </span>

                    </div>
                </div>

            @endforeach

        </div>

        <!-- MESES TEXTO -->
        <div style="margin-top:8px;">

            @foreach ($months as $m)
                <div style="display:inline-block;width:6%;margin:0 4px;text-align:center;font-size:11px;">
                    <div style="font-weight:bold;">
                        {{ strtoupper($m['mes']) }}
                    </div>

                    <div style="font-size:9px; color:#555;">
                        {{ $m['realizadas'] ?? 0 }}/{{ $m['total_programadas'] ?? 0 }}
                    </div>

                </div>
            @endforeach

        </div>
    </div><br><br>
</main>

<div class="footer">
Total de unidades: {{ count($units) }}
</div>

</body>
</html>