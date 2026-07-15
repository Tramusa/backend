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
        width:100%;
        border-collapse:collapse;
    }

    td{
        border:1px solid #000;
        padding:2px;
        vertical-align:top;
    }

    .logo{
        width:130px;
    }

    .title{
        color:#083db7;
        text-align:center;
        font-size:16px;
    }

    .subtitle{
        text-align:center;
        font-size:14px;
        font-weight:bold;
    }

    .orange{
        border:1px solid #d98b3f;
        background:#d98b3f;
        color:#fff;
        font-weight:bold;
        text-align:center;
        font-size:10px
    }

    .green{

        background:#149414;
        color:#fff;
        font-weight:bold;

    }

    .blue{

        background:#083db7;
        color:#fff;
        font-weight:bold;

    }

    .yellow{

        background:#ffd500;
        font-weight:bold;

    }

    .pink{
        background:#ff00ff;
        font-weight:bold;
    }

    .small{
        font-size:10px;
    }

    .infoDetect td{
        border:none !important;
    }

</style>

</head>

<body><br>
    @php

        $causes = $nonConformity->ishikawa->causes ?? collect();

        $maquinaria = $causes->where('category','maquinaria')->pluck('description')->values();

        $personas = $causes->where('category','personas')->pluck('description')->values();

        $metodo = $causes->where('category','metodo')->pluck('description')->values();

        $materiales = $causes->where('category','materiales')->pluck('description')->values();

    @endphp

    {{-- ================= HEADER ================= --}}
    <table>
        <tr>
            <td style="width:130px;text-align:center;">
                @if($logoImage)
                    <img src="{{ $logoImage }}" class="logo">
                @endif
            </td>

            {{-- TITULO --}}
            <td style="padding:0;">
                <div class="title" style="margin-top:6px;">
                    TRAMUSA CARRIER S.A. DE C.V.
                </div>
                <div class="subtitle" style="margin-top:6px;">
                    ISHIKAWA
                </div>
                <table>
                    <tr class="orange infoDetect ">
                        <td style="width:55%;">
                            ÁREA: COORDINACIÓN DE CALIDAD
                        </td>
                        <td style="width:20%;">
                            F-02-23
                        </td>
                        <td style="width:25%;">
                            REVISIÓN: JUNIO 2018
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ================= INSTRUCCIONES ================= --}}
    <table style="margin-top:2px;border:none !important;">
        <tr>
            <td class="small">
                <b>INSTRUCCIONES:</b>
                Haga una lluvia de ideas para determinar las causas que están provocando
                la No Conformidad que se quiere analizar y colóquelas en los cuadros que
                crea conveniente (Insumos, Personas, Método o Materiales).
            </td>
        </tr>
    </table>

    {{-- ================= RUBROS ================= --}}
    <table>
        <tr>
            <td class="green">
                MAQUINARIA Y EQUIPO:
                <span style="font-weight:normal;color:#fff;">
                    Se refiere a maquinaria y/o equipo utilizado en los diferentes procesos.
                </span>
            </td>
        </tr>
        <tr>
            <td class="blue">
                MÉTODO:
                <span style="font-weight:normal;color:#fff;">
                    Se refiere al cómo, cuándo y quién lo hace.
                </span>
            </td>
        </tr>
        <tr>
            <td class="yellow">
                PERSONAS:
                <span style="font-weight:normal;">
                    Características de las personas involucradas en estas funciones o en el problema.
                </span>
            </td>
        </tr>
        <tr>
            <td class="pink">
                MATERIALES:
                <span style="font-weight:normal;">
                    Insumos requeridos para el proceso.
                </span>
            </td>
        </tr>
    </table>

    {{-- ================= DIAGRAMA ================= --}}
    <div style="position:relative;width:100%;height:480px;">

        @if($diagrama)
            <img
                src="{{ $diagrama }}"
                style="position:absolute;top:25px;width:80%;height:auto;">
        @endif

        {{-- ================= PROBLEMA ================= --}}

        <div style="position:absolute;right:8px;top:155px;width:165px;min-height:155px;background:#d40000;color:#fff;font-weight:bold;font-size:11px;text-align:center;padding:6px;line-height:15px;">
            {{ $nonConformity->problem }}
        </div>

        @foreach($maquinaria as $i=>$cause)
            <div style="position:absolute;left:{{ 10 + ($i*10) }}px;top:{{ 60 + ($i*35) }}px;width:180px;border:1px solid #777;background:#fff;padding:4px;font-size:10px;">
                {{ $cause }}
            </div>
        @endforeach

        @foreach($personas as $i=>$cause)
            <div style="position:absolute;left:{{ 300 + ($i*10) }}px;top:{{ 60 + ($i*35) }}px;width:180px;border:1px solid #777;background:#fff;padding:4px;font-size:10px;">
                {{ $cause }}
            </div>
        @endforeach

        @foreach($metodo as $i=>$cause)
            <div style="position:absolute;left:{{ 10 + ($i*10) }}px;top:{{ 380 - ($i*35) }}px;width:180px;border:1px solid #777;background:#fff;padding:4px;font-size:10px;">
                {{ $cause }}
            </div>
        @endforeach

        @foreach($materiales as $i=>$cause)
            <div style="position:absolute;left:{{ 300 + ($i*10) }}px;top:{{ 380 - ($i*35) }}px;width:180px;border:1px solid #777;background:#fff;padding:4px;font-size:10px;">
                {{ $cause }}
            </div>
        @endforeach
    </div>

    {{-- ================= CAUSAS / EFECTO ================= --}}
    <table style="width:100%;border-collapse:collapse;">

        <tr>
            <td style="width:76%;background:#8f2d00;color:#fff;text-align:center;font-weight:bold;font-size:11px;letter-spacing:10px;padding:4px;border:1px solid #000;">
                C A U S A S
            </td>

            <td style="width:24%;background:#9b9800;color:#000;text-align:center;font-weight:bold;font-size:11px;letter-spacing:8px;padding:4px;border:1px solid #000;">
                E F E C T O
            </td>
        </tr>

    </table>
</body>
</html>