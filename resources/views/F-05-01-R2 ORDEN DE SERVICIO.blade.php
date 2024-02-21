<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-05-01-R2 ORDEN DE SERVICIO</title>
    <style>
      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }

      header {
        padding: 10px 0px;
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
        display: table;
        width: 100%;
        clear: both;
      }

      .column-2-1 {
        width: 50%;
        float: left; 
      }

      .column-2-2 {
        width: 50%;
        float: left;
        border: 1px solid #000;
      }
      
      a {
        color: #5D6975;
        text-decoration: underline;
      }

      body {
        position: relative;
        width: 19cm;  
        height: 29.7cm; 
        margin: 0 auto; 
        color: #001028;
        background: #FFFFFF; 
        font-size: 11px; 
        font-family: "Arial Narrow", Arial, sans-serif;
      }
      
      #logo {
        flex: 3;
        text-align: left;       
      }     

      .logoImg{
        width: 100%;
        margin: 10px 0 10px 0;
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
        margin-bottom: 10px;
      }

      table th {
        font-family: "Arial Narrow", Arial, sans-serif;
        text-align: center;
        padding: 0px 5px;
        color: #FFFFFF;
        font-size: 1.1em;
        border: 1px solid #000;      
        font-weight: bold;
        background: #1E4E79;
      }

      table td {
        padding: 0px 5px;
        border: 1px solid #000;
      }     

      table.column-2-2 td {
        border: 1px solid;
        padding: 4px;
        text-align: left;
      }
      
      table.firmas td {
        border: 0px solid;
        padding: 4px;
        text-align: center;
      }

      table.column-2-1 th{
        border: 1px solid;
        padding: 3px;
      }  

      .signature {
        font-family: 'Courier New', monospace; /* Una fuente cursiva disponible en la mayoría de los sistemas */
        font-style: italic;
        font-weight: bold;
        font-size: 13px;
        color: #000080; /* Azul marino */
        letter-spacing: 0.5px; /* Espaciado ligero para emular la caligrafía */
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
        <p class="title">ORDEN DE SERVICIO</p>
        <h2>ÁREA: MANTENIMIENTO  PERIODICIDAD DE INSPECCIÓN: CUANDO SE PRESENTE  F-05-01/R2 REVISIÓN: SEPTIEMBRE 2018</h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1">
            <p class="title">ORDEN DE SERVICIO ASOCIADA A:</p>            
        </div>
        <table class="column-2-2">
            <tr>
                <th>NO. DE REQUISICIÓN</th>
                <th>FOLIO DE REVISIÓN DE <br> CONDICIONES FÍSICO-MECÁNICAS</th>
            </tr>
            <tr>
                <td></td>
                <td>{{ $fm }}</td>
            </tr>
        </table>
      </div>  
      <div class="row">
        <table style="width: 100%; border: 2px solid;">
            <tr>
                <td>MARCA: <br>{{ $unit->brand ?? 'N/A' }}</td>
                <td>NUMERO ECONOMICO: <br>{{ $unit->no_economic }}</td>
                <td>PLACAS: <br>{{ $unit->no_placas ?? 'N/A' }}</td>
                <td>FOLIO: <br>{{ $orderData->id}}</td>
            </tr>
            <tr>
                <td>MODELO: <br>{{ $unit->model ?? $unit->year }}</td>
                <td>N° MOTOR:<br>{{ $unit->no_motor ?? 'N/A' }}</td>
                <td>TIPO DE MANTENIMIENTO:<br> PREVENTIVO          CORRECTIVO </td>
                <td>KILOMETRAJE: <br>{{ $unit->odometro ?? ' ' }}</td>
            </tr>
            <tr>
                <td colspan="2">FECHA Y HORA DE ENTRADA: <br>{{ $orderData->date_in }}</td>
                <td colspan="2">FECHA Y HORA DE SALIDA: <br>{{ $orderData->date_attended }}</td>
            </tr>
        </table>
      </div>        
      <div class="row">
        <table style="width: 100%; border: 2px solid;">
            <tr>
                <td colspan="2">RESUMEN DE LA FALLA: <br><br>{!! $fallas !!}<br><br></td>
            </tr>
            <tr>
                <td colspan="2">RESUMEN DE LA REPARACIÓN: <br><br><pre>{{ $orderData->repair }}</pre><br><br></td>
            </tr>
            <tr>
                <td colspan="2">REFACCIONES EMPLEADAS: <br><br><pre>{{ $orderData->spare_parts }}</pre><br><br></td>
            </tr>
            <tr>
                <td>TOTAL EFECTIVO DE REFACCIONES: <br>${{ number_format($orderData->total_parts, 2) }}</td>
                <td>TOTAL EFECTIVO DE MANO DE OBRA: <br>${{ number_format($orderData->total_mano, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2">TOTAL EFECTIVO DEL SERVICIO: <br><br>${{ number_format($orderData->total_mano + $orderData->total_parts, 2) }}<br></td>
            </tr>
        </table>
      </div> 
      <div class="row"><br><br><br><br>
        <table class="firmas" style="width: 100%; border: 0px solid;">
            <tr>
              <td>
                <p class="signature">{{ $autorizo->name ?? ' ' }} {{ $autorizo->a_paterno ?? ' ' }} {{ $autorizo->a_materno ?? ' ' }}</p>
                ___________________________<br>
                {{ $autorizo->name ?? ' ' }} {{ $autorizo->a_paterno ?? ' ' }}<br>
                AUTORIZÓ
              </td>
              <td>
                <p class="signature">{{ $realizo->name ?? ' ' }} {{ $realizo->a_paterno ?? ' ' }} {{ $realizo->a_materno ?? ' ' }}</p>
                ___________________________<br>
                {{ $realizo->name ?? ' ' }} {{ $realizo->a_paterno ?? ' ' }}<br>
                REALIZÓ	
              </td>
              <td>
                <p class="signature">{{ $operator->name ?? ' ' }} {{ $operator->a_paterno ?? ' ' }} {{ $operator->a_materno ?? ' ' }}</p>
                ___________________________<br>
                {{ $operator->name ?? ' ' }} {{ $operator->a_paterno ?? ' ' }}<br>
                OPERADOR
              </td>
            </tr>
        </table>
      </div>        
    </main>
  </body>
</html>