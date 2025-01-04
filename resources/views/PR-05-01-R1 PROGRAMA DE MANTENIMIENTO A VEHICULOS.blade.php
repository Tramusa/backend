<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PROGRAMA DE MANTENIMIENTO A VEHICULOS</title>
    <style>
      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }

      header {
        padding: 5px 0px;
      }

      .column-1 {
        width: 20%;
        float: left;
        text-align: center;
        border: 2.5px solid #D1D1D1;
      }

      .column-2 {
        width: 80%;
        float: left;
        border: 2.5px solid #D1D1D1;
      }

      .column-50 {
        width: 50%;
        float: left;
      }

      .row{
        clear: both;
        display: table;
        width: 100%;
      }

      .column-2-1 {
        width: 85%;
        float: left; 
      }

      .column-2-2 {
        width: 15%;
        float: left;
        border: 1px solid #000;
      }

      a {
        color: #5D6975;
        text-decoration: underline;
      }

      body {
        position: relative;
        width: 27cm; /* Ancho de la página en orientación horizontal */
        height: 19cm; /* Altura de la página en orientación horizontal */
        margin: auto;
        color: #001028;
        background: #FFFFFF;
        font-size: 11px;
        font-family: "Arial Narrow", Arial, sans-serif;
        transform-origin: left top; /* Punto de origen de la rotación */
      }

      #logo {
        flex: 3;
        text-align: left;       
      }     

      .logoImg{
        width: 100%;
        margin: 0 0 0 0;
      }    

      h2 {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #FFFFFF;
        font-size: 0.8em;
        line-height: 1.4em;
        font-weight: bold;
        text-align: center;
        margin: 0 0 0 0;
        background: #F4B083;
      }

      .blueTitle {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #0073B5;
        font-size: 1.2em;
        font-weight: bold;
        text-align: center;
        margin: 10px 0 10px 0;
      }

      .title {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #000000;
        font-size: 1.2em;
        line-height: 1.4em;
        font-weight: bold;
        text-align: center;
        margin: 0 0 0 0;
      }

      table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 15px;
      }

      table th, table td {
        page-break-inside: avoid;
        text-align: center;
        border: 1px solid #000;
      }

      .page-break {
        page-break-before: always;
      }

      table th {
        font-family: "Arial Narrow", Arial, sans-serif;
        padding: 0px 0px;
        color: #FFFFFF;
        font-size: 1.1em;
        white-space: nowrap;        
        font-weight: bold;
        background: #1E4E79;
      }

      .line {
        border-left: 20px solid #F26726;
      }
    </style>
  </head>
  <body>
    <header class="clearfix">
      <div class="column-1">
        <div id="logo">
          <img class="logoImg" src="{{ $logoImage }}">
        </div>
      </div>
      <div class="column-2">
        <p class="blueTitle">TRAMUSA CARRIER S.A. DE C.V.</p>
        <p class="title">PROGRAMA DE MANTENIMIENTO A VEHICULOS</p>
        <h2>ÁREA: MANTENIMIENTO PR-05-01-R1  PERIODICIDAD: ANUAL RESGUARDO: INDEFINIDO/ELECTRONICO REVISIÓN: FEBRERO 2023</h2>
      </div>      
    </header>
    <main>
        <div class="row" style="width: 94%; float: right;">  
            <table style="width: 35%;">
                <tr>
                    <th>Fecha</th>
                    <th>Semana</th>
                </tr>
                <tr>
                    <td>{{ $fecha }}</td>
                    <td>{{ $currentWeek }}</td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>             
        <table style="width: 101%;">
          <tr>
              <th colspan="4"></th>
              <th colspan="52">Semana</th>
          </tr>
          <tr>
              <th>Unidad</th>
              <th>Actividad</th>
              <th>Inicio</th>
              <th>Periodo</th>
              @for($i = 1; $i <= 52; $i++)
                  <th>{{$i}}</th>
              @endfor
          </tr>

          @php
              $previousNoEconomic = ''; // Variable para almacenar el valor previo de no_economic
          @endphp

          @foreach($Activitys as $activity)
              @if($previousNoEconomic != $activity['no_economic']) 
                  <!-- Si el valor de no_economic cambia, agregar fila azul como separador -->
                  <tr style="background-color: #007BFF; color: white;">
                      <th colspan="56">
                          Unidad: {{ $activity['no_economic'] }}
                      </th>
                  </tr>
              @endif

              <!-- Fila con los datos de la actividad -->
              <tr>                  
                  <td>{{ $activity['no_economic'] ?? ' ' }}</td>
                  <td style="text-align: left;">{{ $activity['activity'] ?? ' ' }}</td>
                  <td>{{ $activity['start'] ?? ' ' }}</td>
                  <td>{{ $activity['periodicity'] ?? ' ' }}</td>
                  @for($i = 1; $i <= 52; $i++)
                      <td>
                          {{ in_array($i, $activity['dates']) ? 'X' : '' }}
                      </td>
                  @endfor
              </tr> 

              @php
                  $previousNoEconomic = $activity['no_economic']; // Actualiza el valor de no_economic
              @endphp
          @endforeach
      </table>  
    </main>
  </body>
</html>