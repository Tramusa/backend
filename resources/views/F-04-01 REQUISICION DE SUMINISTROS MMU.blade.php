<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>REQUISICIÓN DE SUMINISTROS Y/O SERVICIOS</title>

<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    font-size:10px;
    color:#000;
    margin:0;
    padding:12px;
}

/* SIN BORDE GENERAL */
.document{
    padding:0;
}

table{
    width:100%;
    border-collapse:collapse;
}

td{
    padding:4px;
    vertical-align:middle;
}

table td{
    padding:4px;
    vertical-align:top;
    word-wrap:break-word;
    word-break:break-word;
}

.border{
    border:1px solid #7F7F7F;
}

.gray-bg{
    background:#C9C9C9;
    font-weight:bold;
}

.blue-bg{
    background:#005AA9;
    color:#FFF;
    font-size:8px;
    font-weight:bold;
    text-align:center;
}

.center{
    text-align:center;
}

.logo{
    width:180px;
}

.company{
    text-align:center;
    font-size:14px;
    font-weight:bold;
}

.document-title{
    text-align:center;
    font-size:14px;
    font-weight:bold;
    margin-top:4px;
}

.folio-title{
    background:#D9D9D9;
    border:1px solid #7F7F7F;
    text-align:center;
    font-weight:bold;
}

.folio-value{
    border:1px solid #7F7F7F;
    text-align:center;
    font-size:15px;
    color:#2E74B5;
    font-weight:bold;
}

.observaciones{
    height:60px;
}

.signature-box{
    border:1px solid #7F7F7F;
    height:80px;
    text-align:center;
    vertical-align:bottom;
    font-weight:bold;
}

.signature-title{
    border:1px solid #7F7F7F;
    background:#D9D9D9;
    text-align:center;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="document">

    {{-- ENCABEZADO --}}
    <table>
        <tr>

            <td class="border center" style="width:20%;">
                <img src="{{ $logoImage }}" class="logo">
            </td>

            <td class="border" style="width:80%;">
                <div class="company">
                    MULTISERVICIOS MURILLO SA DE CV
                </div>
                <div class="document-title">
                    REQUISICIÓN DE SUMINISTROS Y/O SERVICIOS
                </div>
                <table>
                    <tr class="blue-bg">
                        <td>AREA: ADMINISTRACIÓN</td>
                        <td>F-04-01</td>
                        <td>FRECUENCIA: CUANDO SEA NECESARIO</td>
                        <td>RESGUARDO: 5 AÑOS</td>
                        <td>REVISIÓN: JULIO 2021 R1</td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>

    {{-- FECHA Y FOLIO --}}
    <table style="margin-top:10px;">
        <tr>

            <td style="width:8%;">
                <strong>FECHA:</strong>
            </td>

            <td style="width:52%; border-bottom:1px solid #7F7F7F;">
                {{ $fecha ?? '-' }}
            </td>

            <td style="width:18%;"></td>

            <td class="folio-title" style="width:10%;">
                REQUISICIÓN NO.
            </td>

            <td class="folio-value" style="width:12%;">
                OPE-{{ $Data->id }}
            </td>

        </tr>
    </table>

    <br>

    {{-- CUERPO PRINCIPAL --}}
    <table>
        <tr>

            {{-- IZQUIERDA --}}
            <td style="width:38%; vertical-align:top;">

                <table>

                    <tr>
                        <td class="gray-bg border">AREA SOLICITANTE:</td>
                    </tr>
                    <tr>
                        <td class="border">{{ $Data->work_areaInfo->name ?? '-' }}</td>
                    </tr>

                    <tr><td style="height:5px;"></td></tr>

                    <tr>
                        <td class="gray-bg border">NOMBRE SOLICITANTE:</td>
                    </tr>
                    <tr>
                        <td class="border">{{ $Data->collaboratorInfo->name ?? '-' }}</td>
                    </tr>

                    <tr><td style="height:5px;"></td></tr>

                    <tr>
                        <td class="gray-bg border">FECHA PROPUESTA DE SUMINISTRO:</td>
                    </tr>
                    <tr>
                        <td class="border">{{ $Data->supply_date ?? '-' }}</td>
                    </tr>

                </table>

            </td>

            <td style="width:2%;"></td>

            {{-- DERECHA --}}
            <td style="width:60%; vertical-align:top;">

                <table>

                    <tr>
                        <td colspan="2" class="gray-bg border center">
                            AREA DE APLICACIÓN:
                        </td>
                    </tr>

                    <tr>
                        <td class="gray-bg border " style="width:40%;">CODIGO CUENTA PADRE</td>
                        <td class="gray-bg border">NOMBRE CUENTA MAYOR</td>
                    </tr>

                    <tr>
                        <td class="border">{{ $Data->parent_accountInfo->id ?? '-' }}</td>
                        <td class="border">{{ $Data->parent_accountInfo->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td class="gray-bg border">CODIGO CUENTA TITULO</td>
                        <td class="gray-bg border">NOMBRE CUENTA TITULO</td>
                    </tr>

                    <tr>
                        <td class="border">{{ $Data->title_accountInfo->id ?? '-' }}</td>
                        <td class="border">{{ $Data->title_accountInfo->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td class="gray-bg border">CODIGO SUBTITULO</td>
                        <td class="gray-bg border">NOMBRE SUBCUENTA</td>
                    </tr>

                    <tr>
                        <td class="border">{{ $Data->subtitle_accountInfo->id ?? '-' }}</td>
                        <td class="border">{{ $Data->subtitle_accountInfo->name ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td class="gray-bg border">CODIGO MAYOR</td>
                        <td class="gray-bg border">SUB-SUBCUENTA</td>
                    </tr>

                    <tr>
                        <td class="border">{{ $Data->mayor_accountInfo->id ?? '-' }}</td>
                        <td class="border">{{ $Data->mayor_accountInfo->name ?? '-' }}</td>
                    </tr>

                </table>

            </td>

        </tr>
    </table>

    <br>

    {{-- OBSERVACIONES --}}
    <table>
        <tr>
            <td class="gray-bg border center">OBSERVACIONES</td>
        </tr>
        <tr>
            <td class="border observaciones">
                {{ $Data->observations ?? '' }}
            </td>
        </tr>
    </table>

    <br>

    {{-- DETALLE --}}
    <table>

        <tr class="gray-bg center">
            <td class="border" style="width:8%;">CANT.</td>
            <td class="border" style="width:12%;">CLAVE</td>
            <td class="border" style="width:10%;">U.M.</td>
            <td class="border" style="width:35%;">DESCRIPCIÓN</td>
            <td class="border" style="width:35%;">JUSTIFICACIÓN</td>
        </tr>

        @php
            $filasMinimas = 15;
            $filasActuales = count($detailsRequisitions);
        @endphp

        @forelse($detailsRequisitions as $detail)

            <tr style="height:22px;">
                <td class="border center">{{ $detail->cantidad ?? '' }}</td>
                <td class="border center">{{ $detail->id_product ?? '' }}</td>
                <td class="border center">{{ $detail->unit_measure ?? '' }}</td>
                <td class="border">{{ $detail->name ?? '' }}</td>
                <td class="border">{{ $detail->justific ?? '' }}</td>
            </tr>

        @empty

            @php $filasActuales = 0; @endphp

        @endforelse

        {{-- Completar filas vacías --}}
        @for($i = $filasActuales; $i < $filasMinimas; $i++)

            <tr style="height:22px;">
                <td class="border">&nbsp;</td>
                <td class="border">&nbsp;</td>
                <td class="border">&nbsp;</td>
                <td class="border">&nbsp;</td>
                <td class="border">&nbsp;</td>
            </tr>

        @endfor

    </table>
    <br><br>

    {{-- FIRMAS --}}
    <table style="width: 100%; border: 1px solid #000;">
        <tr>

            <tr>
                <td class="bottom-border-only" style="border: 1px solid #000; font-weight: bold; text-align: center;"><br><br><br>{{ $Data->collaboratorInfo->name ?? '-' }}<br>{{ $Data->work_areaInfo->name ?? '-' }}</td>
                <td class="bottom-border-only" style="border: 1px solid #000; font-weight: bold; text-align: center;"><br><br><br>{{ $Data->user_analyze->name ?? '-' }} {{ $Data->user_analyze->a_paterno ?? '-' }} {{ $Data->user_analyze->a_materno ?? '-' }}<br>{{ $Data->user_analyze->rol ?? '-' }}</td>
                <td class="bottom-border-only" style="border: 1px solid #000; font-weight: bold; text-align: center;"><br><br><br>{{ $Data->user_authorized->name ?? '-' }} {{ $Data->user_authorized->a_paterno ?? '-' }} {{ $Data->user_authorized->a_materno ?? '-' }}<br>{{ $Data->user_authorized->rol ?? '-' }}</td>
            </tr>

        </tr>

        <tr>

            <td class="signature-title" style="width: 33%; text-align: center;">AREA SOLICITANTE</td>
            <td class="signature-title" style="width: 33%; text-align: center;">ANALIZÓ</td>
            <td class="signature-title" style="width: 33%; text-align: center;">AUTORIZÓ</td>

        </tr>
    </table>

</div>

</body>
</html>
