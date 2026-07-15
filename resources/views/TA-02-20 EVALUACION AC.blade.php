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

    td{
        border:1px solid #000;
        vertical-align:top;
        padding:2px;
    }

    .logo{
        width:130px;
        display:block;
        margin:auto;
    }

    .title{
        text-align:center;
        font-size:14px;
        font-weight:bold;
        padding-top:6px;
        padding-bottom:4px;
    }

    .subtitle{
        text-align:center;
        font-size:11px;
        font-weight:bold;
        padding:6px 10px;
        line-height:12px;
    }

    .orange{
        background:#d98b3f;
        color:#fff;
        font-weight:bold;
        text-align:center;
        font-size:8px;
        padding:3px;
    }

    .objectiveTitle{
        background:#0d2f73;
        color:#fff;
        font-weight:bold;
        text-align:center;
        padding:5px;
    }

    .objectiveBody{
        padding:8px;
        font-size:11px;
        line-height:10px;
    }

    .blueTitle{
        background:#0d2f73;
        color:#fff;
        font-weight:bold;
        text-align:center;
    }

    .problemTitle{
        border:2px solid red;
        color:red;
        text-align:center;
        font-weight:bold;
        font-size:15px;
    }

    .problemBody{
        border:2px solid red;
        background:red;
        color:#fff;
        text-align:center;
        font-size:13px;
        font-weight:bold;
        padding:5px;
        height: 115px;
    }

    .green{
        background:#00ff00;
        font-weight:bold;
    }

    .lightBlue{
        background:#9dc3e6;
        font-weight:bold;
    }

    .yellow{
        background:#fff59d;
        font-weight:bold;
    }

    .valueBox{
        width:60px;
        text-align:center;
        font-size:20px;
        font-weight:bold;
        background:#fff;
    }

    .nprTitle{
        border:2px solid #003399;
        color:#003399;
        font-size:12px;
        font-weight:bold;
        padding:12px
    }

    .nprValue{
        text-align:center;
        color:#fff;
        background:#009900;
        font-size:28px;
        font-weight:bold;
    }

    .procede{
        text-align:center;
        font-size:14px;
        font-weight:bold;
        padding:14px
    }

    .result{
        text-align:center;
        color:#fff;
        font-size:18px;
        font-weight:bold;
    }

    .red{
        background:#c00000;
    }

    .orange2{

        background:#ffc000;
        color:#000;

    }
    .infoDetect td{
        border:none !important;
    }
</style>

</head>

<body>

    @php

        $evaluation=$nonConformity->evaluation;

        $npr=
        ($evaluation->severity??1)
        *
        ($evaluation->detectability??1)
        *
        ($evaluation->occurrence??1);

        $result=$npr>=100 ?'ACCIONES CORRECTIVAS' :'CORRECCIONES';

    @endphp

    {{-- ================= HEADER ================= --}}
    <br><br><br>
    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">

        <tr>
            {{-- ================= IZQUIERDA ================= --}}
            <td style="width:65%;padding:0;vertical-align:top;">

                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="width:25%;text-align:center;border-right:1px solid #000;">
                            @if($logoImage)
                                <img src="{{ $logoImage }}" class="logo">
                            @endif
                        </td>

                        <td style="width:75%;padding:0;">
                            <div class="title">
                                TRAMUSA CARRIER S.A. DE C.V.
                            </div>
                            <div class="subtitle">
                                TABLA DE EVALUACIÓN DE NO CONFORMIDADES Y RIESGOS POTENCIALES
                            </div>
                            <table style="width:100%;">
                                <tr class="orange infoDetect ">
                                    <td style="width:54%;">
                                        ÁREA: COORDINACIÓN DE CALIDAD
                                    </td>
                                    <td style="width:19%;">
                                        TA-02-20
                                    </td>
                                    <td style="width:27%;">
                                        REVISIÓN: JUNIO 2018
                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr>

                </table>

                {{-- OBJETIVO --}}
                <table style="width:100%;margin-top:-1px;">
                    <tr>
                        <td class="objectiveTitle">
                            OBJETIVO
                        </td>
                    </tr>
                    <tr>
                        <td class="objectiveBody">
                            Proporcionar una guía para evaluar la necesidad de realizar una
                            corrección o una acción correctiva de acuerdo a un criterio
                            establecido.
                        </td>
                    </tr>
                </table>
            </td>

            {{-- ================= PROBLEMA ================= --}}
            <td style="width:35%;padding:0;vertical-align:top;">
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td class="problemTitle">
                            DESCRIPCIÓN DEL PROBLEMA
                        </td>
                    </tr>
                    <tr>
                        <td class="problemBody">
                            {{ $nonConformity->problem }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table><br><br>

    {{-- ================= BODY ================= --}}
    <table style="margin-top:0;">

        <tr>

            {{-- ================= IZQUIERDA ================= --}}
            <td style="width:58%;padding:0;border-right:2px solid #000;vertical-align:top;">

                <table>
                    <tr>
                        <td class="blueTitle">
                            CRITERIO PARA EVALUAR PROBLEMAS O ÁREAS DE OPORTUNIDAD
                        </td>
                    </tr>
                </table>

                {{-- ================= SEVERIDAD ================= --}}
                <table>
                    <tr>
                        <td colspan="2" class="green">
                            SEVERIDAD&nbsp;&nbsp;&nbsp;
                            <span style="font-weight:normal;">
                                Es el grado en que afecta el problema a nuestro cliente.
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td style="width:110px;text-align:center;font-weight:bold;">
                            CALIFICACIÓN
                        </td>
                        <td style="text-align:center;font-weight:bold;">
                            CRITERIO DE EVALUACIÓN
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">9 - 10</td>
                        <td>Afecta el cumplimiento de planes y programas de producción.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">7 - 8</td>
                        <td>Su resolución involucra la acción de dos o más unidades o departamentos.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">5 - 6</td>
                        <td>Afecta directamente el desarrollo de un proceso de producción.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">3 - 4</td>
                        <td>Su resolución requiere la acción coordinada de 3 o más personas.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">1 - 2</td>
                        <td>Es posible resolverlo al interior de un departamento o por una persona.</td>
                    </tr>

                </table>

                {{-- ================= DETECTABILIDAD ================= --}}
                <table>
                    <tr>
                        <td colspan="2" class="lightBlue">
                            DETECTABILIDAD&nbsp;&nbsp;&nbsp;
                            <span style="font-weight:normal;">
                                Se refiere a la existencia de controles para prevenir o corregir el problema.
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td style="width:110px;text-align:center;font-weight:bold;">
                            CALIFICACIÓN
                        </td>
                        <td style="text-align:center;font-weight:bold;">
                            CRITERIO DE EVALUACIÓN
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">9 - 10</td>
                        <td>No existe un control para detectar el problema.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">7 - 8</td>
                        <td>El control existente difícilmente puede detectar el problema.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">5 - 6</td>
                        <td>El control existente detecta el problema.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">3 - 4</td>
                        <td>El control existente detecta todos los problemas en cuanto ocurran.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">1 - 2</td>
                        <td>El control existente previene los problemas.</td>
                    </tr>

                </table>

                {{-- ================= OCURRENCIA ================= --}}
                <table>

                    <tr>
                        <td colspan="2" class="yellow">
                            OCURRENCIA &nbsp;&nbsp;&nbsp;
                            <span style="font-weight:normal;">
                                Es la cantidad de veces que puede presentarse el problema.
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td style="width:110px;text-align:center;font-weight:bold;">
                            CALIFICACIÓN
                        </td>
                        <td style="text-align:center;font-weight:bold;">
                            CRITERIO DE EVALUACIÓN
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">9 - 10</td>
                        <td>Es un problema del sistema que ocurre en toda la empresa.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">7 - 8</td>
                        <td>Es un problema relativo en nuestra unidad.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">5 - 6</td>
                        <td>Es un problema que es repetitivo en una área.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">3 - 4</td>
                        <td>Es un problema que sucede ocasionalmente.</td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">1 - 2</td>
                        <td>Rara vez sucede y ocurre de manera aislada.</td>
                    </tr>

                </table>

                {{-- ================= CRITERIO ================= --}}
                <table>

                    <tr>
                        <td class="blueTitle">
                            CRITERIO PARA EVALUAR Y SOLICITAR ACCIONES CORRECTIVAS O CORRECCIONES
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px;font-size:11px;line-height:10px;">
                            Una vez que se evalúan todos estos elementos se multiplican entre ellos,
                            para obtener el Número Prioritario de Riesgo (NPR), es decir: <br><br>

                            <div style="text-align:center;font-size:12px;font-weight:bold;">
                                NPR = SEVERIDAD × DETECTABILIDAD × OCURRENCIA
                            </div> <br>

                            Toda aquella no conformidad con un NPR mayor o igual a <b>100</b>,
                            requerirá del desarrollo de una <b>Acción Correctiva</b>, mientras
                            que aquellas menores requerirán únicamente una <b>Corrección</b>.

                        </td>
                    </tr>

                </table>

            </td>

            {{-- ================= COLUMNA DERECHA ================= --}}
            <td style="width:42%;vertical-align:top;padding:10px;">

                {{-- ================= SEVERIDAD ================= --}}
                <div style="margin-top:10px;"></div>
                <table>
                    <tr>
                        <td style="background:#00ff00;font-weight:bold;font-size:11px;padding:8px;">
                            VALOR ASIGNADO A SEVERIDAD
                        </td>

                        <td class="valueBox">
                            {{ $evaluation->severity }}
                        </td>
                    </tr>
                </table><br><br><br><br><br><br><br><br><br>

                {{-- ================= DETECTABILIDAD ================= --}}
                <table>
                    <tr>
                        <td style="background:#9dc3e6;font-weight:bold;font-size:11px;padding:8px;">
                            VALOR ASIGNADO A DETECTABILIDAD
                        </td>
                        <td class="valueBox">
                            {{ $evaluation->detectability }}
                        </td>
                    </tr>
                </table><br><br><br><br><br><br><br><br><br>

                {{-- ================= OCURRENCIA ================= --}}
                <table>
                    <tr>
                        <td style="background:#ffe699;font-weight:bold;font-size:11px;padding:8px;">
                            VALOR ASIGNADO A OCURRENCIA
                        </td>
                        <td class="valueBox">
                            {{ $evaluation->occurrence }}
                        </td>
                    </tr>
                </table><br><br><br><br><br><br>

                {{-- ================= NPR ================= --}}
                <table style="border:2px solid #003399;">
                    <tr>
                        <td class="nprTitle">
                            NUMERO PRIORITARIO DE RIESGO
                        </td>
                        <td class="nprValue">
                            {{ $npr }}
                        </td>
                    </tr>
                </table><br><br>

                {{-- ================= PROCEDE ================= --}}
                <table style="border:2px solid #ffc000;">
                    <tr>
                        <td class="procede">
                            PROCEDE
                        </td>
                        <td class="result {{ $npr>=100 ? 'red':'orange2' }}">
                            {{ $result }}
                        </td>
                    </tr>
                </table>
            </td>

        </tr>

    </table>

</body>

</html>