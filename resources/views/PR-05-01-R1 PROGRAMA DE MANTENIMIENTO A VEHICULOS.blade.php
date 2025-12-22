<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PROGRAMA DE MANTENIMIENTO A VEHICULOS</title>
    <style>
      body {
          font-family: "Arial Narrow", Arial, sans-serif;
          font-size: 10px;
          color: #000;
          margin: 0;
          padding: 0;
      }

      /* ===== HEADER ===== */
      .header-table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 8px;
      }

      .header-table td {
          border: 1px solid #000;
          padding: 6px;
          vertical-align: middle;
      }

      .logoImg {
          width: 100%;
          max-height: 55px;
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
          margin: 3px 0;
      }

      .subtitle {
          font-size: 9px;
          background: #F4B083;
          text-align: center;
          padding: 4px;
          font-weight: bold;
      }

      /* ===== FECHA ===== */
      .info-table {
          width: 35%;
          float: right;
          border-collapse: collapse;
          margin-bottom: 6px;
      }

      .info-table th,
      .info-table td {
          border: 1px solid #000;
          padding: 4px;
          text-align: center;
          font-size: 9px;
      }

      /* ===== TABLA PRINCIPAL ===== */
      table {
          width: 100%;
          border-collapse: collapse;
      }

      th, td {
          border: 1px solid #000;
          padding: 3px;
          text-align: center;
          vertical-align: middle;
          font-size: 9px;
          page-break-inside: avoid;
      }

      thead th {
          background: #1E4E79;
          color: #FFF;
          font-weight: bold;
          font-size: 9px;
      }

      /* Meses */
      .month-header th {
          background: #1E4E79;
          color: #FFF;
          font-size: 9px;
      }

      /* Unidad */
      .unit-header th {
          background: #1E4E79;
          color: #FFF;
          font-weight: bold;
          text-align: center;
          padding-left: 6px;
          font-size: 10px;
      }

      /* Actividad */
      .activity {
          text-align: left;
          padding-left: 4px;
      }

      .menu {
        background: #1E4E79;
        color: #FFF;
      }

      /* Colores meses */
      .month-gray {
          background: #F2F2F2;
      }

      .month-white {
          background: #FFFFFF;
      }

      

      /* Separador visual */
      .spacer {
          height: 6px;
      }

      .page-break {
          page-break-before: always;
      }

      .month-gray {
        background: #f8a268ff;
      }
      /* Semana actual */
      .current-week {
          background: #E66A00 !important; /* naranja más fuerte */
          color: #fff;
          font-weight: bold;
          border: 2px solid #000;
      }

      .month-white { background: #FFFFFF; }
  </style>

  </head>
  <body>
    <header>
      <table class="header-table">
          <tr>
              <td width="20%" align="center">
                  <img class="logoImg" src="{{ $logoImage }}">
              </td>
              <td width="80%">
                  <div class="company">TRAMUSA CARRIER S.A. DE C.V.</div>
                  <div class="title">PROGRAMA DE MANTENIMIENTO A VEHÍCULOS</div>
                  <div class="subtitle">
                      ÁREA: MANTENIMIENTO | PR-05-01-R1 |
                      PERIODICIDAD: ANUAL | RESGUARDO: ELECTRÓNICO |
                      REVISIÓN: FEBRERO 2023
                  </div>
              </td>
          </tr>
      </table>
  </header>

    <main>
      <!-- FECHA / SEMANA -->
      <table class="info-table">
          <tr>
              <th class="menu">Fecha</th>
              <th class="menu">Semana</th>
          </tr>
          <tr>
              <td>{{ $fecha }}</td>
              <td>{{ $currentWeek }}</td>
          </tr>
      </table>

      <div style="clear:both;"></div>

      @php
          /* Mapeo MES → SEMANAS → COLOR */
          $months = [
              ['name'=>'ENE','weeks'=>4,'class'=>'month-gray'],
              ['name'=>'FEB','weeks'=>4,'class'=>'month-white'],
              ['name'=>'MAR','weeks'=>5,'class'=>'month-gray'],
              ['name'=>'ABR','weeks'=>4,'class'=>'month-white'],
              ['name'=>'MAY','weeks'=>4,'class'=>'month-gray'],
              ['name'=>'JUN','weeks'=>5,'class'=>'month-white'],
              ['name'=>'JUL','weeks'=>4,'class'=>'month-gray'],
              ['name'=>'AGO','weeks'=>5,'class'=>'month-white'],
              ['name'=>'SEP','weeks'=>4,'class'=>'month-gray'],
              ['name'=>'OCT','weeks'=>5,'class'=>'month-white'],
              ['name'=>'NOV','weeks'=>4,'class'=>'month-gray'],
              ['name'=>'DIC','weeks'=>4,'class'=>'month-white'],
          ];

          $weekClasses = [];
          $week = 1;

          foreach ($months as $month) {
              for ($i = 0; $i < $month['weeks']; $i++) {
                  $weekClasses[$week] = $month['class'];
                  $week++;
              }
          }
      @endphp

      <!-- TABLA PRINCIPAL -->
      <table>
        </thead>
          <!-- MESES -->
          <tr class="month-header">
              <th colspan="4"></th>
              @foreach($months as $month)
                  <th colspan="{{ $month['weeks'] }}">{{ $month['name'] }}</th>
              @endforeach
          </tr>

          <!-- ENCABEZADO SEMANAS (SIN CEBRA) -->
          <tr>
              <th class="menu">Unidad</th>
              <th class="menu">Actividad</th>
              <th class="menu">Inicio</th>
              <th class="menu">Periodo</th>
              @for($i = 1; $i <= 52; $i++)
                <th class="{{ ($i == $currentWeek ? 'current-week ' : '') . ($weekClasses[$i] ?? '') }}">
                    {{ $i }}
                </th>
              @endfor
          </tr>
        </thead>
        <tbody>
          @php $prevUnit = null; @endphp

          @foreach($Activitys as $activity)

              @if($prevUnit !== $activity['no_economic'])
                  <tr class="unit-header">
                      <th colspan="56">Unidad: {{ $activity['no_economic'] }}</th>
                  </tr>
              @endif

              <!-- FILA DE ACTIVIDAD (AQUÍ SÍ VA LA CEBRA POR MES) -->
              <tr>
                  <td>{{ $activity['no_economic'] }}</td>
                  <td class="activity">{{ $activity['activity'] }}</td>
                  <td>{{ $activity['start'] }}</td>
                  <td>{{ $activity['periodicity'] }}</td>

                  @for($i = 1; $i <= 52; $i++)
                      <td class="{{ $weekClasses[$i] }}">
                          {{ in_array($i, $activity['dates']) ? 'X' : '' }}
                      </td>
                  @endfor
              </tr>

              @php $prevUnit = $activity['no_economic']; @endphp

          @endforeach
        </tbody>
      </table>
    </main>
  </body>
</html>