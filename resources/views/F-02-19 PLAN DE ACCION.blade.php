<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>PLAN DE ACCIÓN</title>

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 10px;
    color: #000;
    margin: 0;
    padding: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td, th {
    border: 1px solid #555;
    padding: 4px;
    vertical-align: middle;
}

.logo {
    width: 130px;
}

.company {
    font-size: 15px;
    font-weight: bold;
    text-align: center;
}

.title {
    font-size: 14px;
    font-weight: bold;
    text-align: center;
}

.orange {
    background: #f79646;
    color: #fff;
    font-weight: bold;
    text-align: center;
}

.instructions {
    border: none;
    padding: 2px 0;
    font-size: 9px;
}

.red {
    background: #ff0000;
    color: #fff;
    font-weight: bold;
}

.yellow {
    background: #ffff00;
    font-weight: bold;
}

.blue {
    background: #00b0f0;
}

.green {
    background: #c6efce;
}

.gray {
    background: #d9d9d9;
    font-weight: bold;
    text-align: center;
}

.center {
    text-align: center;
}

.no-border {
    border: none;
}

.small {
    font-size: 8px;
}

.signature {
    height: 55px;
    vertical-align: bottom;
    text-align: center;
    font-weight: bold;
}
</style>
</head>

<body>

<table>
    <tr>
        <td style="width: 18%;" class="center">
            @if($logoImage)
                <img src="{{ $logoImage }}" class="logo">
            @endif
        </td>

        <td style="width: 82%;">
            <div class="company">TRAMUSA CARRIER S.A. DE C.V.</div>
            <div class="title">PLAN DE ACCIÓN</div>

            <table>
                <tr>
                    <td class="orange">ÁREA: COORDINACIÓN DE CALIDAD</td>
                    <td class="orange">F-02-19</td>
                    <td class="orange">REVISIÓN: JUNIO 2018</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="margin-top: 4px;">
    <tr>
        <td class="instructions">
            <strong><u>INSTRUCCIONES:</u></strong><br>
            <strong>Si aplica Acciones Correctivas:</strong>
            Para cada causa principal de la no conformidad, determine las acciones que se tomarán para eliminar la causa,
            estableciendo fecha compromiso de solución a la acción y el responsable de darle seguimiento, así como a cada
            actividad requerida para la acción correctiva.
            <br>
            <strong>Si aplicará solo Correcciones:</strong>
            llenar los espacios de Acción correctiva, no se llenan los espacios de Actividades.
        </td>
    </tr>
</table>

<table style="margin-top: 3px; width: 70%;">
    <tr>
        <td style="width: 35px;" class="yellow"></td>
        <td>Causa principal de la no conformidad</td>
    </tr>
    <tr>
        <td class="blue"></td>
        <td>Acción correctiva para eliminar la causa.</td>
    </tr>
    <tr>
        <td class="green"></td>
        <td>Actividades</td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td class="red" style="width: 72%;">
            PROBLEMA O ÁREA DE OPORTUNIDAD:<br>
            {{ $nonConformity->problem }}
        </td>

        <td class="red center" style="width: 28%;">
            FECHA COMPROMISO<br>
            DE TERMINACIÓN DE ACCIONES<br>
            <span style="font-size: 12px;">
                {{ $nonConformity->date_commitment ?? '-' }}
            </span>
        </td>
    </tr>
</table>

<table>
    <tr class="gray">
        <td style="width: 55%;">DESCRIPCIÓN</td>
        <td style="width: 15%;">FECHA COMPROMISO</td>
        <td style="width: 20%;">RESPONSABLE DE SEGUIMIENTO</td>
        <td style="width: 10%;">FIRMA</td>
    </tr>

    @forelse($nonConformity->actionPlanCauses as $cause)

        <tr class="yellow">
            <td>
                {{ $cause->main_cause ?: 'Causa principal' }}
            </td>
            <td class="center">
                {{ $cause->commitment_date ?? '' }}
            </td>
            <td class="center">
                {{ $cause->responsible->name ?? '' }}
                {{ $cause->responsible->a_paterno ?? '' }}
            </td>
            <td></td>
        </tr>

        @foreach($cause->correctiveActions as $action)
            <tr class="blue">
                <td>
                    {{ $action->corrective_action }}
                </td>
                <td class="center">
                    {{ $action->commitment_date ?? '' }}
                </td>
                <td class="center">
                    {{ $action->responsible->name ?? '' }}
                    {{ $action->responsible->a_paterno ?? '' }}
                </td>
                <td></td>
            </tr>

            @foreach($action->activities as $activity)
                <tr class="green">
                    <td>
                        {{ $activity->activity }}
                    </td>
                    <td class="center">
                        {{ $activity->commitment_date ?? '' }}
                    </td>
                    <td class="center">
                        {{ $activity->responsible->name ?? '' }}
                        {{ $activity->responsible->a_paterno ?? '' }}
                    </td>
                    <td></td>
                </tr>
            @endforeach
        @endforeach

        <tr>
            <td colspan="4" style="height: 8px; border-left: none; border-right: none;"></td>
        </tr>

    @empty
        <tr>
            <td colspan="4" class="center" style="height: 40px;">
                Sin plan de acción registrado.
            </td>
        </tr>
    @endforelse
</table>

<br><br>

<table>
    <tr>
        <td class="signature" style="width: 33%;">
            {{ $nonConformity->responsible->name ?? '-' }}
            {{ $nonConformity->responsible->a_paterno ?? '' }}
        </td>
        <td class="signature" style="width: 33%;"></td>
        <td class="signature" style="width: 33%;"></td>
    </tr>
    <tr class="gray">
        <td>RESPONSABLE</td>
        <td>REVISÓ</td>
        <td>AUTORIZÓ</td>
    </tr>
</table>

</body>
</html>