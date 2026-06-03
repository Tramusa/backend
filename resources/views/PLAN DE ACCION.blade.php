<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>PLAN DE ACCIÓN</title>

<style>

    body{
        width:19cm;
        height:29.7cm;
        margin:0 auto;
        font-size:11px;
        font-family:Arial, Helvetica, sans-serif;
        color:#000;
    }

    /* ================= HEADER ================= */
    .header-box{
        width:100%;
        border:3px solid #BFBFBF;
        display:table;
        margin-bottom:15px;
    }

    .header-row{
        display:table-row;
    }

    .header-logo{
        width:22%;
        display:table-cell;
        border-right:3px solid #BFBFBF;
        text-align:center;
        vertical-align:middle;
        padding:10px;
    }

    .header-logo img{
        width:100%;
    }

    .header-title{
        width:78%;
        display:table-cell;
        text-align:center;
        vertical-align:middle;
    }

    .company-name{
        color:#0073B5;
        font-weight:bold;
        font-size:16px;
    }

    .document-title{
        font-weight:bold;
        font-size:14px;
        margin-top:4px;
    }

    .header-bar{
        background:#F28D46;
        color:#fff;
        font-size:11px;
        padding:6px;
        text-align:center;
        margin-top:6px;
    }

    /* ================= TABLAS ================= */
    table{
        width:100%;
        border-collapse:collapse;
        table-layout:fixed;
    }

    /* BORDES NORMALES */
    td, th{
        border:1px solid #000;
        padding:5px;
        font-size:10px;
        vertical-align:top;
        word-wrap:break-word;
    }

    /* ================= SOLO TABLA PROBLEMA ================= */
    .problem-table td{
        border:2px solid #ff0000 !important;
    }

    .problem-red{
        background:#ff0000;
        color:#ffffff;
        font-weight:bold;
    }

    .problem-white{
        background:#ffffff;
        color:#ff0000;
        font-weight:bold;
    }

    /* ================= TEXTOS ================= */
    .instructions{
        font-size:11px;
        line-height:1.5;
    }

    .center{
        text-align:center;
    }

    .bold{
        font-weight:bold;
    }

    /* ================= COLORES ================= */
    .yellow{
        background:#ffe600;
        font-weight:bold;
    }

    .blue{
        background:#00b0f0;
    }

    .green{
        background:#c6efce;
    }

    .cause-block{
        page-break-inside:avoid;
    }

    .empty-space{
        height:25px;
    }
</style>
</head>

<body>

    {{-- ================= HEADER ================= --}}
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
                    PLAN DE ACCIÓN
                </div>

                <div class="header-bar">
                    <strong>
                        ÁREA: COORDINACIÓN DE CALIDAD &nbsp; | &nbsp;
                        F-02-19 &nbsp; | &nbsp;
                        REVISIÓN: JUNIO 2018
                    </strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= INSTRUCCIONES ================= --}}
    <table>
        <tr>
            <td colspan="2" class="instructions">
                <u><strong>INSTRUCCIONES:</strong></u>
                <br>
                <strong>Si aplica Acciones Correctivas:</strong>
                Para cada causa principal de la no conformidad,
                determine las acciones que se tomarán para eliminar la causa,
                estableciendo fecha compromiso de solución a la acción
                y el responsable de darle seguimiento,
                así como cada actividad requerida para la acción correctiva.
                <br>
                <strong>Si aplicará solo Correcciones:</strong>
                llenar los espacios de Acción correctiva,
                no se llenan los espacios de Actividades.
            </td>
        </tr>

        <tr>
            <td style="width:6%; background:#ffe600;"></td>
            <td style="font-size:11px;">
                Causa principal de la no conformidad
            </td>
        </tr>

        <tr>
            <td style="background:#00b0f0;"></td>
            <td style="font-size:11px;">
                Acción correctiva para eliminar la causa.
            </td>
        </tr>

        <tr>
            <td style="background:#c6efce;"></td>
            <td style="font-size:11px;">
                Actividades
            </td>
        </tr>
    </table>

    {{-- ================= PROBLEMA ================= --}}
    <table class="problem-table" style="margin-top:10px;">
        <tr>
            <td class="problem-white"
                style=" width:66%; font-size:13px;">
                PROBLEMA O ÁREA DE OPORTUNIDAD:
            </td>

            <td rowspan="2" class="problem-white center" 
                style="width:19%;font-size:11px;text-align:center;vertical-align:middle;font-weight:bold;">
                FECHA COMPROMISO DE TERMINACIÓN DE ACCIONES
            </td>

            <td rowspan="2" class="problem-red center"
                style="width:15%;font-size:16px;text-align:center;vertical-align:middle;font-weight:bold;">
                {{ \Carbon\Carbon::parse($data['date_commitment'])->format('d-m-y') }}
            </td>
        </tr>
        <tr>
            <td class="problem-red"
                style="padding:5px;font-size:13px;color:#ffffff;font-weight:bold;vertical-align:middle;">
                {{ $data['problem'] }}
            </td>
        </tr>
    </table>

    {{-- ================= CAUSAS ================= --}}
    @foreach($data['causes'] as $causeIndex => $cause)
        <div class="cause-block">
            <table style="margin-top:15px;">
                <tr>
                    <td style="width: 58%;"> </td>

                    <td class="center bold">
                        FECHA COMPROMISO
                    </td>

                    <td class="center bold">
                        RESPONSABLE DE SEGUIMIENTO
                    </td>

                    <td class="center bold">
                        FIRMA DE RESPONSABLE
                    </td>
                </tr>
                <tr>
                    <td class="yellow">
                        {{ $causeIndex + 1 }}.{{ $cause['cause'] }}
                    </td>
                    <td class="yellow">&nbsp;</td>
                    <td class="yellow">&nbsp;</td>
                    <td class="yellow">&nbsp;</td>
                </tr>

                {{-- ACCIONES --}}
                @if(count($cause['actions']) > 0)
                    @foreach($cause['actions'] as $action)
                        <tr>
                            <td class="blue">
                                &nbsp;&nbsp;&nbsp;&nbsp;{{ $action['action'] }}
                            </td>

                            <td class="blue center">
                                {{ \Carbon\Carbon::parse($action['date_commitment'])->format('d-m-Y') }}
                            </td>

                            <td class="blue center">
                                {{ $action['responsible'] ?? '' }}
                            </td>

                            <td class="blue"></td>
                        </tr>

                        {{-- ACTIVIDADES --}}
                        @if(count($action['activities']) > 0)
                            @foreach($action['activities'] as $activity)
                                <tr>
                                    <td class="green">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $activity['activity'] }}
                                    </td>

                                    <td class="green center">
                                        {{ \Carbon\Carbon::parse($activity['date_commitment'])->format('d-m-Y') }}
                                    </td>

                                    <td class="green center">
                                        {{ $activity['responsible'] ?? '' }}
                                    </td>

                                    <td class="green"></td>
                                </tr>
                            @endforeach
                        @else
                            @for($i = 0; $i < 4; $i++)
                                <tr>
                                    <td class="green empty-space">&nbsp;</td>
                                    <td class="green"></td>
                                    <td class="green"></td>
                                    <td class="green"></td>
                                </tr>
                            @endfor
                        @endif
                    @endforeach
                @else
                    @for($i = 0; $i < 5; $i++)
                        <tr>
                            <td class="blue empty-space">&nbsp;</td>
                            <td class="blue"></td>
                            <td class="blue"></td>
                            <td class="blue"></td>
                        </tr>
                        <tr>
                            <td class="green empty-space">&nbsp;</td>
                            <td class="green"></td>
                            <td class="green"></td>
                            <td class="green"></td>
                        </tr>
                    @endfor
                @endif
            </table>
        </div>
    @endforeach

</body>
</html>