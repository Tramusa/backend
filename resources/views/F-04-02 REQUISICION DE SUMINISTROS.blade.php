<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-04-02 REQUISICION DE SUMINISTROS</title>
    <style>
      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }

      header {
        padding: 0px 0px;
      }

      .column-1 {
        width: 25%;
        float: left;
        text-align: center;
      }

      .column-2 {
        width: 75%;
        float: left;
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
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
      }
      
      #logo {
        flex: 3;
        text-align: left;       
      }     

      .logoImg{
        width: 100%;
        margin: 5px 0 5px 0;
      }

      h2 {
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
        color: #FFFFFF;
        font-size: 1.2em;
        font-weight: bold;
        text-align: center;
        margin: 0px 0 0px 0;
        padding: 5px 5px;
        background: #f79118;
      }

      .title {
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
        color: #000000;
        font-size: 1.5em;
        font-weight: bold;
        text-align: center;
        margin: 0px 0 0px 0;
      }
      
      table {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 10px;
      }

      table th {
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
        text-align: center;
        padding: 0px 5px;
        color: #FFFFFF;
        font-size: 1.1em;
        border: 1px solid #000;      
        font-weight: bold;
        background: #1E4E79;
      }

      table td {
        font-size: 1.1em;
        padding: 5px 5px;
      }   

      .yellow-bg {
        background-color: #ffa121; /* Yellow background */
        color: #FFF; /* White text */
        font-weight: bold;
      }

      .blue-bg {
        background-color: #080784; /* Yellow background */
        color: #FFF; /* White text */
        font-weight: bold;
      }

      .bottom-border-only {
        border-bottom: 1px solid #000; /* Only bottom border */
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
      <div class="column-2"><br>
        <p class="title">REQUISICION DE SUMINISTROS</p>
        <h2>ÁREA: ADMINISTRACIÓN     .     F-04-02      .    REVISIÓN: ENERO 2018</h2>
      </div>      
    </header>
    <main> 
      <div class="row">
        <table style="width: 100%;"><br>
            <tr>
                <td class="yellow-bg" style="width: 15%;">FOLIO </td>
                <td class="bottom-border-only" style="width: 30%;">{{ $Data->id ?? '-' }}</td>
                <td class="yellow-bg" style="width: 15%;">FECHA </td>
                <td class="bottom-border-only" style="width: 40%;">{{ $fecha ?? '-' }}</td>
            </tr>
        </table>
      </div>   
      <div class="row">
        <table style="width: 100%;">
            <tr>
                <td class="yellow-bg">ÁREA SOLICITANTE </td>
                <td class="yellow-bg">NOMBRE SOLICITANTE </td>
                <td class="yellow-bg">UNIDAD O ÁREA DE APLICACIÓN </td>
            </tr>
            <tr>
                <td class="bottom-border-only">{{ $Data->work_areaInfo->name ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->collaboratorInfo->name ?? '-' }}</td>
                <td class="bottom-border-only">-</td>
            </tr>
        </table>
      </div>   
      <div class="row">
        <table style="width: 100%;">
            <tr>
                <td class="blue-bg">CÓDIGO CUENTA PADRE </td>
                <td class="blue-bg">NOMBRE CUENTA PADRE </td>
                <td class="blue-bg">CÓDIGO CUENTA TITULO </td>
                <td class="blue-bg">NOMBRE CUENTA TITULO </td>
            </tr>
            <tr>
                <td class="bottom-border-only">{{ $Data->parent_accountInfo->id ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->parent_accountInfo->name ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->title_accountInfo->id ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->title_accountInfo->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="blue-bg">CÓDIGO CUENTA SUBTITULO</td>
                <td class="blue-bg">NOMBRE CUENTA SUBTITULO</td>
                <td class="blue-bg">CÓDIGO CUENTA MAYOR</td>
                <td class="blue-bg">NOMBRE CUENTA MAYOR</td>
            </tr>
            <tr>
                <td class="bottom-border-only">{{ $Data->subtitle_accountInfo->id ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->subtitle_accountInfo->name ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->mayor_accountInfo->id ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->mayor_accountInfo->name ?? '-' }}</td>
            </tr>
        </table>
      </div>   
      <div class="row">
        <table style="width: 100%;"><br><br>
          <tr>
            <td class="blue-bg">CANTIDAD </td>
            <td class="blue-bg">CLAVE </td>
            <td class="blue-bg">DESCRIPCIÓN </td>
            <td class="blue-bg">UNIDAD MEDIDA </td>
            <td class="blue-bg">JUSTIFICACIÓN </td>
          </tr>
          @forelse ($detailsRequisitions as $detail)
            <tr>
                <td class="bottom-border-only">{{ $detail->cantidad ?? '-' }}</td>
                <td class="bottom-border-only">{{ $detail->id_product ?? '-' }}</td>
                <td class="bottom-border-only">{{ $detail->name ?? '-' }}</td>
                <td class="bottom-border-only">{{ $detail->unit_measure ?? '-' }}</td>
                <td class="bottom-border-only">{{ $detail->justific ?? '-' }}</td>
            </tr>
          @empty
            <tr>
                <td colspan="5" class="bottom-border-only">No hay detalles de requisición disponibles</td>
            </tr>
          @endforelse
        </table>
      </div>   
      <div class="row">
        <table style="width: 100%;"><br><br>
            <tr>
                <td class="yellow-bg" style="width: 20%;">OBSERVACIONES </td>
                <td class="bottom-border-only">{{ $Data->observations ?? '-' }}</td>
            </tr>
        </table>
      </div>  
      <div class="row">
        <table style="width: 100%; border: 1px solid #000;">            
            <tr>
                <td class="bottom-border-only" style="border: 1px solid #000; font-weight: bold; text-align: center;"><br><br><br>{{ $Data->collaboratorInfo->name ?? '-' }}<br>{{ $Data->work_areaInfo->name ?? '-' }}</td>
                <td class="bottom-border-only" style="border: 1px solid #000; font-weight: bold; text-align: center;"><br><br><br>{{ $Data->user_authorized->name ?? '-' }} {{ $Data->user_authorized->a_paterno ?? '-' }} {{ $Data->user_authorized->a_materno ?? '-' }}<br>{{ $Data->user_authorized->rol ?? '-' }}</td>
                <td class="bottom-border-only" style="border: 1px solid #000; font-weight: bold; text-align: center;"><br><br><br><br><br>-</td>
            </tr>
            <tr>
                <td class="blue-bg" style="width: 33%; text-align: center;">ÁREA SOLICITANTE </td>
                <td class="blue-bg" style="width: 33%; text-align: center;">ANALIZÓ </td>
                <td class="blue-bg" style="width: 34%; text-align: center;">SUFICIENCIA PRESUPUESTAL </td>
            </tr>
        </table>
      </div>  
             
    </main>
  </body>
</html>