<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>INSPECCIÓN DE NEUMÁTICOS</title>

<style>
body {
    font-family: Arial, sans-serif;
    font-size: 10px;
    width: 100%;
}

/* ================= HEADER ================= */
.header-table {
    width: 100%;
    border-collapse: collapse;
}

.header-table td {
    border: 1px solid #000;
    padding: 6px;
}

.logoImg {
    width: 100%;
    max-height: 60px;
}

.company {
    font-size: 14px;
    font-weight: bold;
    color: #0073B5;
    text-align: center;
}

.title {
    font-size: 12px;
    font-weight: bold;
    text-align: center;
}

.subtitle {
    font-size: 9px;
    background: #F4B083;
    padding: 4px;
    text-align: center;
    font-weight: bold;
}

/* ================= TABLE ================= */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.table th, .table td {
    border: 1px solid #000;
    padding: 2px;
    text-align: center;
}

.table th {
    background: #1E4E79;
    color: #fff;
    font-size: 9px;
}

.table td {
    font-size: 9px;
}

/* ================= IMAGES ================= */
.img-container {
    width: 100%;
    margin-top: 10px;
}

.img-box {
    width: 49%;
    display: inline-block;
}

.img-box img {
    width: 100%;
}

/* ================= INFO ================= */
.info-table td {
    text-align: left;
    padding: 4px;
}

</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<table class="header-table">
<tr>
    <td width="20%" align="center">
        <img class="logoImg" src="{{ $logo }}">
    </td>

    <td width="80%">
        <div class="company">TRAMUSA CARRIER S.A. DE C.V.</div>
        <div class="title">INSPECCIÓN DE NEUMÁTICOS</div>

        <div class="subtitle">
            ÁREA: LOGÍSTICA |
            PERIODICIDAD: SEMANAL |
            RESGUARDO: 3 AÑOS |
            REVISIÓN: MARZO 2022
        </div>
    </td>
</tr>
</table>

<!-- ================= DATOS ================= -->
<table class="table info-table">
    <tr>
        <td><b>Folio:</b> {{ $inspection->id ?? '' }}</td>
        <td><b>Unidad:</b> {{ $unit->no_economic ?? 'N/A' }}</td>
        <td><b>Fecha:</b> {{ $inspection->inspection_date }}</td>
        <td><b>Status:</b> {{ $inspection->status == 1 ? 'Finalizada' : 'Pendiente' }}</td>
        <td><b>Realizó:</b> {{ $inspection->user->name ?? '' }} {{ $inspection->user->a_paterno ?? '' }}</td>
    </tr>
</table>

<!-- ================= TABLA PRINCIPAL ================= -->
<table class="table">

<thead>
    <tr>
        <th colspan="5">GENERALES DEL NEUMÁTICO</th>
        <th colspan="3">MEDIDA CARA INTERNA MM</th>
        <th colspan="3">MEDIDA CENTRO MM</th>
        <th colspan="3">MEDIDA CARA EXTERNA MM</th>
        <th></th>
        <th rowspan="2">OBSERVACIONES</th>
    </tr>

    <tr>
        <th>DOT</th>
        <th>Marca</th>
        <th>Posición</th>
        <th>Quemado</th>
        <th>PSI</th>

        <th>M1</th><th>M2</th><th>M3</th>
        <th>M1</th><th>M2</th><th>M3</th>
        <th>M1</th><th>M2</th><th>M3</th>

        <th>Prom</th>
    </tr>
</thead>

<tbody>
@php
    $rows = $inspection->details;
    $minRows = 6;
@endphp

{{-- 🔥 FILAS REALES --}}
@foreach($rows as $d)
<tr>
    <td>{{ $d->tire->dot ?? '' }}</td>
    <td>{{ $d->tire->brand ?? '' }}</td>
    <td>{{ $d->tire->position ?? '' }}</td>
    <td>{{ $d->tire->burn_number ?? '' }}</td>

    <td>{{ $d->psi }}</td>

    <td>{{ $d->internal_1 }}</td>
    <td>{{ $d->internal_2 }}</td>
    <td>{{ $d->internal_3 }}</td>

    <td>{{ $d->center_1 }}</td>
    <td>{{ $d->center_2 }}</td>
    <td>{{ $d->center_3 }}</td>

    <td>{{ $d->external_1 }}</td>
    <td>{{ $d->external_2 }}</td>
    <td>{{ $d->external_3 }}</td>

    <td><b>{{ $d->average }}</b></td>

    <td>{{ $d->observations }}</td>
</tr>
@endforeach

{{-- 🔥 FILAS VACÍAS HASTA 6 --}}
@for($i = count($rows); $i < $minRows; $i++)
<tr>
    @for($j = 0; $j < 16; $j++) {{-- 👈 número de columnas --}}
        <td>&nbsp;</td>
    @endfor
</tr>
@endfor

</tbody>

</table>

<!-- ================= IMÁGENES ================= -->
@if(isset($img1) && isset($img2))
<table style="width:100%; margin-top:50px; border-collapse: collapse;">
    <tr>
        <td style="width:50%; padding:5px;">
            <img src="{{ $img1 }}" style="width:99%;">
        </td>

        <td style="width:50%; padding:5px;">
            <img src="{{ $img2 }}" style="width:97%;">
        </td>
    </tr>
</table>
@endif

</body>
</html>