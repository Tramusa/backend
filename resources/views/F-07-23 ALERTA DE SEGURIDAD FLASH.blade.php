<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">

    <title>F-07-23-R2 ALERTA DE SEGURIDAD</title>

    <style>

        /* =========================================================
           CONFIGURACIÓN GENERAL
        ========================================================= */

        @page {
            margin: 25px 25px 30px 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #000;
            background: #FFFFFF;
            font-size: 10px;
            font-family: "Arial Narrow", Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #000;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .break-text {
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-word;
            margin: 0;
            font-family: inherit;
            font-size: inherit;
        }

        /* =========================================================
           ENCABEZADO
        ========================================================= */

        .header {
            width: 100%;
            margin-bottom: 8px;
        }

        .header-logo {
            width: 20%;
            height: 85px;
            border: 2px solid #D1D1D1;
            float: left;
            text-align: center;
            vertical-align: middle;
        }

        .header-info {
            width: 80%;
            height: 85px;
            border: 2px solid #D1D1D1;
            float: left;
        }

        .logoImg {
            max-width: 95%;
            max-height: 70px;
            margin-top: 7px;
        }

        .company {
            color: #0073B5;
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            margin-top: 7px;
            margin-bottom: 3px;
        }

        .main-title {
            color: #000000;
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            margin: 2px 0 5px 0;
        }

        .header-footer {
            color: #FFFFFF;
            background: #1E4E79;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            padding: 4px 3px;
        }

        /* =========================================================
           DATOS GENERALES
        ========================================================= */

        .section-title {
            background: #1E4E79;
            color: #FFFFFF;
            font-weight: bold;
            text-align: center;
            padding: 4px;
            font-size: 10px;
        }

        .label {
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            margin-bottom: 8px;
        }

        .data-table td {
            padding: 5px;
            vertical-align: top;
            height: 30px;
        }

        .data-table .label {
            font-size: 8px;
            display: block;
            margin-bottom: 2px;
        }

        .data-table .value {
            font-size: 10px;
            min-height: 12px;
        }

        /* =========================================================
           CAMPOS DE TEXTO
        ========================================================= */

        .text-section {
            width: 100%;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        .text-title {
            background: #1E4E79;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 10px;
            padding: 4px 5px;
            text-align: left;
        }

        .text-content {
            border: 1px solid #000;
            padding: 7px;
            min-height: 70px;
            font-size: 10px;
            line-height: 1.35;
        }

        .text-content-large {
            min-height: 100px;
        }

        /* =========================================================
           IMÁGENES
        ========================================================= */

        .images-section {
            width: 100%;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        .images-title {
            background: #1E4E79;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 10px;
            padding: 4px;
            text-align: center;
        }

        .image-cell {
            width: 50%;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
            height: 190px;
        }

        .image-cell img {
            max-width: 100%;
            max-height: 175px;
        }

        .image-cell-full {
            width: 100%;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
        }

        .image-cell-full img {
            max-width: 100%;
            max-height: 300px;
        }

        .no-images {
            text-align: center;
            padding: 15px;
            font-size: 9px;
            color: #555;
        }

        /* =========================================================
           FIRMAS
        ========================================================= */

        .firmas {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .firmas td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 8px;
            border: 1px solid #000;
        }

        .firma-box {
            height: 55px;
        }

        .firma-linea {
            border-top: 1px solid #000;
            margin: 2px 20px;
        }

        .firma-nombre {
            font-size: 9px;
            margin-top: 3px;
        }

        .firma-rol {
            font-size: 9px;
            font-weight: bold;
            margin-top: 2px;
        }

        /* =========================================================
           PIE DEL FORMATO
        ========================================================= */

        .footer-format {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
            color: #000;
        }

        /* =========================================================
           SALTOS
        ========================================================= */

        .page-break {
            page-break-before: always;
        }

        tr {
            page-break-inside: avoid;
        }

        td {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>

    {{-- =========================================================
         ENCABEZADO
    ========================================================== --}}

    <div class="header clearfix">

        <div class="header-logo">

            @if(isset($logoImage) && $logoImage)
                <img
                    src="{{ $logoImage }}"
                    class="logoImg"
                >
            @endif

        </div>

        <div class="header-info">

            <div class="company">
                TRAMUSA CARRIER S.A. DE C.V.
            </div>

            <div class="main-title">
                ALERTA DE SEGURIDAD
            </div>

            <div class="header-footer">
                ÁREA: SEGURIDAD E HIGIENE
                &nbsp;&nbsp;
                F-07-23/R2
                &nbsp;&nbsp;
                PERIODICIDAD: CUANDO SE PRESENTE
                &nbsp;&nbsp;
                RESGUARDO: 5 AÑOS
                &nbsp;&nbsp;
                REVISIÓN: MARZO 2022
            </div>

        </div>

    </div>


    {{-- =========================================================
         INFORMACIÓN GENERAL
    ========================================================== --}}

    <table class="data-table">

        <tr>

            <td style="width: 25%;">

                <span class="label">
                    FOLIO:
                </span>

                <div class="value">
                    {{ $alert->folio ?? 'N/A' }}
                </div>

            </td>

            <td style="width: 50%;">

                <span class="label">
                    ÁREA / LUGAR:
                </span>

                <div class="value break-text">
                    {{ $alert->area_lugar ?? 'N/A' }}
                </div>

            </td>

            <td style="width: 25%;">

                <span class="label">
                    FECHA:
                </span>

                <div class="value">
                    {{ $alert->fecha ?? 'N/A' }}
                </div>

            </td>

        </tr>


        <tr>

            <td>

                <span class="label">
                    HORA:
                </span>

                <div class="value">
                    {{ $alert->hora ?? 'N/A' }}
                </div>

            </td>

            <td>

                <span class="label">
                    DEPARTAMENTO:
                </span>

                <div class="value break-text">
                    {{ $alert->departamento ?? 'N/A' }}
                </div>

            </td>

            <td>

                <span class="label">
                    CLASIFICACIÓN ACTUAL:
                </span>

                <div class="value break-text">
                    {{ $alert->clasificacion_actual ?? 'N/A' }}
                </div>

            </td>

        </tr>


        <tr>

            <td>

                <span class="label">
                    UNIDAD:
                </span>

                <div class="value break-text">
                    {{ $alert->unidad ?? 'N/A' }}
                </div>

            </td>

            <td>

                <span class="label">
                    OPERADOR:
                </span>

                <div class="value break-text">
                    {{ $alert->operador ?? 'N/A' }}
                </div>

            </td>

            <td>

                <span class="label">
                    AFECTADO:
                </span>

                <div class="value break-text">
                    {{ $alert->afectado ?? 'N/A' }}
                </div>

            </td>

        </tr>


        <tr>

            <td>

                <span class="label">
                    PUESTO DEL AFECTADO:
                </span>

                <div class="value break-text">
                    {{ $alert->puesto_afectado ?? 'N/A' }}
                </div>

            </td>

            <td>

                <span class="label">
                    SUPERVISOR / MONITOR EN TURNO:
                </span>

                <div class="value break-text">
                    {{ $alert->supervisor_monitor ?? 'N/A' }}
                </div>

            </td>

            <td>

                <span class="label">
                    LOGÍSTICA:
                </span>

                <div class="value break-text">
                    {{ $alert->logistica ?? 'N/A' }}
                </div>

            </td>

        </tr>

    </table>


    {{-- =========================================================
         DESCRIPCIÓN DEL INCIDENTE
    ========================================================== --}}

    <div class="text-section">

        <div class="text-title">
            DESCRIPCIÓN DEL INCIDENTE / ACCIDENTE
        </div>

        <div class="text-content text-content-large break-text">

            {!! nl2br(e($alert->descripcion ?? 'N/A')) !!}

        </div>

    </div>


    {{-- =========================================================
         INVESTIGACIÓN
    ========================================================== --}}

    <div class="text-section">

        <div class="text-title">
            INVESTIGACIÓN DEL ACCIDENTE
        </div>

        <div class="text-content text-content-large break-text">

            {!! nl2br(e($alert->investigacion ?? 'N/A')) !!}

        </div>

    </div>


    {{-- =========================================================
         ACCIONES
    ========================================================== --}}

    <div class="text-section">

        <div class="text-title">
            ACCIONES PARA EVITAR SU REPETICIÓN
        </div>

        <div class="text-content text-content-large break-text">

            {!! nl2br(e($alert->acciones_repeticion ?? 'N/A')) !!}

        </div>

    </div>


    {{-- =========================================================
         EVIDENCIA FOTOGRÁFICA
    ========================================================== --}}

    @php

        $evidencias = $alert->images
            ->where('tipo', 'evidencia')
            ->sortBy('orden');

    @endphp

    <div class="images-section">

        <div class="images-title">
            EVIDENCIA (FOTOGRÁFICA, MAPA, BOSQUEJOS)
        </div>

        @if($evidencias->count() > 0)

            @foreach($evidencias->chunk(2) as $grupo)

                <table>

                    <tr>

                        @foreach($grupo as $imagen)

                            <td class="image-cell">

                                @if($imagen->imagen)

                                    <img
                                        src="{{ Storage::disk('public')->path($imagen->imagen) }}"
                                    >

                                @endif

                            </td>

                        @endforeach


                        {{-- Completar segunda celda --}}

                        @if($grupo->count() == 1)

                            <td class="image-cell"></td>

                        @endif

                    </tr>

                </table>

            @endforeach

        @else

            <div class="no-images">
                SIN EVIDENCIA FOTOGRÁFICA
            </div>

        @endif

    </div>


    {{-- =========================================================
         ANEXOS
    ========================================================== --}}

    @php

        $anexos = $alert->images
            ->where('tipo', 'anexo')
            ->sortBy('orden');

    @endphp

    <div class="images-section">

        <div class="images-title">
            ANEXOS
        </div>

        @if($anexos->count() > 0)

            @foreach($anexos->chunk(2) as $grupo)

                <table>

                    <tr>

                        @foreach($grupo as $imagen)

                            <td class="image-cell">

                                @if($imagen->imagen)

                                    <img
                                        src="{{ Storage::disk('public')->path($imagen->imagen) }}"
                                    >

                                @endif

                            </td>

                        @endforeach


                        @if($grupo->count() == 1)

                            <td class="image-cell"></td>

                        @endif

                    </tr>

                </table>

            @endforeach

        @else

            <div class="no-images">
                SIN ANEXOS
            </div>

        @endif

    </div>


    {{-- =========================================================
         WEBFLEET
    ========================================================== --}}

    @php

        $webfleet = $alert->images
            ->where('tipo', 'webfleet')
            ->sortBy('orden');

    @endphp

    <div class="images-section">

        <div class="images-title">
            WEBFLEET
        </div>

        @if($webfleet->count() > 0)

            @foreach($webfleet as $imagen)

                <table>

                    <tr>

                        <td class="image-cell-full">

                            @if($imagen->imagen)

                                <img
                                    src="{{ Storage::disk('public')->path($imagen->imagen) }}"
                                >

                            @endif

                        </td>

                    </tr>

                </table>

            @endforeach

        @else

            <div class="no-images">
                SIN IMAGEN WEBFLEET
            </div>

        @endif

    </div>


    {{-- =========================================================
         ANEXOS TEXTO
    ========================================================== --}}

    <div class="text-section">

        <div class="text-title">
            ANEXOS / INFORMACIÓN ADICIONAL
        </div>

        <div class="text-content break-text">

            {!! nl2br(e($alert->anexos ?? 'N/A')) !!}

        </div>

    </div>


    {{-- =========================================================
         NOMBRE DE QUIEN REPORTA
    ========================================================== --}}

    <table class="firmas">

        <tr>

            <td>

                <div class="firma-box">

                    @if(!empty($alert->firma_reportado))

                        <img
                            src="{{ $alert->firma_reportado }}"
                            style="max-height:50px; max-width:180px;"
                        >

                    @else

                        <div style="padding-top:25px;">
                            {{ $alert->reportado_por ?? '' }}
                        </div>

                    @endif

                </div>

                <div class="firma-linea"></div>

                <div class="firma-nombre">
                    {{ $alert->reportado_por ?? 'N/A' }}
                </div>

                <div class="firma-rol">
                    NOMBRE Y FIRMA DE QUIEN REPORTA
                </div>

            </td>


            <td>

                <div class="firma-box"></div>

                <div class="firma-linea"></div>

                <div class="firma-nombre">
                    {{ $alert->supervisor_monitor ?? 'N/A' }}
                </div>

                <div class="firma-rol">
                    SUPERVISOR / MONITOR
                </div>

            </td>

        </tr>

    </table>


    {{-- =========================================================
         PIE
    ========================================================== --}}

    <div class="footer-format">

        TRAMUSA CARRIER S.A. DE C.V.
        &nbsp;&nbsp;|&nbsp;&nbsp;
        ÁREA: SEGURIDAD E HIGIENE
        &nbsp;&nbsp;|&nbsp;&nbsp;
        F-07-23/R2
        &nbsp;&nbsp;|&nbsp;&nbsp;
        RESGUARDO: 5 AÑOS
        &nbsp;&nbsp;|&nbsp;&nbsp;
        REVISIÓN: MARZO 2022

    </div>

</body>

</html>