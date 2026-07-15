<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>REPORTE DE ACCIONES</title>

<style>
    *{
        box-sizing:border-box;
    }

    body{
        font-family:Arial, Helvetica, sans-serif;
        font-size:10px;
        margin:0;
        padding:2px;
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

    .center{
        text-align:center;
    }

    .bold{
        font-weight:bold;
    }

    .blueHeader{
        background:#0000cc;
        color:white;
        font-weight:bold;
        text-align:center;
    }

    .orange{
        background:#ef8c3a;
        color:white;
        font-weight:bold;
        text-align:center;
    }

    .gray{
        background:#BFBFBF;
    }

    .checkbox{
        display:inline-block;
        width:18px;
        height:18px;
        border:2px solid #000;
        vertical-align:middle;
    }

    .checkbox2{
        display:inline-block;
        width:35px;
        height:17px;
        border:2px solid #000;
        vertical-align:middle;
    }

    .checkbox3{
        display:inline-block;
        width:17px;
        height:14px;
        border:2px solid #000;
        vertical-align:middle;
    }

    .checked{
        background:#005eff;
    }

    .checkedG{
        background-color: green;
    }

    .redCode{
        color:red;
        font-weight:bold;
        border:1px solid #000;
        padding:4px 10px;
        background:#BFBFBF;
    }

    .boxTxt{
        font-weight:bold;
        border:1px solid #000;
        padding:4px 10px;
    }

    .boxTxtG{
        font-weight:bold;
        border:1px solid #000;
        padding:4px 10px;
        background:#BFBFBF;
    }

    .blueText{
        color:#0000cc;
        font-weight:bold;
        text-align:center;
    }

    .npr{
        font-size:22px;
        color:#0000ff;
        font-weight:bold;
        text-align:center;
        background:#BFBFBF;
    }

    .causeTitle{
        width:15%;
        font-weight:bold;
        text-align:center;
        vertical-align:middle;
    }

    .causeBox{
        font-weight:bold;
        background:#BFBFBF;
        color:#0033cc;
        vertical-align:top;
        height:120px;
        padding:8px;
        line-height:1;
    }

    .causeItem{
        margin-bottom:4px;
    }

    .actionRow{
        height:28px;
    }

    .logo{
        width:140px;
    }
    .infoRow{
        border:1px solid #000;
        border-collapse:collapse;
    }

    .infoRow td{
        border:none !important;
        padding: 14px 0px;
    }

    .infoDetect{
        border:1px solid #000;
        border-collapse:collapse;
    }

    .infoDetect td{
        border:none !important;
    }
</style>

</head>

<body><br>
    <!-- =============== ENCABEZADO  =================== -->
    <table>
      <tr>
        <td width="21%" class="center">
            @if($logoImage)
                <img src="{{ $logoImage }}" class="logo">
            @endif
        </td>

        <td width="79%" style="padding:0;">
            <table>
                <tr>
                    <td class="center" style="border:none;font-size:16px;color:#005cb9;">
                        TRAMUSA CARRIER S.A. DE C.V.
                    </td>
                </tr>
                <tr>
                    <td class="center bold" style="border:none;font-size:14px;">
                        REPORTE DE ACCIONES
                    </td>
                </tr>
                <tr class="orange">
                    <td>
                        ÁREA: COORDINACIÓN DE CALIDAD
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        F-02-14
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        REVISIÓN: JUNIO 2018
                    </td>
                </tr>
            </table>
        </td>
      </tr>
    </table>

    <!-- ====================  INFORMACIÓN GENERAL  =========================== -->
    <table class="infoRow" style="margin-top:6px;">
        <tr>
            <td class="center">
                NO CONFORMIDAD
            </td>
            <td width="12%" class="center">
                <span class="redCode">
                    {{ $nonConformity->number }}
                </span>
            </td>
            <td class="center">
                FECHA DE DETECCIÓN
            </td>
            <td width="13%" class="blueText">
                <span class="boxTxt">
                    {{ \Carbon\Carbon::parse($nonConformity->date)->format('d-M-y') }}
                </span>
            </td>
            <td class="center">
                FECHA DE COMPROMISO
            </td>
            <td width="13%" class="blueText">
                <span class="boxTxtG">
                    {{ \Carbon\Carbon::parse($nonConformity->date_commitment)->format('d-M-y') }}
                </span>
            </td>
        </tr>
    </table>

    <!-- ===================== DESCRIPCIÓN DEL PROBLEMA ====================== -->
    <table style="margin-top:4px;">
        <tr>
            <td class="center bold">
                DESCRIPCIÓN DEL PROBLEMA
            </td>
        </tr>
        <tr>
            <td class="center blueText" style="padding: 10px 0px;">
                {{ $nonConformity->problem }}
            </td>
        </tr>
    </table>

    <!-- =================== INFORMACIÓN DE DETECCIÓN  ======================== -->
    <table class="infoDetect" style="margin-top:4px;">
        <tr>
            <td colspan="10" class="center bold">
                COMO SE DETECTÓ
            </td>
        </tr>
        <tr>
            <td class="center">AUDITORÍA INTERNA DE <br> CALIDAD</td>
            <td width="35">
                <div class="checkbox {{ $nonConformity->detected=='AUDITORIA INTERNA DE CALIDAD' ? 'checked':'' }}"></div>
            </td>
            <td class="center">AUDITORÍA DE SERVICIO</td>
            <td width="35">
                <div class="checkbox {{ $nonConformity->detected=='AUDITORIA DE SERVICIO' ? 'checked':'' }}"></div>
            </td>
            <td class="center">QUEJA</td>
            <td width="35">
                <div class="checkbox {{ $nonConformity->detected=='QUEJA' ? 'checked':'' }}"></div>
            </td>
            <td class="center">OTRO</td>
            <td width="35">
                <div class="checkbox {{ $nonConformity->detected=='OTRO' ? 'checked':'' }}"></div>
            </td>
            <td>ESPECIFIQUE</td>
            <td></td>
        </tr>
    </table>

    <!-- ================= AFECTA A / TRAMUSA ================= -->
     <table style="margin-top:4px;border:none !important;">
        <tr>
            <td width="65%" style="padding:0;">
                <table class="infoDetect">
                    <tr>
                        <td colspan="6" class="center bold">
                            AFECTA A:
                        </td>
                    </tr>
                    <tr>
                        <td class="center">SISTEMA</td>
                        <td width="35">
                            <div class="checkbox {{ $nonConformity->affects=='SISTEMA' ? 'checked':'' }}"></div>
                        </td>

                        <td class="center">SERVICIO</td>
                        <td width="35">
                            <div class="checkbox {{ $nonConformity->affects=='SERVICIO' ? 'checked':'' }}"></div>
                        </td>

                        <td class="center">PROCESO</td>
                        <td width="35">
                            <div class="checkbox {{ $nonConformity->affects=='PROCESO' ? 'checked':'' }}"></div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="35%" style="padding:0;">
                <table class="infoDetect">
                    <tr>
                        <td colspan="4" class="center bold">
                            ¿LA SOLUCIÓN DEPENDE DE TRAMUSA?
                        </td>
                    </tr>
                    <tr>
                        <td class="center">SI</td>
                        <td width="50" height="30">
                            <div class="checkbox2 {{ $nonConformity->tramusa_solution ? 'checked':'' }}"></div>
                        </td>

                        <td class="center">NO</td>
                        <td width="50" height="30">
                            <div class="checkbox2 {{ !$nonConformity->tramusa_solution ? 'checked':'' }}"></div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding:0;">
                <table class="infoRow">
                    <tr>
                        <td width="25%" class="center">
                            RESPONSABLE DE ATENDERLA
                        </td>
                        <td width="35%" class="blueText center">
                            <span class="boxTxt">
                                {{ optional($nonConformity->responsibleUser)->name }}
                                {{ optional($nonConformity->responsibleUser)->a_paterno }}
                                {{ optional($nonConformity->responsibleUser)->a_materno }}
                            </span>
                        </td>
                        <td width="10%" class="center">
                            ÁREA
                        </td>
                        <td width="30%" class="blueText center">
                            <span class="boxTxt">
                                {{ $nonConformity->area }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- ================== EVALUACIÓN ========================== -->
    <table style="margin-top:4px;">
        <tr>
            <td width="68%" style="padding:0;">
                <table>
                    <tr>
                        <td colspan="3" class="blueHeader">
                            NÚMERO PRIORITARIO DE RIESGO (NPR)
                        </td>
                    </tr>
                    <tr>
                        <td width="12%" class="blueText" style="background:#BFBFBF; text-align:right;">
                            {{ optional($nonConformity->evaluation)->severity }}
                        </td>
                        <td width="55%">
                            SEVERIDAD
                        </td>
                        <td class="center">
                            Total
                        </td>
                    </tr>
                    <tr>
                        <td class="blueText" style="background:#BFBFBF; text-align:right;">
                            {{ optional($nonConformity->evaluation)->detectability }}
                        </td>
                         <td>
                            DETECTABILIDAD
                        </td>
                        <td rowspan="2" class="npr">
                            {{ optional($nonConformity->evaluation)->npr }}
                        </td>
                    </tr>
                    <tr>
                        <td class="blueText" style="background:#BFBFBF; text-align:right;">
                            {{ optional($nonConformity->evaluation)->occurrence }}
                        </td>
                        <td>
                            OCURRENCIA
                        </td>
                    </tr>
                </table>
            </td>

            <td width="32%" style="padding:0;line-height: 1;">
                <table class="infoDetect">
                    <tr style="height: 15px;">
                        <td colspan="2" class="center bold">
                            TIPO DE ACCIÓN:
                        </td>
                    </tr>

                    {{-- ACCIÓN CORRECTIVA --}}
                    <tr style="height: 15px;">
                        <td>
                            <div class="checkbox3 {{
                                $nonConformity->type == 'non_conformity' && optional($nonConformity->evaluation)->npr >= 100
                                    ? 'checkedG'
                                    : ''
                            }}"></div>
                        </td>
                        <td>ACCIÓN CORRECTIVA</td>
                    </tr>

                    {{-- CORRECCIÓN --}}
                    <tr style="height: 15px;">
                        <td>
                            <div class="checkbox3 {{
                                $nonConformity->type == 'non_conformity' && optional($nonConformity->evaluation)->npr < 100
                                    ? 'checkedG'
                                    : ''
                            }}"></div>
                        </td>
                        <td>CORRECCIÓN</td>
                    </tr>

                    {{-- OPORTUNIDAD DE MEJORA --}}
                    <tr style="height: 15px;">
                        <td>
                            <div class="checkbox3 {{
                                $nonConformity->type == 'opportunity_improvement'
                                    ? 'checkedG'
                                    : ''
                            }}"></div>
                        </td>
                        <td>OPORTUNIDAD DE MEJORA</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- =================== CAUSAS RAÍZ  ======================== -->
    <table style="margin-top:4px;">
        <tr>
            <td class="causeTitle">
                CAUSA<br>RAÍZ
            </td>
            <td class="causeBox">
                @foreach($nonConformity->actionPlanCauses as $cause)
                    <div class="causeItem">
                        • {{ $cause->main_cause }}
                    </div>
                @endforeach
            </td>
        </tr>
    </table>

    <!-- =================== PLAN DE ACCIÓN RESUMIDO =========================== -->
    <table style="margin-top:10px;border-collapse:collapse;width:100%;font-size:10px;">

        <tr style="height:28px;font-weight:bold;text-align:center;">
            <td width="12%">No.</td>
            <td width="52%">ACCIONES</td>
            <td width="22%">RESPONSABLE</td>
            <td width="9%" style="font-size:8px;">FECHA</td>
            <td width="5%" style="font-size:8px;">FIRMA</td>
        </tr>

        @php
            $totalFilas = 12;
            $filasUsadas = 0;
            $indice = 0;
            $folio = $nonConformity->number;

            if (!function_exists('letraConsecutiva')) {
                function letraConsecutiva($indice)
                {
                    $resultado = '';

                    do {
                        $resultado = chr(65 + ($indice % 26)) . $resultado;
                        $indice = intdiv($indice, 26) - 1;
                    } while ($indice >= 0);

                    return $resultado;
                }
            }
        @endphp

        @foreach($nonConformity->actionPlanCauses as $cause)

            @foreach($cause->correctiveActions as $action)

                {{-- ACCIÓN --}}
                <tr class="actionRow">

                    <td class="center" style="color:red;font-weight:bold;">
                        {{ $folio . '-' . letraConsecutiva($indice++) }}
                    </td>

                    <td style="background:#CFCFCF;font-weight:bold;">
                        {{ $action->corrective_action }}
                    </td>

                    <td style="background:#CFCFCF;">
                        {{ optional($action->responsible)->name }}
                        {{ optional($action->responsible)->a_paterno }}
                        {{ optional($action->responsible)->a_materno }}
                    </td>

                    <td class="center" style="background:#CFCFCF;">
                        @if($action->commitment_date)
                            {{ \Carbon\Carbon::parse($action->commitment_date)->format('d/m/Y') }}
                        @endif
                    </td>

                    <td class="firma"></td>

                </tr>

                @php
                    $filasUsadas++;
                @endphp

                {{-- ACTIVIDADES --}}
                @foreach($action->activities as $activity)

                    <tr class="actionRow">

                        <td class="center" style="color:red;font-weight:bold;">
                            {{ $folio . '-' . letraConsecutiva($indice++) }}
                        </td>

                        <td style="background:#D9D9D9;padding-left:18px;">
                             {{ $activity->activity }}
                        </td>

                        <td style="background:#D9D9D9;">
                            {{ optional($activity->responsible)->name }}
                            {{ optional($activity->responsible)->a_paterno }}
                            {{ optional($activity->responsible)->a_materno }}
                        </td>

                        <td class="center" style="background:#D9D9D9;">
                            @if($activity->commitment_date)
                                {{ \Carbon\Carbon::parse($activity->commitment_date)->format('d/m/Y') }}
                            @endif
                        </td>

                        <td class="firma"></td>

                    </tr>

                    @php
                        $filasUsadas++;
                    @endphp

                @endforeach

            @endforeach

        @endforeach

        {{-- Completar filas vacías --}}
        @for($i = $filasUsadas; $i < $totalFilas; $i++)

            <tr class="actionRow">

                <td></td>

                <td style="background:#CFCFCF;"></td>

                <td style="background:#CFCFCF;">
                    &nbsp;
                </td>

                <td style="background:#CFCFCF;"></td>

                <td class="firma"></td>

            </tr>

        @endfor

    </table>

    <table style="margin-top:6px;border-collapse:collapse;">
        <tr>
            <td width="17%">  Fecha de Eliminación </td>
            <td width="43%" style="background:#CFCFCF;">
                &nbsp;
            </td>
            <td width="18%" class="center">
                Firma de Revisor:
            </td>
            <td width="22%" style="background:#CFCFCF;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td> Nombre de Revisor: </td>
            <td style="background:#CFCFCF;">
                &nbsp;
            </td>
            <td colspan="2"> &nbsp;  </td>
        </tr>
    </table>

    <table style="margin-top:6px;border-collapse:collapse;">
        <tr>
            <td width="12%"> QUEJA No. </td>
            <td width="5%" style="background:#CFCFCF;">
                &nbsp;
            </td>
            <td width="15%" class="center">
                NOMBRE DEL CLIENTE:
            </td>
            <td width="43%" style="background:#CFCFCF;">
                &nbsp;
            </td>
            <td width="7%" class="center">
                FIRMA DEL:
            </td>
            <td width="18%" style="background:#CFCFCF;">
                &nbsp;
            </td>
        </tr>
    </table>

    <table style="border:none;margin-top:4px;">
        <tr>
            <td style="border:none;font-size:9px;">
                <b>*NOTA:</b> Los espacios rellenados en color gris, son de uso exclusivo del Facilitador de Grupos de Proyecto.
            </td>
        </tr>
    </table>

</body>

</html>


