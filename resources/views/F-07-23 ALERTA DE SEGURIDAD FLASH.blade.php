
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <style>

        @page {
            margin: 10px 10px 15px 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7px;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        td {
            border: 1px solid #666;
            padding: 2px 4px;
            vertical-align: middle;
        }

        .no-border {
            border: 0 !important;
        }

        /* ================= COLORES ======================== */

        .blue {
            background: #24577e;
            color: #fff;
        }

        .orange {
            background: #e9784f;
            color: #fff;
        }

        .yellow {
            background: #f6c84b;
        }

        .green-border {
            border: 2px solid #218c4c !important;
        }

        /* =================  ENCABEZADO ========================= */

        .header {
            height: 61px;
        }

        .header-logo {
            width: 20%;
            text-align: center;
        }

        .header-logo img {
            width: 135px;
            height: auto;
        }

        .header-title {
            width: 80%;
            padding: 0;
        }

        .company-name {
            height: 22px;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #0068b5;
            padding-top: 7px;
        }

        .security-title {
            height: 18px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            padding-top: 3px;
        }

        .orange-bar {
            height: 13px;
            background: #ef8c3a;
            color: #fff;
            font-size: 9px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-align: center;
            padding-top: 2px;
        }

        .folio {
            position: absolute;
            right: 12px;
            top: 72px;
            font-size: 10px;
            color: #e9784f;
            font-weight: bold;
        }

        /* ===============  ETIQUETAS ========================== */

        .label {
            background: #24577e;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 3px 4px;
        }

        .value {
            font-size: 9.5px;
            padding: 3px 5px;
        }

        .value-blue {
            color: #0067a9;
            font-weight: bold;
        }

        /* ==============  DATOS GENERALES ======================= */
        .general {
            margin-top: 7px;
        }

        .row-small {
            height: 26px;
        }

        .row-medium {
            height: 28px;
        }

        .row-supervisor {
            height: 33px;
        }

        /* ==============   CLASIFICACIÓN  ======================= */
        .classification {
            height: 44px;
        }

        .classification-label {
            width: 20%;
            background: #24577e;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            vertical-align: middle;
        }

        .classification-item {
            width: 11.42%;
            text-align: center;
            font-size: 9px;
            line-height: 10px;
            padding: 2px;
            font-weight: normal;
        }

        .classification-selected {
            background: #f6c84b;
            font-weight: bold;
        }

        .classification-selected .unit {
            display: block;
            margin-top: 9px;
            color: #0067a9;
            font-weight: bold;
        }

        /* ===============   SECCIONES  ==================== */
        .section-header {
            background: #24577e;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            height: 18px;
            padding: 4px 5px;
            border: 1px solid #555;
        }

        .description {
            height: 139px;
            border: 2px solid #000 !important;
            vertical-align: top;
            padding: 9px 5px;
            font-size: 10px;
            line-height: 11px;
            white-space: pre-line;
        }

        /* ==============   EVIDENCIA  =================== */
        .evidence-box {
            height: 175px;
            vertical-align: middle;
            padding: 5px;
        }

        .evidence-cell {
            width: 33.33%;
            height: 160px;
            border: 0 !important;
            text-align: center;
            vertical-align: middle;
            padding: 3px;
        }

        .evidence-image {
            width: 225px;
            height: 200px;
            display: block;
            margin: 0 auto;
        }

        /* ============= SALTO DE PÁGINA ====================== */
        .page-break {
            page-break-before: always;
        }

        /* ===============  ANEXOS  ================== */
        .annex-description {
            min-height: 45px;
            padding: 7px 6px;
            font-size: 9px;
            line-height: 10px;
            white-space: pre-line;
            vertical-align: top;
        }

        .annex-images-box {
            height: 215px;
            vertical-align: middle;
            padding: 6px;
        }

        .annex-cell {
            width: 50%;
            height: 200px;
            border: 0 !important;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
        }

        .annex-image {
            width: 300px;
            height: 212px;
            display: block;
            margin: 0 auto;
        }

        /* ================= WEBFLEET ===================== */

        .webfleet-box {
            height: 180px;
            padding: 6px;
            vertical-align: top;
        }

        .webfleet-image {
            width: 56%;
            height: auto;
            display: block;
            margin-left: 0;
        }

        /* ================  ACCIONES ========================= */

        .actions-box {
            min-height: 91px;
            padding: 6px;
            font-size: 9px;
            line-height: 11px;
            white-space: pre-line;
            vertical-align: top;
        }

        /* ================  FIRMA  ======================= */

        .signature-area {
            height: 64px;
            vertical-align: bottom;
            padding: 3px;
        }

        .signature-image {
            max-width: 145px;
            max-height: 43px;
            display: block;
            margin-left: 18px;
            margin-bottom: 1px;
        }

        .signature-name {
            font-size: 10px;
            font-weight: bold;
        }

        .signature-label {
            background: #24577e;
            color: white;
            font-weight: bold;
            font-size: 7px;
            height: 15px;
            padding: 3px;
        }

    </style>

</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | IMÁGENES
    |--------------------------------------------------------------------------
    */

    $evidencias = $alert->images
        ? $alert->images
            ->where('tipo', 'evidencia')
            ->sortBy('orden')
            ->values()
        : collect();


    $anexosImagenes = $alert->images
        ? $alert->images
            ->where('tipo', 'anexo')
            ->sortBy('orden')
            ->values()
        : collect();


    $webfleet = $alert->images
        ? $alert->images
            ->where('tipo', 'webfleet')
            ->sortBy('orden')
            ->values()
        : collect();


    /*
    |--------------------------------------------------------------------------
    | CLASIFICACIÓN
    |--------------------------------------------------------------------------
    */

    $clasificacion = strtolower(
        trim($alert->clasificacion_actual ?? '')
    );

@endphp

{{-- ======================  ENCABEZADO  ======================== --}}
<table class="header">
    <tr>
        <td class="header-logo">
            @if(!empty($logoImage))
                <img src="{{ $logoImage }}">
            @endif
        </td>

        <td class="header-title">
            <div class="company-name">TRAMUSA CARRIER S.A. DE C.V.</div>
            <div class="security-title">ALERTA DE SEGURIDAD</div>

            <div class="orange-bar">
                ÁREA: SEGURIDAD E HIGIENE&nbsp;&nbsp;&nbsp;
                F-07-23/R2&nbsp;&nbsp;&nbsp;
                PERIODICIDAD: CUANDO SE PRESENTE&nbsp;&nbsp;&nbsp;
                RESGUARDO: 5 AÑOS&nbsp;&nbsp;&nbsp;
                REVISIÓN: MARZO 2022
            </div>
        </td>
    </tr>
</table>
{{-- ==============   FOLIO ============================ --}}
<div class="folio">
    {{ $alert->folio ?? '' }}
</div><br>
{{-- ======================   DATOS GENERALES  ============================== --}}
<table class="general">
    {{-- ÁREA / LUGAR --}}
    <tr class="row-small">
        <td width="20%" class="label">ÁREA/LUGAR:</td>
        <td width="80%" class="value">{{ $alert->area_lugar ?? 'N/A' }}</td>
    </tr>
    {{-- FECHA / HORA --}}
    <tr class="row-small">
        <td class="label">FECHA:</td>
        <td class="value">
            <table>
                <tr>
                    <td class="no-border value" style="width: 42%;" >
                        {{ $alert->fecha ? \Carbon\Carbon::parse($alert->fecha)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="label" style="width: 14%;" >
                        HORA:
                    </td>
                    <td class="no-border value" style="width: 44%;" >
                        {{ $alert->hora ?? 'N/A' }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    {{-- DEPARTAMENTO --}}
    <tr class="row-small">
        <td class="label">
            DEPARTAMENTO:
        </td>
        <td class="value">
            {{ $alert->departamento ?? 'N/A' }}
        </td>
    </tr>
</table>
{{-- ================  CLASIFICACIÓN  ============================== --}}
<table class="classification">
    <tr>
        {{-- TÍTULO --}}
        <td class="classification-label">
            CLASIFICACIÓN ACTUAL:
        </td>
        {{-- DAÑO A EQUIPO --}}

        <td class="classification-item {{ $clasificacion === 'daño a equipo' ? 'classification-selected' : '' }}">
            Daño a<br>
            equipo
            @if($clasificacion === 'daño a equipo')
                <span class="unit">
                    {{ $alert->unidad ?? 'N/A' }}
                </span>
            @endif
        </td>
        {{-- INCIDENTE POTENCIAL --}}
        <td class="classification-item {{ $clasificacion === 'incidente potencial' ? 'classification-selected' : '' }}">
            Incidente<br>
            potencial
        </td>
        {{-- SIN INCAPACIDAD --}}
        <td class="classification-item {{ $clasificacion === 'sin incapacidad' ? 'classification-selected' : '' }}">
            Sin<br>
            incapacidad
        </td>
        {{-- INCAPACIDAD TEMPORAL --}}
        <td class="classification-item {{ $clasificacion === 'incapacidad temporal' ? 'classification-selected' : '' }}">
            Incapacidad<br>
            temporal
        </td>
        {{-- INCAPACIDAD PERMANENTE PARCIAL --}}
        <td class="classification-item {{ $clasificacion === 'incapacidad permanente parcial' ? 'classification-selected' : '' }}">
            Incapacidad<br>
            permanente<br>
            parcial
        </td>
        {{-- INCAPACIDAD PERMANENTE TOTAL --}}
        <td class="classification-item  {{ $clasificacion === 'incapacidad permanente total' ? 'classification-selected' : '' }}">
            Incapacidad<br>
            permanente<br>
            total
        </td>
        {{-- FATALIDAD --}}
        <td class="classification-item {{ $clasificacion === 'fatalidad' ? 'classification-selected' : '' }}">
            Fatalidad
        </td>
    </tr>
</table>
{{-- ================= OPERADOR / AFECTADO / SUPERVISOR ================= --}}
<table class="row-small">
    {{-- OPERADOR --}}
    <tr>
        <td width="20%" class="label">
            OPERADOR:
        </td>
        <td width="80%" class="value">
            {{ $alert->operador ?? 'N/A' }}
        </td>
    </tr>
    {{-- AFECTADO --}}
    <tr class="row-small">
        <td class="label">
            AFECTADO:
        </td>
        <td class="value value-blue">
            {{ $alert->afectado ?? 'N/A' }}
        </td>
    </tr>
    {{-- PUESTO --}}
    <tr class="row-small">
        <td class="label">
            PUESTO DEL AFECTADO
        </td>
        <td class="value value-blue">
            {{ $alert->puesto_afectado ?? 'N/A' }}
        </td>
    </tr>
    {{-- SUPERVISOR --}}
    <tr class="row-supervisor">
        <td class="label">
            SUPERVISOR / MONITOR EN<br>
            TURNO:
        </td>
        <td class="value">
            {{ $alert->supervisor_monitor ?? 'N/A' }}
        </td>
    </tr>
</table>
{{-- =====================   DESCRIPCIÓN  =============================== --}}
<table style="margin-top: 5px;">
    <tr>
        <td class="section-header">
            DESCRIPCIÓN DEL INCIDENTE/ACCIDENTE:
        </td>
    </tr>
    <tr>
        <td class="description">
            {{ $alert->descripcion ?? 'N/A' }}
        </td>
    </tr>
</table>
{{-- ========================   EVIDENCIA  ========================================= --}}
@if($evidencias->count() > 0)
<table style="margin-top: 5px;">
    <tr>
        <td class="section-header">
            EVIDENCIA (FOTOGRAFICA, MAPA, BOSQUEJOS)
        </td>
    </tr>
    <tr>
        <td class="evidence-box">
            <table class="evidence-table">
                <tr>
                    @for($i = 0; $i < 3; $i++)
                        <td class="evidence-cell">
                            @if(isset($evidencias[$i]) && !empty($evidencias[$i]->pdf_image))
                                <img src="{{ $evidencias[$i]->pdf_image }}" class="evidence-image">
                            @endif
                        </td>
                    @endfor
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif
{{-- ======================  ANEXOS ================================= --}}
<table style="margin-top: 5px;">
    {{-- TÍTULO --}}
    <tr>
        <td class="section-header">
            ANEXOS
        </td>
    </tr>
    {{-- DESCRIPCIÓN --}}
    <tr>
        <td class="annex-description">
            {{ $alert->anexos ?? '' }}
        </td>
    </tr>
    {{-- IMÁGENES --}}
    @if($anexosImagenes->count() > 0)
        <tr>
            <td class="annex-images-box">
                <table class="annex-table">
                    <tr>
                        @for($i = 0; $i < 2; $i++)
                            <td class="annex-cell">
                                @if(isset($anexosImagenes[$i]) && !empty($anexosImagenes[$i]->pdf_image))
                                    <img src="{{ $anexosImagenes[$i]->pdf_image }}" class="annex-image">
                                @endif
                            </td>
                        @endfor
                    </tr>
                </table>
            </td>
        </tr>
    @endif
</table>
{{-- ===================  WEBFLEET ==================================== --}}
<table style="margin-top: 5px;">
    <tr>
        <td class="webfleet-box">
            @if($webfleet->count() > 0)
                <div style="color:#ef6f3f; font-weight:bold; font-size:8px; margin-bottom:3px;">
                    Webfleet
                </div>
                @foreach($webfleet->take(1) as $imagen)
                    @if(!empty($imagen->pdf_image))
                        <img src="{{ $imagen->pdf_image }}" class="webfleet-image">
                    @endif
                @endforeach
            @endif
        </td>
    </tr>
</table>
{{-- =================  ACCIONES PARA EVITAR REPETICIÓN  ======================= --}}
<table style="margin-top: 5px;">
    <tr>
        <td class="section-header">
            Acciones para evitar su repetición
        </td>
    </tr>
    <tr>
        <td class="actions-box">
            {{ $alert->acciones_repeticion ?? '' }}
        </td>
    </tr>
</table>
{{-- ===================   FIRMA  ================================ --}}
<table style="margin-top: 3px;">
    <tr>
        <td class="signature-area">
            {{-- Si posteriormente tienes una firma en imagen, aquí se puede colocar automáticamente. --}}
            <div style="height: 40px;"></div>
            <div class="signature-name">
                {{ $alert->reportado_por ?? '' }}
            </div>
        </td>
    </tr>
    <tr>
        <td class="signature-label">
            Nombre y firma de quien reporta
        </td>
    </tr>
</table>

</body>

</html>