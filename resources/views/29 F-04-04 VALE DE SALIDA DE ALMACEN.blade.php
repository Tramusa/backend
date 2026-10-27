<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-04-04 VALE DE SALIDA DEL ALMACEN</title>
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
        width: 30%;
        float: left;
        text-align: center;
        border: 2.5px solid #000;
      }

      .column-2 {
        width: 70%;
        float: left;
        border: 2.5px solid #000;
      }

      .row{
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
        margin: 2px 0 2px 0;
      }

      #ruta {
        flex: 3;   
        text-align: center;      
      }     

      h1 {
        font-family: "Arial Narrow", Arial, sans-serif;
        border-top: 2.5px solid  #D1D1D1;
        border-bottom: 2.5px solid  #D1D1D1;
        border-right: 2.5px solid  #D1D1D1;
        border-left: 2.5px solid  #D1D1D1;
        color: #FFFFFF;
        font-size: 1.2em;
        line-height: 1.4em;
        font-weight: bold;
        text-align: center;
        margin: 0 0 20px 0;
        background: #1E4E79;
      }

      h2 {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #FFFFFF;
        font-size: 1.1em;
        line-height: 1.6em;
        font-weight: bold;
        text-align: center;
        margin: 0 0 0 0;
        background: #F4B083;
      }

      .blueTitle {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #0073B5;
        font-size: 1.4em;
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
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 10px;
      }

      table th {
        font-family: "Arial Narrow", Arial, sans-serif;
        text-align: center;
        padding: 4px 5px;
        color: #FFFFFF;
        font-size: 1.2em;
        border: 1px solid #000;
        white-space: nowrap;        
        font-weight: bold;
        background: #1E4E79;
      }

      .line {
        border-left: 20px solid #F26726;
      }

      table td {
        padding: 2px 5px;
        border: 1px solid #000;
      }

      .remision-table .total-row td {
          background: #E8EDF2;
          font-weight: bold;
          border-top: 2px solid #000;
          padding: 8px 5px;
      }

      .remision-table .total-label {
          text-align: right;
      }

      .remision-table .total-amount {
          font-size: 12px;
          white-space: nowrap;
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
        <p class="title">VALE DE SALIDA DEL ALMACEN</p>
        <h2>  ÁREA:ADMINISTRACIÓN      F-04-04     REVISIÓN: ENERO DE 2018  </h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1"></div>
        <table class="column-2-2">
          <tr><th>FOLIO</th></tr>
          <tr><td class="blueTitle">{{ $DataOut['id']  ?? ' ' }}</td></tr>
        </table><br>
      </div>     
      <div style="clear: both;"></div>      
      <div class="row">
          <table class="remision-table">
              <thead>
                  <tr>
                      <th>CANTIDAD</th>
                      <th>UNIDAD DE MEDIDA</th>
                      <th>DESCRIPCIÓN</th>
                      <th>PRECIO</th>
                      <th>IMPORTE</th>
                  </tr>
              </thead>

              <tbody>
                  @php
                      $minRows = 20;
                      $filledRows = count($DataDetails);
                      $totalImporte = 0;
                  @endphp

                  {{-- PRODUCTOS --}}
                  @foreach($DataDetails as $detail)
                      @php
                          $cantidad = (float) ($detail->quality ?? 0);
                          $precio = (float) ($detail->product->price ?? 0);
                          $importe = $cantidad * $precio;

                          $totalImporte += $importe;
                      @endphp

                      <tr>
                          <td class="text-center">
                              {{ $detail->quality ?? 0 }}
                          </td>

                          <td class="text-center">
                              {{ $detail->product->unit_measure ?? '' }}
                          </td>

                          <td>
                              {{ $detail->product->name ?? '' }}
                          </td>

                          <td class="text-right">
                              ${{ number_format($precio, 2) }}
                          </td>

                          <td class="text-right">
                              ${{ number_format($importe, 2) }}
                          </td>
                      </tr>
                  @endforeach

                  {{-- FILAS VACÍAS --}}
                  @for($i = 0; $i < max(0, $minRows - $filledRows); $i++)
                      <tr class="empty-row">
                          <td>&nbsp;</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                      </tr>
                  @endfor

                  {{-- TOTAL GENERAL --}}
                  <tr class="total-row">
                      <td colspan="4" class="total-label">
                          TOTAL
                      </td>
                      <td class="text-right total-amount">
                          ${{ number_format($totalImporte, 2) }}
                      </td>
                  </tr>
              </tbody>
          </table>
      </div>
      <div style="clear: both;"></div>
      <div class="row">        
        <table style="width: 100%; text-align: center;">
          <tr>
            <th>OBSERVACIONES</th>
          </tr>
          <tr>
            <td style="height: 70px; text-align: left;">
                {{ $DataOut->observations ?? ' ' }}
            </td>
          </tr>
        </table>
      </div>
      <div style="clear: both;"></div>
      <div class="row"> 
        <table style="width: 100%; text-align: center;">            
            <tr>              
              <td>
                  FIRMA Y FECHA
                  <br><br><br><br><br>
                  _________________________________________
                  <br><br>

                  {{ $DataOut->receiver ?? ' ' }}
              </td>

              <td>
                  FIRMA Y FECHA
                  <br><br><br><br><br>
                  _________________________________________
                  <br><br>

                  {{ 
                      ($DataOut->user->name ?? '') . ' ' .
                      ($DataOut->user->a_paterno ?? '') . ' ' .
                      ($DataOut->user->a_materno ?? '')
                  }}
              </td>
          </tr>

          <tr>
              <th>RECIBE</th>
              <th>ENTREGÓ</th>
          </tr>
        </table>
      </div>  
    </main>
  </body>
</html>
