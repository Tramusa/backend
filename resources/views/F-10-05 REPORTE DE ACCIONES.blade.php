```html
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
    font-size:11px;
    color:#000;
    margin:0;
    padding:6px;
}

table{
    width:100%;
    border-collapse:collapse;
}

td,
th{
    border:1px solid #000;
    padding:2px 4px;
    vertical-align:middle;
    line-height:1.1;
}

.logo{
    width:110px;
}

.company{
    text-align:center;
    font-size:15px;
    font-weight:bold;
}

.title{
    text-align:center;
    font-size:15px;
    font-weight:bold;
}

.headerTable{
    width:100%;
    border-collapse:collapse;
}

.headerTable td{
    background:#cf8f55;
    color:#fff;
    text-align:center;
    font-weight:bold;
    border:none;
    padding:3px;
}

.instructions{
    font-size:10px;
    line-height:1.25;
    margin-top:4px;
}

.blue{
    background:#1d4ed8;
    color:#fff;
    font-weight:bold;
}

.gray{
    background:#e5e5e5;
    font-weight:bold;
}

.center{
    text-align:center;
}

.bold{
    font-weight:bold;
}

.small{
    font-size:9px;
}

.npr{

    font-size:24px;

    color:#003cff;

    font-weight:bold;

    text-align:center;

}

.noBorder{

    border:none !important;

}

</style>

</head>

<body>

<!-- ===================================================== -->
<!-- ENCABEZADO -->
<!-- ===================================================== -->

<table>

    <tr>

        <td style="width:120px;text-align:center;">

            @if($logoImage)

                <img
                    src="{{ $logoImage }}"
                    class="logo"
                >

            @endif

        </td>

        <td>

            <div class="company">

                TRAMUSA CARRIER S.A. DE C.V.

            </div>

            <br>

            <div class="title">

                REPORTE DE ACCIONES

            </div>

            <table class="headerTable">

                <tr>

                    <td style="width:55%;">

                        ÁREA: COORDINACIÓN DE CALIDAD

                    </td>

                    <td style="width:20%;">

                        F-02-14

                    </td>

                    <td style="width:25%;">

                        REVISIÓN: JUNIO 2018

                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

<br>

<!-- ===================================================== -->
<!-- INSTRUCCIONES -->
<!-- ===================================================== -->

<table>

    <tr>

        <td class="noBorder instructions">

            <b>INSTRUCCIONES:</b>

            Documente en este formato toda la información relacionada con la No
            Conformidad u Oportunidad de Mejora, incluyendo evaluación,
            análisis de causa raíz y acciones implementadas para su atención.

        </td>

    </tr>

</table>

<br>

<!-- ===================================================== -->
<!-- INFORMACIÓN GENERAL -->
<!-- ===================================================== -->

<table>

    <tr>

        <td
            class="gray center"
            style="width:25%;"
        >

            NO CONFORMIDAD /
            OPORTUNIDAD DE MEJORA

        </td>

        <td
            class="center"
            style="width:15%;"
        >

            {{ $nonConformity->number }}

        </td>

        <td
            class="gray center"
            style="width:20%;"
        >

            FECHA DETECCIÓN

        </td>

        <td
            class="center"
            style="width:20%;"
        >

            {{ strtolower(\Carbon\Carbon::parse($nonConformity->date)->locale('es')->translatedFormat('d-M-y')) }}

        </td>

        <td
            class="gray center"
            style="width:20%;"
        >

            NPR

        </td>

    </tr>

    <tr>

        <td
            class="gray center"
        >

            RESPONSABLE

        </td>

        <td colspan="3">

            {{ optional($nonConformity->responsible)->name }}

            {{ optional($nonConformity->responsible)->a_paterno }}

            {{ optional($nonConformity->responsible)->a_materno }}

        </td>

        <td
            rowspan="3"
            class="npr"
        >

            {{ optional($nonConformity->evaluation)->total }}

        </td>

    </tr>

    <tr>

        <td
            class="gray center"
        >

            ÁREA

        </td>

        <td colspan="3">

            {{ $nonConformity->area }}

        </td>

    </tr>

    <tr>

        <td
            class="gray center"
        >

            FECHA COMPROMISO

        </td>

        <td colspan="3">

            {{ strtolower(\Carbon\Carbon::parse($nonConformity->date_commitment)->locale('es')->translatedFormat('d-M-y')) }}

        </td>

    </tr>

</table>

<br>
```html
<!-- ===================================================== -->
<!-- DESCRIPCIÓN DEL PROBLEMA -->
<!-- ===================================================== -->

<table>

    <tr>

        <td
            class="blue center"
            style="width:22%;"
        >

            DESCRIPCIÓN DEL PROBLEMA

        </td>

        <td>

            {{ $nonConformity->problem }}

        </td>

    </tr>

</table>

<br>

<!-- ===================================================== -->
<!-- INFORMACIÓN DE DETECCIÓN -->
<!-- ===================================================== -->

<table>

    <tr>

        <td
            class="gray center"
            style="width:20%;"
        >

            DETECTADO EN

        </td>

        <td style="width:30%;">

            {{ $nonConformity->detected }}

        </td>

        <td
            class="gray center"
            style="width:15%;"
        >

            AFECTA

        </td>

        <td style="width:35%;">

            {{ $nonConformity->affects }}

        </td>

    </tr>

    <tr>

        <td
            class="gray center"
        >

            TIPO

        </td>

        <td>

            {{ $nonConformity->type == 'non_conformity'
                ? 'NO CONFORMIDAD'
                : 'OPORTUNIDAD DE MEJORA'
            }}

        </td>

        <td
            class="gray center"
        >

            ESTATUS

        </td>

        <td>

            {{ strtoupper($nonConformity->status) }}

        </td>

    </tr>

</table>

<br>

<!-- ===================================================== -->
<!-- EVALUACIÓN -->
<!-- ===================================================== -->

<table>

    <tr>

        <td
            colspan="4"
            class="blue center"
        >

            EVALUACIÓN DE RIESGO

        </td>

    </tr>

    <tr>

        <td class="gray center">

            SEVERIDAD

        </td>

        <td class="gray center">

            OCURRENCIA

        </td>

        <td class="gray center">

            DETECTABILIDAD

        </td>

        <td class="gray center">

            TOTAL NPR

        </td>

    </tr>

    <tr>

        <td class="center">

            {{ optional($nonConformity->evaluation)->severity }}

        </td>

        <td class="center">

            {{ optional($nonConformity->evaluation)->occurrence }}

        </td>

        <td class="center">

            {{ optional($nonConformity->evaluation)->detectability }}

        </td>

        <td
            class="center"
            style="
                font-size:20px;
                font-weight:bold;
                color:#003cff;
            "
        >

            {{ optional($nonConformity->evaluation)->total }}

        </td>

    </tr>

</table>

<br>

<!-- ===================================================== -->
<!-- CAUSAS RAÍZ -->
<!-- ===================================================== -->

<table>

    <tr>

        <td
            colspan="2"
            class="blue center"
        >

            CAUSAS RAÍZ SELECCIONADAS

        </td>

    </tr>

    <tr>

        <td
            class="gray center"
            style="width:8%;"
        >

            No.

        </td>

        <td
            class="gray center"
        >

            DESCRIPCIÓN

        </td>

    </tr>

    @forelse($nonConformity->actionPlanCauses as $index => $cause)

        <tr>

            <td class="center">

                {{ $index + 1 }}

            </td>

            <td>

                {{ $cause->main_cause }}

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="2" class="center">

                Sin causas raíz registradas.

            </td>

        </tr>

    @endforelse

</table>

<br>
<!-- ===================================================== -->
<!-- PLAN DE ACCIÓN RESUMIDO -->
<!-- ===================================================== -->

<table>

    <tr>

        <td
            colspan="4"
            class="blue center"
        >
            PLAN DE ACCIÓN
        </td>

    </tr>

    <tr>

        <td
            class="gray center"
            style="width:40%;"
        >
            ACCIÓN CORRECTIVA
        </td>

        <td
            class="gray center"
            style="width:20%;"
        >
            RESPONSABLE
        </td>

        <td
            class="gray center"
            style="width:20%;"
        >
            FECHA COMPROMISO
        </td>

        <td
            class="gray center"
            style="width:20%;"
        >
            ESTATUS
        </td>

    </tr>

    @php
        $hasActions = false;
    @endphp

    @foreach($nonConformity->actionPlanCauses as $cause)

        @foreach($cause->correctiveActions as $action)

            @php
                $hasActions = true;
            @endphp

            <tr>

                <td>

                    {{ $action->corrective_action }}

                </td>

                <td class="center">

                    {{ optional($action->responsible)->name }}
                    {{ optional($action->responsible)->a_paterno }}

                </td>

                <td class="center">

                    @if($action->date_commitment)

                        {{ \Carbon\Carbon::parse($action->date_commitment)->format('d/m/Y') }}

                    @endif

                </td>

                <td class="center">

                    {{ strtoupper($action->status ?? 'PENDIENTE') }}

                </td>

            </tr>

        @endforeach

    @endforeach

    @if(!$hasActions)

        <tr>

            <td colspan="4" class="center">

                Sin acciones correctivas registradas.

            </td>

        </tr>

    @endif

</table>

<br>

<!-- ===================================================== -->
<!-- FIRMAS -->
<!-- ===================================================== -->

<table>

    <tr>

        <td
            class="gray center"
            style="width:50%;"
        >
            ELABORÓ
        </td>

        <td
            class="gray center"
            style="width:50%;"
        >
            RESPONSABLE
        </td>

    </tr>

    <tr>

        <td
            style="
                height:80px;
                vertical-align:bottom;
                text-align:center;
            "
        >

            ______________________________

            <br><br>

            COORDINACIÓN DE CALIDAD

        </td>

        <td
            style="
                height:80px;
                vertical-align:bottom;
                text-align:center;
            "
        >

            ______________________________

            <br><br>

            {{ $nonConformity->responsible->name ?? '' }}
            {{ $nonConformity->responsible->a_paterno ?? '' }}
            {{ $nonConformity->responsible->a_materno ?? '' }}

        </td>

    </tr>

</table>

</body>

</html>

