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
        display: flex;
      }

      .column-1 {
        flex: 3;
        text-align: center;
        border: 2.5px solid #D1D1D1;
      }

      .column-2 {
        flex: 9;      
        border: 2.5px solid #D1D1D1;
      }

      .row2 {
        display: flex;
      }

      .column-2-1 {
        flex: 9;
        text-align: center;
      }

      .column-2-2 {
        flex: 2;      
        border: 1px solid #000;
      }

      a {
        color: #5D6975;
        text-decoration: underline;
      }

      body {
        position: relative;
        width: 21cm;  
        height: 29.7cm; 
        margin: 0 auto; 
        color: #001028;
        background: #FFFFFF; 
        font-family: Arial, sans-serif; 
        font-size: 12px; 
        font-family: Arial;
      }

      /* Estilos para la imagen de perfil */
      #perfil {
        text-align: left;
        display: inline-block;
        border: 2px solid #F26726;
        border-radius: 15%; /* Añade un borde redondeado */
        overflow: hidden; /* Oculta las esquinas sobrantes de la imagen */
        padding: 3px; /* Agrega un pequeño espacio alrededor de la imagen de perfil */
    
      }

      #logo {
        flex: 3;
        text-align: left;
      }     

      #logo img {
        width: 100%;
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
        font-size: 0.9em;
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
        padding: 10px 10px 10px 10px;
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
        font-size: 1.2em;
        line-height: 1.4em;
        font-weight: bold;
        padding-top: 40px;
      }

      .names {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #9B9B9B;
        font-size: 1.2em;
        line-height: 1.4em;
        font-weight: bold;
        text-align: center;
        margin: 0 0 0 0;
        padding-top: 5px;
        text-decoration: underline; /* Agregar subrayado */
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
        width: 175px;
        margin-right: 20px;
        display: inline-block;
      }

      #involved {
        float: left;
        padding: 5px;
        color: #000;
        font-size: 0.9em;
      }

       .blue {
        color: #0073B5;
       }

       .red {
        color: #FF0000;
       }

      .izq {
        text-align: right;
        width: 140px;
        margin-right: 10px;
        display: inline-block;
      }

      #project div {
        white-space: nowrap;      
      }

      table {
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
        padding: 0px 5px;
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
        padding-left: 40px;
      }

      table .observ {
        font-family: Arial, sans-serif;
        text-align: center;
        padding: 10px;
        color: #F26726;
        font-weight: bold; /* Negrita */
      }

      table td {
        padding: 0px 5px;
        border: 1px solid #000;
      }
    </style>
  </head>
  <body>
    <header class="clearfix">
      <div class="column-1">
        <div id="logo">
          <img src="{{ asset('imgPDF/logo.png') }}">
        </div>
      </div>
      <div class="column-2">
        <p class="blueTitle">TRAMUSA CARRIER S.A. DE C.V.</p>
        <p class="title">ORDEN DE PEDIDO</p>
        <h2>ÁREA: LOGÍSTICA F-03-33/R2 PERIODICIDAD: CUANDO SE REQUIERA RESGUARDO: 3 AÑOS/ELECTRÓNICO REVISIÓN: AGOSTO 2021</h2>
      </div>      
    </header>
    <main>
      <div id="project">
        <div><span>FECHA:</span><a class="blue">viernes, 7 de julio de 2023</a></div>
        <div><span>ESTACION:</span><a class="red">E08945 D0979</a></div>
      </div>
      <p class="leyenda">Entregar unicamente al portador cuyo nombre e identificación y demas datos personales se plasman a continuación, el cargamento para transportar con destino a:</p>
      <h1>GENERALES</h1> 
      <div id="project">
        <div><span>NOMBRE DEL SOLICITANTE:</span> FZN 4401</div>
        <div><span>MEDIO DE SOLICITUD: </span> Autobús</div>
        <div><span>NOMBRE O RAZÓN SOCIAL:</span>17-RC-1A</div>
        <div><span>DIRECCION: </span> Mercedes Benz</div>
        <div><span>CIUDAD:</span> 2015</div>
        <div><span>ESTACIÓN:</span> Blanco</div>
        <div><span>SIIC:</span> FZN 4401</div>
        <div><span>ENCARGADO DE ESTACION:</span> Autobús</div>
        <div><span>E-MAIL:</span>17-RC-1A</div>
        <div><span>TERMINAL ORIGEN:</span><a class="blue">E08945 D0979</a></div>
        <div><span>TRANSPORTISTA:</span><a class="blue">E08945 D0979</a></div>
        <div><span>EQUIPO:</span><a class="blue">E08945 D0979</a></div>
        <div><span>PLACAS TRACTOR:</span> FZN 4401</div>
        <div><span>PLACAS TONEL 1: </span> Autobús</div>
        <div><span>PLACAS TONEL 2: </span> Autobús</div>
        <div><span>VOLUMEN:</span>17-RC-1A</div>
        <div><span>PRODUCTO:</span><a class="blue">E08945 D0979</a></div>
        <div><span>OPERADOR:</span> 2015</div>
        <div><span>RFC OPERADOR:</span> Blanco</div>
      </div> <br> <br>
      <div class="row2">
        <div class="column-2-1"><br></div>
        <table class="column-2-2">
          <tr><th>Orden de Viaje / Trip number</th></tr>
          <tr><td class="blueTitle">P 5983</td></tr>
        </table>
      </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      
      <table>
        <tr>
          <th colspan="1">PRODUCTO</th>
          <th colspan="1">EQUIPO </th>
          <th colspan="1">VOLUMEN</th>
          <th colspan="1">TAD </th>
          <th colspan="1">DESTINO </th>
        </tr>
        <tr>
          <td colspan="1" class="blackTitle">MAGNA</td>
          <td colspan="1" class="blackTitle">FZN 3423 43.500</td>
          <td colspan="1" class="blackTitle">44</td>
          <td colspan="1" class="blackTitle">TREE FUEL</td>
          <td colspan="1" class="blackTitle">E08945 D0979 SAIN ALTO, ZAC.</td>
        </tr>
      </table>
      <div class="desc">Favor de cerciorarse estar completamente lleno este formato antes de la carga.</div>
      <div class="desc"><b>Notas:</b></div>
      <div class="desc"><b>PRIMERA.</b> Este formato queda sin valor si presenta tachaduras o enmendaduras.</div>
      <div class="desc"><b>SEGUNDA.</b> El remitente deberá revisar bajo su responsabilidad la documentación personal del conductor y del vehículo.</div><br>
      <table class="columnTable-50">        
        <tr>
          <td colspan="1" style="text-align: center;">
            <div>FECHA Y HORA                         06/07/2023 </div>
            <div><br><br><br><br><br><br><br><br><br></div>
            <div>__________________________________________</div>
            <div>ING. ELÍAS JARETH ROCHA JIMÉNEZ</div>
          </td>
        </tr>
        <tr><th colspan="1">COORDINADOR DE LOGÍSTICA</th></tr>
      </table>
      <table class="columnTable-50">        
        <tr>
          <th colspan="1">DOCUMENTO</th>  
          <th colspan="1">CUMPLE</th> 
          <th colspan="1">NO CUMPLE</th>  
          <th colspan="1">N / A</th>
        </tr>
        <tr>
          <td style="text-align: center;"><br>
            <b><div>Programación</div> <br>
            <div>Vale de retiro</div> <br>
            <div>Carta autorización</div> <br>
            <div>Sello de goma</div> </b><br>
          </td>
          <td colspan="3"></td>
        </tr>        
      </table>      
      <div class="title">SEGUIMIENTO A QUEJAS Y/O SUGERENCIAS</div>
      <div class="title red">calidad@tramusacarrier.com.mx</div>
      <table>
        <tr><th colspan="1">OBSERVACIONES / Observations:</th></tr>
        <tr><td colspan="1">
          <br><br><br><br><br><br><br><br><br>
        </td></tr>
      </table>
    </main>
  </body>
</html>