<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PDF Pedido</title>
    <style>
      .clearfix:after {
        content: "";
        display: flex;
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

      .row{
        display: table;
        width: 100%;
      }

      .column-2-1 {
        width: 9%;
        float: left; 
      }

      .column-2-2 {
        width: 14%;
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

      #signature {
        text-align: center;
      }     

      .signatureImg{
        width: 42%;
      }

      #docs {
        text-align: center;
      }

      .docsImg {
        width: 100%; /* Aumenta el ancho al 80% del contenedor */
        height: 30px; /* Esto mantiene la relación de aspecto original */
      }

      h1 {
        font-family: "Arial Narrow", Arial, sans-serif;
        border: 2.5px solid  #D1D1D1;
        color: #FFFFFF;
        font-size: 1.2em;
        line-height: 1.4em;
        font-weight: bold;
        text-align: center;
        margin-top: -8px;
        background: #1E4E79;
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

      .blackTitle {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #000;
        font-size: 1.5em;
        font-weight: bold;
        text-align: center;
        padding: 10px;
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

      .leyenda {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #000000;
        font-size: 1em;
        line-height: 1.4em;
        font-weight: bold;
        margin-top: 40px;
      }

      #project {
        float: left;
        font-weight: bold;
        color: #000;
        text-decoration: underline; /* Agregar subrayado */
      }

      #project span {
        text-align: right;
        text-decoration: normal; /* Agregar subrayado */
        width: 180px;
        margin-right: 15px;
        display: inline-block;
      }

       .blue {
        color: #0073B5;
       }

       .red {
        color: #FF0000;
       }

      #project div {
        white-space: nowrap;      
      }

      table {
        overflow-x: auto; /* Permite desplazarse horizontalmente si el contenido es más ancho que el contenedor */
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 10px;
      }

      .columnTable-50 {
        width: 50%; 
        float: left; /* Agrega esta propiedad para alinear las tablas una al lado de la otra */
      }

      table th {
        font-family: "Arial Narrow", Arial, sans-serif;
        text-align: center;
        margin: 0px 5px;
        color: #FFFFFF;
        font-size: 1.1em;
        border: 1px solid #000;
        white-space: nowrap;        
        font-weight: bold;
        background: #1E4E79;
      }

      .desc {
        font-family: Arial, sans-serif;
        text-align: left;
        font-size: 1em;
        margin-left: 40px;
      }
      
      table .observ {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 10px;
        color: #F26726;
        font-weight: bold; /* Negrita */
      }

      table td {
        margin: 0px 5px;
        border: 1px solid #000;
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
        <p class="title">ORDEN DE PEDIDO</p>
        <h2>ÁREA: LOGÍSTICA F-03-01/R2 PERIODICIDAD: CADA PEDIDO RESGURADO: 3 AÑOS/ELECTRONICO REVISION: JUNIO 2019</h2>
      </div>      
    </header>
    <main>
      <div id="project">
        <div><span>FECHA:</span><a class="blue">{{ $trip->date }}</a></div>
        <div><span>ESTACION:</span><a class="red">{{ $trip->destination->no_season }}</a></div>
      </div>
      <p class="leyenda">Entregar unicamente al portador cuyo nombre e identificación y demas datos personales se plasman a continuación, el cargamento para transportar con destino a:</p>
      <h1>GENERALES</h1> 
      <div id="project">
        <div><span>NOMBRE DEL SOLICITANTE:</span>{{ $trip->name }}</div>
        <div><span>MEDIO DE SOLICITUD:</span>{{ $trip->application_medium }}</div>
        <div><span>NOMBRE O RAZÓN SOCIAL:</span>{{ $customer->name }}</div>
        <div><span>DIRECCION:</span>{{$trip->destination->street.' '.$trip->destination->suburb}}</div>
        <div><span>CIUDAD:</span>{{$trip->destination->city.', '.$trip->destination->state.', '.$trip->destination->cp}}</div>
        <div><span>ESTACIÓN:</span>{{ $trip->destination->name }}</div>
        <div><span>SIIC:</span>{{ $trip->destination->siic }}</div>
        <div><span>ENCARGADO DE ESTACION:</span>{{ $customer->manager_base }}</div>
        <div><span>E-MAIL:</span>{{ $customer->email }}</div>
        <div><span>TERMINAL ORIGEN:</span><a class="blue">{{ $trip->origin->name }}</a></div>
        <div><span>TRANSPORTISTA:</span><a class="blue">{{ $unit['carrier'] }}</a></div>
        <div><span>EQUIPO:</span><a class="blue">{{ $unit['no_economic'] }}</a></div>
        <div><span>PLACAS TRACTOR:</span>{{ $unit['placaTracto'] }}</div>
        <div><span>PLACAS TONEL 1:</span>{{ $unit['placaT1'] }}</div>
        <div><span>PLACAS TONEL 2:</span>{{ $unit['placaT2'] }}</div>
        <div><span>VOLUMEN:</span>{{ $unit['volume'] }}</div>
        <div><span>PRODUCTO:</span><a class="blue">{{ $trip->product }}</a></div>
        <div><span>OPERADOR:</span>{{$operator->name.' '.$operator->a_paterno.' '.$operator->a_materno}}</div>
        <div><span>RFC OPERADOR:</span>{{$operator->rfc}}</div>
      </div> <br>
      <div class="row">
        <div class="column-2-1"></div>
        <table class="column-2-2">
          <tr><th>Orden N°</th></tr>
          <tr><td class="blueTitle">C {{$trip->id}}</td></tr>
        </table>
      </div>      
      <table style="clear: both;"><br>
        <tr>
          <th colspan="1">PRODUCTO</th>
          <th colspan="1">EQUIPO </th>
          <th colspan="1">VOLUMEN</th>
          <th colspan="1">TAD </th>
          <th colspan="1">DESTINO </th>
        </tr>
        <tr>
          <td colspan="1" class="blackTitle">{{ $trip->product }}</td>
          <td colspan="1" class="blackTitle">{{ $unit['no_economic'] }}</td>
          <td colspan="1" class="blackTitle">{{ $unit['volume'] }}</td>
          <td colspan="1" class="blackTitle">{{ $trip->origin->name }}</td>
          <td colspan="1" class="blackTitle">{{$trip->destination->street.' '.$trip->destination->suburb.', '.$trip->destination->city.', '.$trip->destination->state.', '.$trip->destination->cp}}</td>
        </tr>
      </table>
      <div class="desc">Favor de cerciorarse estar completamente lleno este formato antes de la carga.</div>
      <div class="desc"><b>Notas:</b></div>
      <div class="desc"><b>PRIMERA.</b> Este formato queda sin valor si presenta tachaduras o enmendaduras.</div>
      <div class="desc"><b>SEGUNDA.</b> El remitente deberá revisar bajo su responsabilidad la documentación personal del conductor y del vehículo.</div><br>
      <table class="columnTable-50">        
        <tr>
          <td colspan="1" style="text-align: center;"><br>
            <div>FECHA Y FIRMA . . . . . . . . . . . . . . . . . . . . . . . . {{ $hoy }}</div>
            <div id="signature">
              <img class="signatureImg" src="{{ $coordinador->signature }}">
            </div>
            <div>__________________________________________</div>
            <div>ING. {{$coordinador->name.' '.$coordinador->a_paterno.' '.$coordinador->a_materno}}</div>
          </td>
        </tr>
        <tr><th colspan="1">COORDINADOR DE LOGÍSTICA</th></tr>
      </table>
      <table class="columnTable-50">        
        <tr>
          <th colspan="1">DOCUMENTO</th>  
          <th colspan="1">CUMPLE</th> 
          <th colspan="1">NO CUMPLE</th>  
          <th colspan="1">. N / A .</th>
        </tr>
        <tr>
          <td style="text-align: center;"><br>
            <b><div>Programación</div> <br>
            <div>Vale de retiro</div> <br>
            <div>Carta autorización</div> <br>
            <div>Sello de goma</div> </b><br>
          </td>
          <td colspan="3">
            <div id="docs">
              <img class="docsImg" src="{{ $docs->programming_doc }}">
            </div>
            <div id="docs">
              <img class="docsImg" src="{{ $docs->vale_doc }}">
            </div>
            <div id="docs">
              <img class="docsImg" src="{{ $docs->letter_doc }}">
            </div>
            <div id="docs">
              <img class="docsImg" src="{{ $docs->stamp_doc }}">
            </div>
          </td>
        </tr>        
      </table>      
      <div class="title">SEGUIMIENTO A QUEJAS Y/O SUGERENCIAS</div>
      <div class="title red">calidad@tramusacarrier.com.mx</div>
      <table>
        <tr><th colspan="1">OBSERVACIONES / Observations:</th></tr>
        <tr><td colspan="1">
          <br><br><br>
          <div class="observ">
            {{$trip->detaills}}
          </div><br><br><br><br>
        </td></tr>
      </table>
    </main>
  </body>
</html>
