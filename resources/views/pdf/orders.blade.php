<!DOCTYPE html>
<html>
<head>
    <title>BITACORA DE MANTENIMIENTO</title>
    <style>
    body {
        font-family: "Arial Narrow", Arial, sans-serif;
        font-size: 10px;
        color: #000;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 8px;
    }

    thead th {
        background: #1E4E79;
        color: #FFF;
        font-size: 10px;
        padding: 6px 4px;
        border: 1px solid #000;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    tbody td {
        border: 1px solid #000;
        padding: 5px 4px;
        vertical-align: top;
    }

    /* Zebra */
    tbody tr:nth-child(even) {
        background: #F2F2F2;
    }

    /* Alineaciones */
    .center { text-align: center; }
    .left   { text-align: left; }
    .right  { text-align: right; }

    /* Column widths */
    .col-id       { width: 4%; }
    .col-date     { width: 10%; }
    .col-unit     { width: 7%; }
    .col-odo      { width: 6%; }
    .col-op       { width: 14%; }
    .col-type     { width: 8%; }
    .col-falla    { width: 15%; }
    .col-desc     { width: 18%; }
    .col-time     { width: 6%; }
    .col-cost     { width: 6%; }

    /* Header */
    header {
        margin-bottom: 6px;
    }

    .header-table {
        width: 100%;
        border-collapse: collapse;
    }

    .header-table td {
        border: 1px solid #000;
        padding: 6px;
        vertical-align: middle;
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
        margin-top: 4px;
    }

    .subtitle {
        font-size: 9px;
        background: #F4B083;
        color: #000;
        padding: 4px;
        text-align: center;
        font-weight: bold;
    }
</style>

</head>

  <header>
    <table class="header-table">
        <tr>
            <td width="20%" align="center">
                <img class="logoImg" src="{{ $logoImage }}">
            </td>
            <td width="80%">
                <div class="company">TRAMUSA CARRIER S.A. DE C.V.</div>
                <div class="title">BITÁCORA DE MANTENIMIENTO</div>
                <div class="subtitle">
                    ÁREA: MANTENIMIENTO &nbsp; | &nbsp;
                    PERIODICIDAD: — &nbsp; | &nbsp;
                    RESGUARDO: ELECTRÓNICO &nbsp; | &nbsp;
                    REVISIÓN: FEBRERO 2025
                </div>
            </td>
        </tr>
<body>
    </table>
  </header>

    <table>
    <thead>
        <tr>
            <th class="col-id">N°</th>
            <th class="col-date">Fecha / Semana</th>
            <th class="col-unit">Unidad</th>
            <th class="col-odo">Odómetro</th>
            <th class="col-op">Operador</th>
            <th class="col-type">Tipo</th>
            <th class="col-falla">Falla</th>
            <th class="col-desc">Descripción</th>
            <th class="col-time">Tiempo</th>
            <th class="col-cost">Costo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td class="center">{{ $order->id }}</td>
                <td class="center">
                    {{ $order->date_attended }}<br>
                    <strong>Semana {{ $order->week ?? '' }}</strong>
                </td>
                <td class="center">{{ $order->no_economic ?? 'N/A' }}</td>
                <td class="center">{{ $order->odometro ?? '—' }}</td>
                <td class="left">
                    {{ $order->name }} {{ $order->a_paterno }} {{ $order->a_materno }}
                </td>
                <td class="center">{{ $order->type_mtto }}</td>
                <td class="left">{{ $order->fallas[0] ?? '' }}</td>
                <td class="left">{{ $order->repair }}</td>
                <td class="center">{{ $order->time }} min</td>
                <td class="right">
                    ${{ number_format($order->total_mano + $order->total_parts, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
