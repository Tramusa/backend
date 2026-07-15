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
        font-family:Arial, Helvetica, sans-serif;
        font-size:11px;
        margin:0;
    }

    table{
        border-collapse:collapse;
    }

    .full{
        width:100%;
    }

    .matrix{
        width:auto;
        border-collapse:collapse;
    }

    td{
        border:1px solid #000;
        padding:3px;
        vertical-align:middle;
    }

    .logo{
        width:170px;
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
        font-weight:bold;
        text-align:center;
    }

    .blueHeader{
        background:#2d52c7;
        color:#fff;
        font-weight:bold;
        text-align:center;
    }

    .greenHeader{
        background:#17a84b;
        color:#fff;
        font-weight:bold;
        text-align:center;
    }

    .yellowHeader{
        background:#ffd42a;
        font-weight:bold;
        text-align:center;
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
        font-family: "DejaVu Sans";
        color:#17a84b;
        font-size:28px;
        font-weight:bold;
        line-height:18px;
    }

    .sum{
        background:#ffd42a;
        font-weight:bold;
        text-align:center;
    }

    .totalCell{
        background:#fff8cc;
        text-align:center;
        font-weight:bold;
    }

    .infoDetect td{
        border:none !important;
    }
    
</style>

</head>

<body><br>

    {{--================ HEADER =================--}}
    <table>
        <tr>
            <td style="width:170px;text-align:center;">
                @if($logoImage)
                    <img src="{{ $logoImage }}" class="logo">
                @endif
            </td>
            <td style="padding:0;">
                <div class="title" style="margin-top:8px;">
                    TRAMUSA CARRIER S.A. DE C.V.
                </div>
                <div class="subtitle" style="margin-top:8px;">
                    DIAGRAMA DE RELACIÓN
                </div>
                <table>
                    <tr class="orange infoDetect">
                        <td style="width:55%;">
                            ÁREA: COORDINACIÓN DE CALIDAD
                        </td>
                        <td style="width:20%;">
                            F-02-24
                        </td>
                        <td style="width:25%;">
                            REVISIÓN: JUNIO 2018
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding:10px;font-size:12px;">
                <b>INSTRUCCIONES:</b>
                Marque únicamente las relaciones existentes entre las causas identificadas en el Diagrama de Ishikawa.
            </td>
        </tr>
    </table><br>

    {{-- ================= MATRIZ ================= --}}
    <table class="matrix" style="font-size:10px;display:inline-table;">
        <tr>
            <td class="center bold" style="width:30px;height:24px;background:#e5e5e5;">
                No.
            </td>

            <td class="blueHeader" style="width:330px;">
                CAUSAS IDENTIFICADAS
            </td>

            @foreach($causes as $i=>$cause)
                <td style="width:24px;height:24px;border:none;background:#fff;"></td>
            @endforeach
        </tr>

        @foreach($causes as $row=>$cause)
        <tr>

            <td class="center bold" style="width:30px;height:24px;">
                {{ $row+1 }}
            </td>

            <td style="width:330px;height:24px;padding-left:6px;">
                {{ $cause }}
            </td>

            @foreach($causes as $col=>$tmp)

                @if($col>$row)

                    <td style="width:24px;height:24px;border:none;background:#fff;"></td>

                @elseif($col==$row)

                    <td 
                        style="max-width:24px;height:24px;background:#17a84b;color:#fff;text-align:center;font-weight:bold;padding:0;"
                    >
                        {{ $row+1 }}
                    </td>

                @else

                    <td
                        style="width:24px;height:24px;text-align:center;vertical-align:middle;padding:0;"
                    >
                        @if(!empty($matrix["{$row}-{$col}"]))
                            <span class="check">&#x2713;</span>
                        @endif
                    </td>

                @endif

            @endforeach

        </tr>
        @endforeach

    </table>

    {{-- ================= SUMA DE FRECUENCIAS ================= --}}
    <table class="matrix" style="margin-top:-1px;font-size:10px;">
        <tr>
            <td class="yellowHeader" style="width:370px;">
                SUMA DE FRECUENCIAS DE RELACIÓN
            </td>
            @foreach($totals as $total)
                <td style="
                        background:#fff8cc;
                        text-align:center;
                        font-weight:bold;
                        width:29.5px;
                        height:24px;
                        padding:0;
                        text-align:center;
                        vertical-align:middle;
                    ">
                    {{ $total }}
                </td>
            @endforeach
        </tr>
    </table>

    {{-- ================= RESULTADO ================= --}}
    <table style="margin-top:10px;font-size:11px;">
        <tr>
            <td class="greenHeader" style="width:50px;">
                No.
            </td>
            <td class="blueHeader">
                DESCRIPCIÓN DE LA CAUSA
            </td>
            <td class="yellowHeader" style="width:90px;">
                FREC. DE<br>RELACIONES
            </td>
        </tr>
        @forelse($ranking as $item)
            <tr>
                <td class="center bold">
                    {{ $item['no'] }}
                </td>
                <td style="padding:6px;">
                    {{ $item['cause'] }}
                </td>
                <td class="center bold">
                    {{ $item['total'] }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3"
                    style="text-align:center;padding:20px;">
                    Sin información registrada.
                </td>
            </tr>
        @endforelse

    </table>

</body>

</html>