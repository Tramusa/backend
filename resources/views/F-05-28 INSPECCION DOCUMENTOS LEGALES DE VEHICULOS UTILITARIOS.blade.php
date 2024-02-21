<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-05-28 INSPECCION DOCUMENTOS LEGALES DE VEHICULOS UTILITARIOS</title>
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
        border: 2.5px solid #000;
      }

      .column-2 {
        width: 78%;
        float: left;
        border: 2.5px solid #000;
      }

      .row{
        display: table;
        width: 100%;
      }

      .column-2-1 {
        width: 62%;
        float: left; 
        margin-left: 12%;
      }

      .column-2-2 {
        width: 20%;
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
        font-size: 1em;
        padding: 10px 5px;
        border: 1px solid #000;
      }

      .signature {
        font-family: 'Courier New', monospace; /* Una fuente cursiva disponible en la mayoría de los sistemas */
        font-style: italic;
        font-weight: bold;
        font-size: 18px;
        color: #000080; /* Azul marino */
        letter-spacing: 0.6px; /* Espaciado ligero para emular la caligrafía */
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
        <p class="title">INSPECCION DOCUMENTOS LEGALES DE VEHICULOS UTILITARIOS</p>
        <h2>ÁREA: MANTENIMIENTO   F-05-28   PERIODICIDAD: MENSUAL   RESGUARDO: 2 AÑOS      REVISIÓN: MAYO 2023</h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1">
          FECHA: ___{{ $fecha }}___<br>
          UNIDAD: ___{{ $data['unit']['no_economic'] ?? ' '}}___<br><br><br>
        </div>
      </div>     
      <div style="clear: both;"></div>
      <div class="row">        
          <table style="width: 100%;">
            <tr>
              <th>N° <br> </th>
              <th>DOCUMENTOS LEGALES</th>
              <th>CUMPLE<br>(SI, NO, N/A)<br> </th>
              <th>NO. DE <br>DOCUMENTO</th>
              <th>VIGENCIA</th>
              <th>OBSERVACIONES</th>
            </tr>
            <tr>
              <td>1 <br> </td>
              <td>Factura</td>
              <td>{{ $data['factura'] ?? ' '}}</td>
              <td></td>
              <td></td>
              <td>{{ $data['obFactura'] ?? ' '}}</td>
            </tr>
            <tr>
              <td>6 <br> </td>
              <td>Póliza de seguro</td>
              <td>{{ $data['polizaDeSeguro'] ?? ' '}}</td>
              <td>{{ $data['unit']['insurance_policy'] ?? ' '}}</td>
              <td>{{ $data['unit']['safe_expiration'] ?? ' '}}</td>
              <td>{{ $data['obPolizaDeSeguro'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>7 <br> </td>
              <td>Recibo póliza de seguro</td>
              <td>{{ $data['reciboPolizaDeSeguro'] ?? ' '}}</td>
              <td>{{ $data['unit']['policy_receipt'] ?? ' '}}</td>
              <td>{{ $data['unit']['expiration_receipt'] ?? ' '}}</td>
              <td>{{ $data['obReciboPolizaDeSeguro'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>9 <br> </td>
              <td>Dictamen fisico-mecánica NOM-068-SCT-2014</td>
              <td>{{ $data['dictamenFisicoMecanica'] ?? ' '}}</td>
              <td>{{ $data['unit']['physical_mechanical'] ?? ' '}}</td>
              <td>{{ $data['unit']['physical_expiration'] ?? ' '}}</td>
              <td>{{ $data['obDictamenFisicoMecanica'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>10 <br> </td>
              <td>Dictamen baja emisión de contaminantes</td>
              <td>{{ $data['dictamenBajaEmisionContaminantes'] ?? ' '}}</td>
              <td>{{ $data['unit']['pollutant_emission'] ?? ' '}}</td>
              <td>{{ $data['unit']['contaminant_expiration'] ?? ' '}}</td>
              <td>{{ $data['obDictamenBajaEmisionContaminantes'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>11 <br> </td>
              <td>Permiso de la S.C.T. de la empresa</td>
              <td>{{ $data['permisoSCTEmpresa'] ?? ' '}}</td>
              <td></td>
              <td></td>
              <td>{{ $data['obPermisoSCTEmpresa'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>12 <br> </td>
              <td>Permiso de la S.C.T. del vehículo</td>
              <td>{{ $data['permisoSCTVehiculo'] ?? ' '}}</td>
              <td></td>
              <td></td>
              <td>{{ $data['obPermisoSCTVehiculo'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>13 <br> </td>
              <td>Tarjeta de circulación</td>
              <td>{{ $data['tarjetaCirculacion'] ?? ' '}}</td>
              <td>{{ $data['unit']['circulation_card'] ?? ' '}}</td>
              <td>{{ $data['unit']['expiration_circulation'] ?? ' '}}</td>
              <td>{{ $data['obTarjetaCirculacion'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>15 <br> </td>
              <td>Regulador de velocidad de vehículo</td>
              <td>{{ $data['reguladorVelocidadVehiculo'] ?? ' '}}</td>
              <td></td>
              <td></td>
              <td>{{ $data['obReguladorVelocidadVehiculo'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>16 <br> </td>
              <td>Bitacora de conducción F-03-13</td>
              <td>{{ $data['bitacoraConduccion'] ?? ' '}}</td>
              <td></td>
              <td></td>
              <td>{{ $data['obBitacoraConduccion'] ?? ' '}}</td>
            </tr> 
            <tr>
              <td>17 <br> </td>
              <td>Bitacora Consumo de combustible</td>
              <td>{{ $data['BitacoraConsumoCombustible'] ?? ' '}}</td>
              <td></td>
              <td></td>
              <td>{{ $data['obBitacoraConsumoCombustible'] ?? ' '}}</td>
            </tr>
            <tr>
              <td> <br> </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr> 
            <tr>
              <td> <br> </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr> 
            <tr>
              <td> <br> </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td> <br> </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td> <br> </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>     
          </table>
      </div>
      <div style="clear: both;"></div>
        <div><br><br><br><br><br><br><br><br>
          <table style="width: 100%; text-align: center; font-weight: bold;">            
            <tr>  
              <td>                
                <br>
                <br>
                  <div class="signature">{{ $data['coordinador']->name }} {{ $data['coordinador']->a_paterno }} {{ $data['coordinador']->a_materno }}</div><br>
                  _______________________________________________<br>
                  {{ $data['coordinador']->name }} {{ $data['coordinador']->a_paterno }} {{ $data['coordinador']->a_materno }}
              </td>
              <td></td>
            </tr>
            <tr>
              <th>AUXILIAR LOGISTICA PERSONAL</th>
              <th style="width: 50%; ">OBSERVACIONES</th>
            </tr>
          </table>
        </div>  
      
    </main>
  </body>
</html>