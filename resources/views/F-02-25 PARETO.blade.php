<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

    <style>
    @page{
        margin:8px;
    }

    *{
        box-sizing:border-box;
    }

    body{
        margin:0;
        font-family:Arial, Helvetica, sans-serif;
        font-size:11px;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    td,th{
        border:1px solid #000;
        padding:3px;
        vertical-align:middle;
    }

    .logo{
        width:120px;
    }

    .title{
        text-align:center;
        font-size:18px;
        font-weight:bold;
    }

    .subtitle{
        text-align:center;
        font-size:15px;
        font-weight:bold;
        margin-top:10px;
    }

    .orange{
        background:#d98b3f;
        color:#fff;
        text-align:center;
        font-weight:bold;
    }

    .blueHeader{
        background:#233f99;
        color:#fff;
        font-weight:bold;
        text-align:center;
    }

    .greenHeader{
        background:#16a34a;
        color:#fff;
        font-weight:bold;
        text-align:center;
    }

    .yellowHeader{
        background:#ffd42a;
        text-align:center;
        font-weight:bold;
    }

    .center{
        text-align:center;
    }

    .bold{
        font-weight:bold;
    }

    .small{
        font-size:10px;
    }

    .check{
        color:#1d4ed8;
        font-size:14px;
        font-weight:bold;
    }

    .total{
        background:#233f99;
        color:#fff;
        font-weight:bold;
        text-align:center;
    }
</style>

</head>

<body><br>

    {{-- ================= HEADER ================= --}}
    <table>

        <tr>
            <td style="width:170px;text-align:center;">
                @if($logoImage)
                    <img src="{{ $logoImage }}" class="logo">
                @endif
            </td>

            <td style="padding:0;">
                <div class="title" style="margin-top:10px;">
                    TRAMUSA CARRIER S.A. DE C.V.
                </div>
                <div class="subtitle">
                    PARETO
                </div>
                <table style="margin-top:10px;">
                    <tr class="orange">
                        <td style="width:55%;">ÁREA: COORDINACIÓN DE CALIDAD
                        </td>
                        <td style="width:20%;">
                            F-02-25
                        </td>
                        <td style="width:25%;">
                            REVISIÓN
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding:8px;font-size:12px;">
                <b>INSTRUCCIÓN:</b>
                Determinar las causas principales y su frecuencia de ocurrencia de relación con las demás causas.
            </td>
        </tr>

    </table><br>

    {{-- ================= TABLA ================= --}}
    <table style="font-size:10px;">
        <tr class="blueHeader">
            <td style="width:60px;">No.</td>
            <td>CAUSAS</td>
            <td style="width:90px;">FRECUENCIA</td>
            <td style="width:90px;">DESVIACIONES ACUMULADAS</td>
            <td style="width:95px;"> % DE CONTRIBUCIÓN</td>
            <td style="width:95px;"> CONTRIBUCIÓN ACUMULADA</td>
        </tr>

        @forelse($pareto as $row)
            <tr>
                <td class="center">
                    {{ $row['number'] }}
                </td>
                <td style="padding-left:8px;">
                    {{ $row['description'] }}
                </td>
                <td class="center">
                    {{ $row['frequency'] }}
                </td>
                <td class="center">
                    {{ $row['accumulated'] }}
                </td>
                <td class="center">
                    {{ number_format($row['percent'],1) }}%
                </td>
                <td class="center">
                    {{ number_format($row['accumulatedPercent'],1) }}%
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:15px;">
                    Sin información registrada.
                </td>
            </tr>
        @endforelse

        {{-- ================= TOTAL ================= --}}
        <tr class="total">
            <td colspan="2">TOTAL</td>
            <td> {{ $totalFrequency }} </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table><br><br>

    {{-- ================= GRÁFICA PARETO ================= --}}
    @php
        $axisMax = max(1, $totalFrequency);
        $step = $axisMax / 5;

        $chartHeight = 320; 
        $chartWidth = 560;

        $count = count($pareto);

        $space = $count > 0
            ? $chartWidth / $count
            : $chartWidth;

        $barWidth = min(28, $space * 0.45);

        $prevX = null;
        $prevY = null;
    @endphp

    <div style="margin-top:20px;">

        <div style="position:relative;width:700px;height:520px;margin:auto;">

            {{-- ================= TÍTULOS ================= --}}
            <div style="position:absolute;left:-50px;top:200px;transform:rotate(-90deg);font-size:12px;font-weight:bold;">
                FRECUENCIA ACUMULADA
            </div>
            <div style="position:absolute;right:-40px;top:200px;transform:rotate(90deg);font-size:12px;font-weight:bold;">
                CONTRIBUCIÓN %
            </div>

            {{-- ================= ÁREA GRÁFICA ================= --}}
            <div style="position:absolute;left:60px;top:20px;width:{{ $chartWidth }}px;height:{{ $chartHeight }}px;border:2px solid #444;background:#ECEEF2;">

                {{-- ================= CUADRÍCULA ================= --}}
                @for($i=0;$i<=5;$i++)
                    @php
                        $y = ($chartHeight/5)*$i;
                        $value = round($axisMax - ($step * $i));
                    @endphp

                    <div style="position:absolute;left:0;top:{{ $y }}px;width:100%;border-top:1px solid #d6d6d6;">
                    </div>

                    <div style="position:absolute;left:-38px;top:{{ $y-7 }}px;width:30px;text-align:right;font-size:10px;">
                        {{ $value }}
                    </div>
                @endfor

                {{-- ================= EJE DERECHO ================= --}}
                @for($i=0;$i<=5;$i++)
                    @php
                        $y = ($chartHeight/5)*$i;
                        $percent = 100-($i*20);
                    @endphp

                    <div style="position:absolute;right:-40px;top:{{ $y-7 }}px;width:30px;font-size:10px;text-align:left;">
                        {{ $percent }}%
                    </div>
                @endfor

                {{-- ================= LÍNEA 80% ================= --}}
                @php
                    $y80 = $chartHeight * 0.20;
                @endphp
                <div style="position:absolute;left:0;top:{{ $y80 }}px;width:100%;border-top:2px dashed #ff9900;">
                </div>

                {{-- ================= BARRAS + LÍNEA ================= --}}
                @foreach($pareto as $i => $row)
                    @php
                        $barHeight = $axisMax == 0
                            ? 0
                            : ($row['frequency'] / $axisMax) * $chartHeight;

                        $left = ($space * $i) + (($space - $barWidth) / 2);

                        $pointX = $left + ($barWidth / 2);
                        $pointY = $chartHeight - (($row['accumulatedPercent'] / 100) * $chartHeight);
                    @endphp

                    {{-- BARRA --}}
                    <div style="position:absolute;left:{{ round($left) }}px;bottom:0;width:{{ round($barWidth) }}px;height:{{ round($barHeight) }}px;background:#3b82f6;border:1px solid #1e40af;">
                    </div>

                    {{-- LÍNEA PARETO --}}
                    @if($prevX !== null)
                        @php
                            $dx = $pointX - $prevX;
                            $dy = $pointY - $prevY;

                            $length = sqrt(($dx*$dx)+($dy*$dy));
                            $angle = rad2deg(atan2($dy,$dx));
                        @endphp

                        <div style="position:absolute;left:{{ $prevX }}px;top:{{ $prevY }}px;width:{{ $length }}px;height:2px;background:red;transform:rotate({{ $angle }}deg);transform-origin:0 0;">
                        </div>
                    @endif

                    {{-- PUNTO --}}
                    <div style="position:absolute;left:{{ $pointX-4 }}px;top:{{ $pointY-4 }}px;width:8px;height:8px;background:red;border-radius:50%;border:1px solid #fff;">
                    </div>

                    {{-- % ACUMULADO EN EL PUNTO --}}
                    <div style="position:absolute;left:{{ $pointX-12 }}px;top:{{ $pointY-18 }}px;font-size:10px;font-weight:bold;color:red;text-align:center;width:35px;">
                        {{ round($row['accumulatedPercent']) }}%
                    </div>


                    {{-- FRECUENCIA --}}
                    <div style="position:absolute;left:{{ $left-5 }}px;bottom:{{ $barHeight-18 }}px;width:40px;text-align:center;font-size:10px;font-weight:bold;color:#1d4ed8;">
                        {{ $row['frequency'] }}
                    </div>

                    @php
                        $prevX = $pointX;
                        $prevY = $pointY;
                    @endphp

                @endforeach

                {{-- ================= ETIQUETAS (DESCRIPCIÓN) ================= --}}
                @foreach($pareto as $i => $row)
                    @php
                        $left = ($space * $i) + (($space - 80) / 2);
                    @endphp

                    <div style="position:absolute;left:{{ $left }}px;bottom:-85px;width:90px;font-size:9px;font-weight:bold;text-align:center;transform:rotate(-45deg);transform-origin:top left;line-height:9px;">
                        {{ $row['description'] }}
                    </div>
                @endforeach

            </div>

        </div>

    </div>
</body>
</html>