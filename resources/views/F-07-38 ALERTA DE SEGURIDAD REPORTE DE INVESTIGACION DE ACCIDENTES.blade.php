<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 18px 18px 22px 18px; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #333; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        td, th { border: 1px solid #777; padding: 4px 5px; vertical-align: middle; }
        .no-border { border: none !important; }
        .blue { background: #24577e; color: #fff; }
        /* IDENTIFICACIÓN: FORMATO CON LÍNEAS */
        .identificacion { width: 100%; border-collapse: collapse; margin-top: 8px; table-layout: fixed; }
        .identificacion td { border: none; padding: 1px 4px 0px 4px; vertical-align: bottom; font-size: 9px; }
        .identificacion .id-label { color: #0068b5; font-weight: bold; text-transform: uppercase; text-align: right; white-space: nowrap; }
        .identificacion .id-value { border-bottom: 1px solid #555; padding-left: 6px; color: #333; height: 22px; }
        .identificacion .id-label-inline { color: #0068b5; font-weight: bold; text-transform: uppercase; white-space: nowrap; text-align: right; }
        .label { background: #24577e; color: #fff; font-weight: bold; text-align: right; text-transform: uppercase; width: 20%; font-size: 8px; }
        .value { font-size: 9px; color: #333; }
        .header { border: 2px solid #777; height: 65px; }
        .header-logo { width: 20%; text-align: center; }
        .header-logo img { max-width: 145px; max-height: 55px; }
        .header-content { padding: 0; }
        .company-name { color: #0068b5; text-align: center; font-size: 12px; font-weight: bold; padding: 5px; }
        .document-title { text-align: center; font-size: 9px; font-weight: bold; padding: 3px; }
        .orange-bar { background: #efad83; color: #fff; text-align: center; font-size: 7px; padding: 4px 2px; white-space: nowrap; }
        .folio { text-align: right; color: #0068b5; font-weight: bold; font-size: 9px; padding: 4px 2px; }
        .general { margin-top: 4px; }
        .general td { min-height: 22px; }
        .blue-label { color: #0068b5; font-weight: bold; text-align: right; text-transform: uppercase; font-size: 8px; }
        .classification td { height: 57px; text-align: center; font-size: 9px; line-height: 10px; padding: 3px; }
        .classification-title { background: #24577e; color: #fff; font-weight: bold; text-align: right !important; }
        .selected { background: #f6d36b; font-weight: bold; }
        .selected-unit { display: block; color: #0068b5; margin-top: 4px; font-weight: bold; }
        /* Tablas consecutivas */
        .section-table { width: 100%; border-collapse: collapse; margin: 0 !important; padding: 0; }
        .section-table td { padding: 3px 5px; vertical-align: middle; }
        /* Etiquetas azules con texto blanco */
        .section-table .label { background: #24577e; color: #fff !important; font-weight: bold; text-align: right; text-transform: uppercase; font-size: 8px; }
        /* Clasificación: misma altura y apariencia que las etiquetas */
        .section-table .classification-title { background: #24577e; color: #fff !important; font-weight: bold; text-align: right; text-transform: uppercase; font-size: 8px; padding: 3px 5px; vertical-align: middle; white-space: nowrap; }
        /* Mantener las tablas pegadas */
        /* ================= EVIDENCIAS Y REPORTES ================= */
        .evidence-section { width:100%; border-collapse:collapse; margin-top:5px; page-break-inside:avoid; }
        .evidence-section td { border:1px solid #777; padding:2px 4px; vertical-align:middle; }
        .evidence-title { background:#24577e; color:#fff; font-weight:bold; font-size:8px; padding:3px 4px !important; text-transform:uppercase; }
        .evidence-photos { text-align:center; padding:3px !important; height:180px; }
        .evidence-photos img { display:inline-block; max-height:220px; max-width:98%; width:auto; height:auto; vertical-align:middle; margin:0 2px; }
        .camera-cell { width:50%; height:190px; text-align:center; padding:3px !important; }
        .camera-cell img { max-width:100%; max-height:185px; width:auto; height:auto; }
        .readi-cell { height:75px; text-align:center; vertical-align:middle; font-weight:bold; font-size:9px; }
        .webfleet-report-cell { height:175px; text-align:left; vertical-align:top !important; padding:5px !important; }
        .webfleet-report-image { max-width:220px; max-height:160px; width:auto; height:auto; display:block; }
        .webfleet-audio { font-size:8px; padding:3px !important; text-transform:uppercase; }

        /* ================= OTRA INFORMACIÓN ================= */
        .other-info td { font-size:8px; padding:3px 4px; height:17px; line-height:10px; }

        /* ================= TABLAS DE ACCIONES ================= */
        .action-table { width:100%; border-collapse:collapse; table-layout:fixed; margin-top:6px; page-break-inside:avoid; }
        .action-table td, .action-table th { border:1px solid #777; padding:2px 4px; font-size:8px; line-height:11px; vertical-align:middle; }
        .action-table .action-title { background:#24577e; color:#fff; font-size:8px; font-weight:bold; text-align:center; padding:4px 3px; }
        .action-table th { background:#fff; color:#333; font-weight:normal; text-align:center; }
        .action-number { text-align:center; width:6%; }
        .action-description { text-align:left; }
        .action-date { text-align:center; }
        .action-responsible { text-align:center; }
        .section-table + .section-table { margin-top: 0 !important; }
        .section { margin-top: 6px; page-break-inside: avoid; }
        .section-title { background: #24577e; color: #fff; font-weight: bold; font-size: 9px; padding: 4px 5px; text-transform: uppercase; }
        .description { min-height: 125px; vertical-align: top; padding: 8px 7px; font-size: 9px; line-height: 12px; white-space: pre-line; }
        .analysis-table { width: 100%; border-collapse: collapse; border: none !important; margin: 0; }
        .analysis-table tr, .analysis-table td { border: none !important; }
        .analysis-cell { width: 33.33%; vertical-align: top; padding: 4px 3px; }
        .analysis-title { color: #24577e; font-style: italic; font-weight: bold; text-transform: uppercase; text-decoration: underline; font-size: 9px; min-height: 16px; margin-bottom: 1px; }
        .analysis-item { padding: 1px 0; margin: 0; font-size: 9px; line-height: 9px; text-decoration: underline; min-height: 0; }
        .text-box-analisis { min-height: 20px; vertical-align: top; white-space: pre-line; line-height: 12px; }
        .text-box { min-height: 34px; padding: 6px; vertical-align: top; white-space: pre-line; line-height: 12px; }
        .tree-box { min-height: 100px; text-align: center; padding: 8px; vertical-align: middle; }
        .tree-image { max-width: 95%; max-height: 500px; }
        .evidence-cell { width: 50%; height: 175px; text-align: center; border: none; padding: 4px; }
        .evidence-image { max-width: 98%; max-height: 165px; }
        .signature-cell { height: 70px; text-align: center; vertical-align: bottom; }
        .signature-image { max-width: 130px; max-height: 38px; }
        .signature-name { font-weight: bold; font-size: 8px; border-top: 1px solid #555; padding-top: 4px; }
        .signature-position { font-size: 7px; }
        .small { font-size: 8px; }
        .avoid-break { page-break-inside: avoid; }
        .muted { color: #777; }
        .subheading { font-weight: bold; font-size: 8px; }
        .analysis-table { page-break-inside: avoid; }
    </style>
</head>
<body>
@php
    $valor = function ($campo, $default = 'N/A') use ($investigation) {
        $dato = data_get($investigation, $campo);
        return ($dato === null || $dato === '') ? $default : $dato;
    };

    $fecha = function ($dato) {
        if (!$dato) return 'N/A';
        try {
            return \Carbon\Carbon::parse($dato)->locale('es')->translatedFormat('l, j \d\e F \d\e Y');
        } catch (\Throwable $e) {
            return $dato;
        }
    };

    $clasificacion = mb_strtolower(trim((string) $valor('clasificacion_actual', '')));
    $clasificaciones = [
        'daño a equipo',
        'incidente potencial',
        'sin incapacidad',
        'incapacidad temporal',
        'incapacidad permanente parcial',
        'incapacidad permanente total',
        'fatalidad',
    ];

    $camposAnalisis = [
        'agentes' => 'Agentes',
        'tipos_contacto' => 'Tipo de contacto',
        'actos_inseguros' => 'Actos inseguros',
        'error_humano' => 'Qué influye en error humano',
        'protecciones_factores' => 'Ausencia / fallas de protecciones / condiciones ambientales / factores organizacionales',
        'condiciones_directas' => 'Condiciones directas (barreras físicas / salvaguardas tecnológicas / ambiente de la tarea)',
        'factor_organizacional' => 'Factor organizacional',
        'tipo_lesion' => 'Tipo de lesión',
        'parte_cuerpo_afectada' => 'Parte del cuerpo afectada',
    ];

    $normalizarOpciones = function ($items) {
        if (is_string($items)) {
            $decoded = json_decode($items, true);
            $items = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }
        if (!is_array($items)) return [];
        return collect($items)->map(function ($item) {
            if (is_array($item)) {
                $v = $item['valor'] ?? 'N/A';
                if ($v === 'Otro' && !empty($item['otro'])) return $item['otro'];
                return $v ?: 'N/A';
            }
            return $item ?: 'N/A';
        })->values()->all();
    };

    $accionesInmediatas = data_get($investigation, 'acciones_inmediatas', []);
    $accionesRepeticion = data_get($investigation, 'acciones_repeticion', []);
    if (isset($investigation->actions)) {
        $accionesInmediatas = $investigation->actions->where('tipo', 'inmediata')->values();
        $accionesRepeticion = $investigation->actions->where('tipo', 'repeticion')->values();
    }

    $equipo = $investigation->team ?? $investigation->equipoInvestigacion ?? collect();
    $imagenes = $imagenes ?? collect();
    $imagenArbol = $imagenes->firstWhere('tipo', 'arbol_causas');
    $evidencias = $imagenes->where('tipo', 'evidencia')->sortBy('orden')->values();
    $webfleet = $imagenes->where('tipo', 'webfleet')->sortBy('orden')->values();
    $reportes = $imagenes->where('tipo', 'reporte_webfleet')->sortBy('orden')->values();
    $logoImage = $logoImage ?? null;

    $renderImagenes = function ($items) {
        return $items;
    };
@endphp

{{-- ENCABEZADO --}}
<table class="header">
    <tr>
        <td class="header-logo">
            @if(!empty($logoImage))
                <img src="{{ $logoImage }}">
            @endif
        </td>
        <td class="header-content">
            <div class="company-name">TRAMUSA CARRIER S.A. DE C.V.</div>
            <div class="document-title">ALERTA DE SEGURIDAD y/o REPORTE DE INVESTIGACIÓN DE ACCIDENTES ENFERMEDADES</div>
            <div class="orange-bar">ÁREA: SEGURIDAD E HIGIENE &nbsp; F-07-38 &nbsp; PERIODICIDAD: CUANDO SE PRESENTE &nbsp; RESGUARDO: 5 AÑOS &nbsp; REVISIÓN: MARZO 2021</div>
        </td>
    </tr>
</table>

{{-- IDENTIFICACIÓN LINEAS--}}
<table class="identificacion">
    <tr>
        <td class="id-label" width="40%"># INCIDENTE / ACCIDENTE / ENFERMEDAD:</td>
        <td class="id-value" width="60%">{{ $valor('folio') }}</td>
    </tr>
    <tr>
        <td class="id-label"> NOMBRE DE INCIDENTE / ACCIDENTE / ENFERMEDAD: </td>
        <td class="id-value">{{ $valor('nombre_incidente') }}</td>
    </tr>
    <tr>
        <td class="id-label">FECHA DE INVESTIGACIÓN:</td>
        <td class="id-value">{{ $fecha($valor('fecha_investigacion', null)) }}</td>
    </tr>
</table><br>

{{-- IDENTIFICACIÓN TABLA --}}
<table class="general section-table">
    <tr>
        <td class="label" width="20%">LUGAR:</td>
        <td colspan="3">{{ $valor('lugar') }}</td>
    </tr>
    <tr>
        <td class="label">FECHA:</td>
        <td width="34%">{{ $fecha($valor('fecha', null)) }}</td>
        <td class="label" width="12%">HORA:</td>
        <td>{{ $valor('hora') }}</td>
    </tr>
    <tr>
        <td class="label">DEPARTAMENTO:</td>
        <td colspan="3">{{ $valor('departamento') }}</td>
    </tr>
    <tr>
        <td class="label">EMPRESA CONTRATISTA:</td>
        <td colspan="3">{{ $valor('empresa_contratista') }}</td>
    </tr>
</table>
{{-- CLASIFICACIÓN --}}
<table class="classification section-table">
    <tr>
        <td class="classification-title" width="20%">CLASIFICACIÓN ACTUAL:</td>
        @foreach($clasificaciones as $opcion)
            @php $seleccionada = $clasificacion === $opcion; @endphp
            <td class="{{ $seleccionada ? 'selected' : '' }}">
                {{ ucfirst($opcion) }}
                @if($seleccionada && $opcion === 'daño a equipo')
                    <span class="selected-unit">{{ $valor('unidad') }}</span>
                @endif
            </td>
        @endforeach
    </tr>
</table>
{{-- PERSONAL --}}
<table class="general section-table">
    <tr>
        <td class="label" width="20%">TRABAJADOR / OPERADOR:</td>
        <td>{{ $valor('operador') }}</td>
    </tr>
    <tr>
        <td class="label">AFECTADO:</td>
        <td>{{ $valor('afectado') }}</td>
    </tr>
    <tr>
        <td class="label">PUESTO DEL AFECTADO:</td>
        <td>{{ $valor('puesto_afectado') }}</td>
    </tr>
    <tr>
        <td class="label">JEFE INMEDIATO / SUPERVISOR EN TURNO:</td>
        <td>{{ $valor('supervisor_monitor') }}</td>
    </tr>
    <tr>
        <td class="label">INVESTIGACIÓN SÍ/NO:</td>
        <td>{{ $valor('investigacion') ? 'SÍ' : 'NO' }}</td>
    </tr>
</table>

{{-- DESCRIPCIÓN --}}
<table class="section">
    <tr><td class="section-title">DESCRIPCIÓN DEL INCIDENTE/ACCIDENTE:</td></tr>
    <tr><td class="description">{{ $valor('descripcion', '') }}</td></tr>
</table><br>

{{-- ANÁLISIS DE CLASIFICACIÓN: tres columnas --}}
<table class="section analysis-table">
    @foreach(array_chunk($camposAnalisis, 3, true) as $grupo)
        <tr>
            @foreach($grupo as $campo => $titulo)
                @php
                    $opciones = $normalizarOpciones($valor($campo, []));
                    $opciones = collect($opciones)->filter(fn($item) => trim((string) $item) !== '')->values()->all();
                    $opciones = array_slice($opciones, 0, 4);
                    while (count($opciones) < 4) {
                        $opciones[] = 'N/A';
                    }
                @endphp
                <td class="analysis-cell">
                    <div class="analysis-title">{{ $titulo }}:</div>
                    @foreach($opciones as $opcion)
                        <div class="analysis-item">{{ $opcion }}</div>
                    @endforeach
                </td>
            @endforeach

            @for($i = count($grupo); $i < 3; $i++)
                <td class="analysis-cell"></td>
            @endfor
        </tr>
    @endforeach
</table>

{{-- ÁRBOL DE CAUSAS --}}
<table class="section">
    <tr><td class="section-title">ÁRBOL DE CAUSAS</td></tr>
    <tr>
        <td class="tree-box">
            @if($imagenArbol && !empty($imagenArbol['pdf_image']))
                <img
                    src="{{ $imagenArbol['pdf_image'] }}"
                    class="tree-image"
                >
            @elseif(!empty($valor('arbol_causas', '')))
                {{ $valor('arbol_causas', '') }}
            @else
                <span class="muted">Sin árbol de causas registrado.</span>
            @endif
        </td>
    </tr>
</table>

{{-- ANÁLISIS DE CAUSAS Y HALLAZGOS --}}
<table class="section">
    <tr><td class="section-title">NATURALEZA DEL INCIDENTE / ACCIDENTE:</td></tr>
    <tr><td><div class="text-box-analisis">{{ $valor('naturaleza_incidente') }}</div></td></tr>
</table>
<table class="section">
    <tr><td class="section-title">CUÁL FUE LA CAUSA RAÍZ DEL ACCIDENTE/INCIDENTE:</td></tr>
    <tr><td><div class="text-box-analisis">{{ $valor('causa_raiz') }}</div></td></tr>
</table>
<table class="section">
    <tr><td class="section-title">CÓMO SE PUDO HABER EVITADO EL ACCIDENTE/INCIDENTE:</td></tr>
    <tr><td><div class="text-box-analisis">{{ $valor('como_se_pudo_evitar') }}</div></td></tr>
</table>
<table class="section">
    <tr><td class="section-title">HALLAZGOS INICIALES:</td></tr>
    <tr><td><div class="text-box-analisis">{{ $valor('hallazgos_iniciales') }}</div></td></tr>
</table>

{{-- ================= EVIDENCIA FOTOGRÁFICA ================= --}}
<table class="evidence-section">
    <tr>
        <td class="evidence-title">EVIDENCIA (FOTOGRÁFICA, MAPA, BOSQUEJOS)</td>
    </tr>
    <tr>
        <td class="evidence-photos">
            @foreach($evidencias as $imagen)
                @if(!empty(data_get($imagen, 'pdf_image')))
                    <img src="{{ data_get($imagen, 'pdf_image') }}">
                @endif
            @endforeach
        </td>
    </tr>
</table>
{{-- ================= REPORTE CÁMARA ================= --}}
<table class="evidence-section">
    <tr>
        <td colspan="2" class="evidence-title">REPORTE CÁMARA:</td>
    </tr>
    <tr>
        @foreach($webfleet->take(2) as $imagen)
            <td class="camera-cell">
                @if(!empty(data_get($imagen, 'pdf_image')))
                    <img src="{{ data_get($imagen, 'pdf_image') }}">
                @endif
            </td>
        @endforeach

        @if($webfleet->count() < 2)
            <td class="camera-cell"></td>
        @endif
    </tr>
</table>
{{-- ================= REPORTE READI SCORE ================= --}}
<table class="evidence-section">
    <tr>
        <td class="evidence-title">REPORTE READI SCORE:</td>
    </tr>
    <tr>
        <td class="readi-cell">
            {{ $valor('reporte_readi_score') }}
        </td>
    </tr>
</table>
{{-- ================= REPORTE WEBFLEET ================= --}}
<table class="evidence-section">
    <tr>
        <td class="evidence-title">REPORTE WEBFLEET:</td>
    </tr>
    <tr>
        <td class="webfleet-report-cell">
            @if($reportes->count() > 0)
                @if(!empty(data_get($reportes->first(), 'pdf_image')))
                    <img
                        src="{{ data_get($reportes->first(), 'pdf_image') }}"
                        class="webfleet-report-image"
                    >
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <td class="webfleet-audio">
            
        </td>
    </tr>
</table>

{{-- OTRA INFOMACION --}}
<table class="section">
    <tr><td  class="section-title">OTRA INFORMACIÓN </td></tr>
    <tr><td>¿SE APLICARON PRIMEROS AUXILIOS?, NOMBRE DE QUIEN APLICO PRIMEROS AUXILIOS? <strong> {{ $valor('primeros_auxilios') }}, {{ $valor('nombre_aplico_primeros_auxilios') }}</strong></td></tr>
    <tr><td>¿EL TRABAJADOR PADECIA ALGUNA DISCAPACIDAD? <strong> {{ $valor('discapacidad') }} </strong> </td></tr>
    <tr><td>DAÑO EVALUADO POR EL ESPECIALISTA., <strong> {{ $valor('danio_evaluado') }} </strong> </td></tr>
    <tr><td>NOMBRE DEL ESPECIALISTA.  <strong> {{ $valor('especialista') }} </strong> </td></tr>
</table>

{{-- ================= ACCIONES INMEDIATAS ================= --}}
<table class="action-table">
    <tr>
        <td colspan="3" class="action-title">
            ACCIONES TOMADAS DE FORMA INMEDIATA
        </td>
    </tr>
    <tr>
        <th width="6%"></th>
        <th width="74%">ACCIONES TOMADAS DE FORMA INMEDIATA</th>
        <th width="20%">RESPONSABLE</th>
    </tr>

    @forelse($accionesInmediatas as $indice => $accion)
        <tr>
            <td class="action-number">{{ $indice + 1 }}</td>
            <td class="action-description">
                {{ data_get($accion, 'descripcion', '') }}
            </td>
            <td class="action-responsible">
                {{ data_get($accion, 'responsable', '') }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="3" class="action-description">
                Sin acciones inmediatas registradas.
            </td>
        </tr>
    @endforelse
</table>

{{-- ================= ACCIONES PARA EVITAR REPETICIÓN ================= --}}
<table class="action-table">
    <tr>
        <td colspan="4" class="action-title">
            ACCIONES A REALIZAR PARA EVITAR LA REPETICIÓN
        </td>
    </tr>
    <tr>
        <th width="6%"></th>
        <th width="46%">ACCIONES</th>
        <th width="18%">FECHA COMPROMISO</th>
        <th width="30%">RESPONSABLE</th>
    </tr>

    @forelse($accionesRepeticion as $indice => $accion)
        <tr>
            <td class="action-number">{{ $indice + 1 }}</td>
            <td class="action-description">
                {{ data_get($accion, 'descripcion', '') }}
            </td>
            <td class="action-date">
                {{ data_get($accion, 'fecha_compromiso') ?: 'Pendiente' }}
            </td>
            <td class="action-responsible">
                {{ data_get($accion, 'responsable', '') }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="action-description">
                Sin acciones registradas.
            </td>
        </tr>
    @endforelse
</table>


{{-- ACCIONES PARA EVITAR REPETICIÓN --}}
<table class="section action-table">
    <tr><td colspan="3" class="section-title">ACCIONES PARA EVITAR SU REPETICIÓN</td></tr>
    <tr><th width="55%">DESCRIPCIÓN</th><th width="30%">RESPONSABLE</th><th width="15%">FECHA COMPROMISO</th></tr>
    @forelse($accionesRepeticion as $accion)
        <tr><td>{{ data_get($accion, 'descripcion', '') }}</td><td>{{ data_get($accion, 'responsable', '') }}</td><td>{{ data_get($accion, 'fecha_compromiso', '') }}</td></tr>
    @empty
        <tr><td colspan="3">Sin acciones registradas.</td></tr>
    @endforelse
</table>



{{-- EQUIPO DE INVESTIGACIÓN --}}
<table class="section">
    <tr><td colspan="3" class="section-title">EQUIPO DE INVESTIGACIÓN</td></tr>
    <tr><th class="blue">PUESTO</th><th class="blue">NOMBRE</th><th class="blue">FIRMA</th></tr>
    @forelse($equipo as $integrante)
        <tr>
            <td>{{ data_get($integrante, 'puesto', '') }}</td>
            <td>{{ data_get($integrante, 'nombre', '') }}</td>
            <td class="signature-cell">
                @if(!empty(data_get($integrante, 'firma_pdf')))<img src="{{ data_get($integrante, 'firma_pdf') }}" class="signature-image">@endif
            </td>
        </tr>
    @empty
        <tr><td colspan="3">Sin integrantes registrados.</td></tr>
    @endforelse
</table>

{{-- FIRMAS DE CIERRE --}}
<table class="section">
    <tr>
        <td class="signature-cell"><div class="signature-name">SEGURIDAD E HIGIENE</div><div class="signature-position">Responsable de investigación</div></td>
        <td class="signature-cell"><div class="signature-name">SUPERVISOR</div><div class="signature-position">Área correspondiente</div></td>
        <td class="signature-cell"><div class="signature-name">AUTORIZACIÓN</div><div class="signature-position">Gerencia / Responsable</div></td>
    </tr>
</table>
</body>
</html>
