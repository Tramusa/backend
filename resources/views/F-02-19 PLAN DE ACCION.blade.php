```html
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>PLAN DE ACCIÓN</title>

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

td,th{
    border:1px solid #000;
    padding:1px 3px;
    vertical-align:middle;
    line-height:1.05;
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
    font-size:14px;
    font-weight:bold;
    margin-top:2px;
}

.headerTable{
    border:1px solid #000;
    width:100%;
    border-collapse:collapse;
}

.headerTable td{
    background:#e69043;
    color:#fff;
    font-weight:bold;
    text-align:center;
    border:none;
    padding:2px;
}

.instructionsTable{
    border:none;
}

.instructionsTable td{
    border:none !important;
    padding:0;
    font-size:10px;
    line-height:1.2;
}

.legend{
    width:88%;
    margin-top:2px;
}

.legend td{
    border:1px solid #c8c8c8;
    padding:1px 3px;
}

.square{
    width:30px;
}

.yellow{
    background:#ffe600;
}

.blue{
    background:#00b0f0;
}

.green{
    background:#cdeec7;
}

.problem-header{
    background: #ffffff;
    color: #ff0000;
    font-weight: bold;
    border: 1px solid #ff0000;
}

.problem-body{
    background: #ff0000;
    color: #ffffff;
    font-weight: bold;
    border: 1px solid #ff0000;
}

.problem-date{
    background: #ffffff;
    color: #ff0000;
    font-weight: bold;
    text-align: center;
    border: 1px solid #ff0000;
}

.problem-date-value{
    background: #ff0000;
    color: #ffffff;
    font-weight: bold;
    text-align: center;
    border: 1px solid #ff0000;
    font-size: 14px;
}

.gray{
    background:#efefef;
    font-weight:bold;
    text-align:center;
}

.center{
    text-align:center;
}

.small{
    font-size:9px;
}

.blank{
    background:#fff;
}

</style>

</head>

<body>
    <!-- ENCABEZADO -->
    <table>
        <tr>
            <td style="width:115px;text-align:center;">
                @if($logoImage)
                    <img src="{{ $logoImage }}" class="logo">
                @endif
            </td>
            <td>
                <div class="company">
                    TRAMUSA CARRIER S.A. DE C.V.
                </div><br>
                <div class="title">
                    PLAN DE ACCIÓN
                </div>
                <table class="headerTable">
                    <tr>
                        <td style="width:55%;">
                            ÁREA: COORDINACIÓN DE CALIDAD
                        </td>
                        <td style="width:20%;">
                            F-02-19
                        </td>
                        <td style="width:26%;">
                            REVISIÓN: JUNIO 2018
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    <!-- INSTRUCCIONES -->
    <table class="instructionsTable">
        <tr>
            <td>
                <b>INSTRUCCIONES:</b><br>

                <b>Si aplica Acciones Correctivas:</b>
                Para cada causa principal de la no conformidad, determine las acciones que se tomarán para eliminar la causa estableciendo fecha compromiso de solución a la acción y responsable de darle seguimiento, así como cada actividad requerida para la acción correctiva.
                <br>

                <b>Si aplicará sólo Correcciones:</b>
                llenar los espacios de Acción correctiva, no se llenan los espacios de Actividades.
            </td>
        </tr>
    </table><br>

    <!-- LEYENDA -->
    <table style="width:100%; border-collapse:collapse; margin-top:2px;">

        <tr>

            <!-- Cuadro amarillo -->
            <td style="
                width:28px;
                background:#ffe600;
                border:1px solid #000;
            "></td>

            <!-- Texto sin borde -->
            <td style="
                border:none;
                padding-left:6px;
            ">
                Causa principal de la no conformidad
            </td>

        </tr>

        <tr>

            <!-- Cuadro azul -->
            <td style="
                background:#00b0f0;
                border:1px solid #000;
            "></td>

            <!-- Texto sin borde -->
            <td style="
                border:none;
                padding-left:6px;
            ">
                Acción correctiva para eliminar la causa.
            </td>

        </tr>

        <tr>

            <!-- Cuadro verde -->
            <td style="
                background:#cdeec7;
                border:1px solid #000;
            "></td>

            <!-- Texto sin borde -->
            <td style="
                border:none;
                padding-left:6px;
            ">
                Actividades
            </td>

        </tr>

    </table><br>

    <!-- PROBLEMA -->
    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td class="problem-header" style="width:65%;">
                PROBLEMA O ÁREA DE OPORTUNIDAD:
            </td>
            <td class="problem-date" style="width:20%;" rowspan="2">
                FECHA COMPROMISO DE TERMINACIÓN DE ACCIONES
            </td>
            <td class="problem-date-value" style="width:15%;" rowspan="2">
                {{ strtolower(\Carbon\Carbon::parse($nonConformity->date_commitment)->locale('es')->translatedFormat('d-M-y')) }}
            </td>
        </tr>
        <tr>
            <td class="problem-body">
                {{ $nonConformity->problem }}
            </td>
        </tr>
    </table><br>

    <!-- ENCABEZADO TABLA -->
    <table>
        <tr>
            <td style="width:57%;background:#fff;"></td>
            <td class="gray" style="width:10%;">
                FECHA  COMPROM.
            </td>
            <td class="gray" style="width:21%;">
                RESPONSABLE DE   SEGUIMIENTO
            </td>
            <td class="gray" style="width:12%;">
                FIRMA DE RESPONSABLE
            </td>
        </tr>

        @forelse($nonConformity->actionPlanCauses as $cause)

            <tr class="yellow">
                <td>
                    {{ $cause->main_cause ?? '. ' }}
                </td>
                <td class="center">
                    {{ strtolower(\Carbon\Carbon::parse($cause->date_commitment)->locale('es')->translatedFormat('d-M-y')) }}
                </td>
                <td class="center">
                    {{ optional($cause->responsible)->name }}
                    {{ optional($cause->responsible)->a_paterno }}
                </td>
                <td></td>
            </tr>

            @foreach($cause->correctiveActions as $action)
                <tr class="blue">
                    <td>
                        {{ $action->corrective_action }}
                    </td>
                    <td class="center">
                        {{ strtolower(\Carbon\Carbon::parse($action->date_commitment)->locale('es')->translatedFormat('d-M-y')) }}
                    </td>
                    <td class="center">
                        {{ optional($action->responsible)->name }}
                        {{ optional($action->responsible)->a_paterno }}
                    </td>
                    <td></td>
                </tr>

                @foreach($action->activities as $activity)
                    <tr class="green">
                        <td>
                            {{ $activity->activity }}
                        </td>
                        <td class="center">
                            {{ strtolower(\Carbon\Carbon::parse($activity->date_commitment)->locale('es')->translatedFormat('d-M-y')) }}
                        </td>
                        <td class="center">
                            {{ optional($activity->responsible)->name }}
                            {{ optional($activity->responsible)->a_paterno }}
                        </td>
                        <td></td>
                    </tr>
                @endforeach

            @endforeach


            <!-- Renglones vacíos para escribir -->
            @for($i=0;$i<4;$i++)
                <tr class="green">
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor


            @empty
            <tr>
                <td colspan="4" style="height:80px;text-align:center;">
                    Sin plan de acción registrado.
                </td>
            </tr>
        @endforelse
    </table>

</body>
</html>
