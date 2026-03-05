<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>DISPONIBILIDAD DE UNIDADES</title>

<style>
body {
    width: 19cm;
    height: 29.7cm;
    margin: 0 auto;
    font-size: 11px;
    font-family: "Arial Narrow", Arial, sans-serif;
    color: #000;
}

/* ===== HEADER ESTILO ORDEN DE SERVICIO ===== */

.header-box {
    width: 100%;
    border: 3px solid #BFBFBF;
    display: table;
    margin-bottom: 20px; /* ← ESPACIO nuevo */
}

.header-row {
    display: table-row;
}

.header-logo {
    width: 22%;
    display: table-cell;
    border-right: 3px solid #BFBFBF;
    text-align: center;
    vertical-align: middle;
    padding: 10px;
}

.header-logo img {
    width: 100%;
}

.header-title {
    width: 78%;
    display: table-cell;
    text-align: center;
    vertical-align: middle;
    padding: 10px 5px;
}

.company-name {
    color: #0073B5;
    font-weight: bold;
    font-size: 16px;
}

.document-title {
    font-weight: bold;
    font-size: 14px;
    margin-top: 4px;
}

.header-bar {
    background: #F4B083;
    color: #fff;
    font-size: 11px;
    padding: 6px;
    text-align: center;
}

/* ================= TABLE ================= */

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #1E4E79;
    color: #fff;
    font-size: 11px;
    padding: 6px;
    border: 1px solid #000;
    text-align: center;
}

td {
    border: 1px solid #000;
    padding: 3px 4px;
    text-align: center;
    font-size: 11px;
}

/* Pastilla verde disponibilidad */
.badge-success {
    background: #28a745;
    color: #fff;
    padding: 3px 8px;
    border-radius: 5px;
    font-weight: bold;
    display: inline-block;
    min-width: 60px;
}
.badge {
    color: #fff;
    padding: 3px 8px;
    border-radius: 5px;
    font-weight: bold;
    display: inline-block;
    min-width: 60px;
}

/* Verde ≥ 87 */
.badge-green {
    background: #28a745;
}

/* Amarillo 82 - 86 */
.badge-yellow {
    background: #ffc107;
    color: #000;
}

/* Rojo ≤ 81 */
.badge-red {
    background: #dc3545;
}

.footer {
    margin-top: 15px;
    font-size: 10px;
    text-align: right;
}
</style>
</head>

<body>

<div class="header-box">
  
    @php
        $meses = [ 1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE',
        ];

        if (strtolower($month) === 'anual') {
            $periodoTexto = 'ANUAL';
        } elseif (is_numeric($month) && isset($meses[(int)$month])) {
            $periodoTexto = $meses[(int)$month];
        } else {
            $periodoTexto = strtoupper($month);
        }
    @endphp
    <div class="header-row">

        <div class="header-logo">
            <img src="{{ $logoImage }}">
        </div>

        <div class="header-title">
            <div class="company-name">
                TRAMUSA CARRIER S.A. DE C.V.
            </div>

            <div class="document-title">
                DISPONIBILIDAD DE UNIDADES
            </div>
            <div class="header-bar">
              <strong>
                PERIODO: {{ $periodoTexto }} | 
                LOGÍSTICA: {{ strtoupper($logistic) }} |
                FECHA: {{ $fecha }}
              </strong>
            </div>
        </div>
    </div>    
</div>

<main>

  <table>
      <thead>
          <tr>
              <th>N°</th>
              <th>Unidad</th>
              <th>Horas Prog.</th>
              <th>Horas Mtto</th>
              <th>Horas Espera</th>
              <th>% Disponibilidad</th>
          </tr>
      </thead>

      <tbody>
          @foreach ($units as $index => $unit)

              @php
                  $disponibilidad = 0;

                  if ($unit['horas_programadas'] > 0) {
                      $disponibilidad = (
                          ($unit['horas_programadas']
                          - $unit['horas_mtto']
                          + $unit['horas_espera'])
                          / $unit['horas_programadas']
                      ) * 100;
                  }
                  $badgeClass = 'badge-red';

                  if ($disponibilidad >= 87) {
                      $badgeClass = 'badge-green';
                  } elseif ($disponibilidad >= 82 && $disponibilidad <= 86) {
                      $badgeClass = 'badge-yellow';
                  }
              @endphp

              <tr>
                  <td>{{ $index + 1 }}</td>

                  <td>
                      {{ $unit['no_economico'] }}
                  </td>

                  <td>
                      {{ number_format($unit['horas_programadas'], 0) }}
                  </td>

                  <td>
                      {{ number_format($unit['horas_mtto'], 0) }}
                  </td>

                  <td>
                      {{ number_format($unit['horas_espera'], 0) }}
                  </td>

                  <td>
                      <span class="badge {{ $badgeClass }}">
                          {{ number_format($disponibilidad, 2) }}%
                      </span>
                  </td>
              </tr>

          @endforeach
      </tbody>
  </table>

</main>

<div class="footer">
    Total de unidades: {{ count($units) }}
</div>

</body>
</html>