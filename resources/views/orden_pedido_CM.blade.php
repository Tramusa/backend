<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PDF Pedido CM</title>
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
        width: 15%;
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
        margin: 10px 0 10px 0;
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
        clear: both;
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
        font-size: 1.3em;
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

      #project {
        float: left;
        font-weight: bold;
        color: #000;
        text-decoration: underline; /* Agregar subrayado */
      }

      #project span {
        text-align: right;
        text-decoration: normal; /* Agregar subrayado */
        width: 220px;
        margin-right: 20px;
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
          <img class="logoImg" src="{{ $logoImage }}">
        </div>
      </div>
      <div class="column-2">
        <p class="blueTitle">TRAMUSA CARRIER S.A. DE C.V.</p>
        <p class="title">ORDEN DE PEDIDO CONCENTRADO DE MINERAL</p>
        <h2>ÁREA: LOGÍSTICA F-03-26 FRECUENCIA: CADA PEDIDO RESGUARDO: 3 AÑOS ELECTRÓNICO REVISIÓN: MARZO 2020</h2>
      </div>      
    </header>
    <main>
      <div id="project">
        <div><span>FECHA:</span><a class="blue">viernes, 7 de julio de 2023</a></div>
        <div><span>LUGAR DE CARGA:</span><a class="blue">MINA LA COLORADA</a></div>
      </div><br><br><br>
      <h1>GENERALES</h1> 
      <div id="project">
        <div><span>MEDIO DE SOLICITUD:</span><a class="blue">MINA LA COLORADA</a></div>
        <div><span>NOMBRE O RAZÓN SOCIAL:</span>PLATA PANAMERICANA, S.A. DE C.V.</div>
        <div><span>CIUDAD:</span><a class="blue">MINA LA COLORADA</a></div>
        <div><span>CONTACTO:</span><a class="blue">MINA LA COLORADA</a></div>
        <div><span>E-MAIL:</span><a class="blue">homero.adamecruz@mx.panamericansilver.com</a></div>
      </div>
      <div class="row">
        <div class="column-2-1"><br></div>
        <table class="column-2-2">
          <tr><th>N° DE ORDEN</th></tr>
          <tr><td class="blueTitle">MPA 198</td></tr>
          <tr><td class="blueTitle">ORDINARIO</td></tr>
        </table>
      </div>

      <h1>TERMINALES DE DESCARGA:</h1> 
      <div id="project">
        <div><span>1) NOMBRE DE TERMINAL DESTINO:</span>IMPALA TERMINALS MEXICO, S.A. DE C.V</div>
        <div><span>DOMICILIO DE ENTREGA:</span><a class="blue">Carr. Manzanillo-Minatitlán KM 1.5 Col. Tepeixtles Manzanillo, Col. C.P. 28239</a></div>
        <div><span>2) NOMBRE DE TERMINAL DESTINO:</span>IMPALA TERMINALS MEXICO, S.A. DE C.V</div>
        <div><span>DOMICILIO DE ENTREGA:</span><a class="blue">Carr. Manzanillo-Minatitlán KM 1.5 Col. Tepeixtles Manzanillo, Col. C.P. 28239</a></div>
        <div><span>3) NOMBRE DE TERMINAL DESTINO:</span>IMPALA TERMINALS MEXICO, S.A. DE C.V</div>
        <div><span>DOMICILIO DE ENTREGA:</span><a class="blue">Carr. Manzanillo-Minatitlán KM 1.5 Col. Tepeixtles Manzanillo, Col. C.P. 28239</a></div>
        <div><span>4) NOMBRE DE TERMINAL DESTINO: </span>IMPALA TERMINALS MEXICO, S.A. DE C.V</div>
        <div><span>DOMICILIO DE ENTREGA:</span><a class="blue">Carr. Manzanillo-Minatitlán KM 1.5 Col. Tepeixtles Manzanillo, Col. C.P. 28239</a></div>
        <div><span>5) NOMBRE DE TERMINAL DESTINO: </span>IMPALA TERMINALS MEXICO, S.A. DE C.V</div>
        <div><span>DOMICILIO DE ENTREGA:</span><a class="blue">Carr. Federal Libre Manzanillo-Minatitlan KM 4.5 Col. Tepeixtles Manzanillo, Col. C.P. 28239</a></div>
        <div><span>NO. DE EQUIPO:</span>FZN4102</div>
        <div><span>PLACAS TRACTOR:</span><a class="blue">65AR3V</a></div>
        <div><span>MARCA:</span><a class="blue">KENWORTH</a></div>
        <div><span>AÑO:</span><a class="blue">2020</a></div>
        <div><span>PLACAS REMOLQUE 1: </span><a class="blue">17UF8K</a></div>
        <div><span>MARCA:</span><a class="blue">FRUEHAUF</a></div>
        <div><span>AÑO:</span><a class="blue">2020</a></div>
        <div><span>PLACAS REMOLQUE 2:</span><a class="blue">N / A</a></div>
        <div><span>VOLUMEN DE CARGA:</span><a class="blue">36 TON</a></div>
        <div><span>PRODUCTO/MATERIAL:</span>CONCENTRADO DE MINERAL</div>
        <div><span>OPERADOR UNIDAD TRANSPORTE: </span><a class="blue">SERGIO SIMENTAL GARCÍA</a></div>
        <div><span>RFC OPERADOR:</span><a class="blue">SIGS841221GL1</a></div>
      </div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      
      <table>
        <tr>
          <th colspan="1">PRODUCTO</th>
          <th colspan="1">EQUIPO </th>
          <th colspan="1">VOL DE CARGA</th>
          <th colspan="1">LUGAR DE DESCARGA </th>
          <th colspan="1">DIRECCIÓN DE DESCARGA </th>
        </tr>
        <tr>
          <td colspan="1" class="blackTitle">CONCENTRADO DE MINERAL</td>
          <td colspan="1" class="blackTitle">FZN4102</td>
          <td colspan="1" class="blackTitle">44 TON</td>
          <td colspan="1" class="blackTitle">IMPALA TERMINALS MEXICO, S.A. DE C.V.</td>
          <td colspan="1" class="blackTitle">Carr. Manzanillo-Minatitlán KM 1.5 Col. Tepeixtles Manzanillo, Col. C.P. 28239</td>
        </tr>
      </table>
      <div class="desc">Favor de cerciorarse estar completamente lleno este formato antes de la carga.</div>
      <div class="desc"><b>Notas:</b></div>
      <div class="desc"><b>PRIMERA.</b> Este formato queda sin valor si presenta tachaduras o enmendaduras.</div>
      <div class="desc"><b>SEGUNDA.</b> El remitente deberá revisar bajo su responsabilidad la documentación personal del conductor y del vehículo.</div>
      <div class="desc"><b>TERCERA.</b> La Terminal de descarga de mercancías, se asigna a decisión del cliente con posibles cambios a petición del mismo.</div><br>
      <table class="columnTable-50">        
        <tr>
          <td colspan="1" style="text-align: center;"><br>
            <div>FECHA Y FIRMA . . . . . . . . . . . . . . . . . . . . . . . . 06/07/2023 </div>
            <div><br><br><br><br><br><br><br></div>
            <div>__________________________________________</div>
            <div>ING. ELÍAS JARETH ROCHA JIMÉNEZ</div><br>
          </td>
        </tr>
        <tr><th colspan="1">COORDINADOR DE LOGÍSTICA</th></tr>
      </table>
      <table class="columnTable-50">        
        <tr>
          <th colspan="1">DOCUMENTO</th>  
          <th colspan="1">CUMPLE</th>  
          <th colspan="1">  N / A  </th>
        </tr>
        <tr>
          <td style="text-align: right;"><br>
            <b><div>Carta porte</div> <br>
            <div>Bitácora de conducción</div> <br>
            <div>Reglas de oro</div> <br>
            <div>Tarjeta de 5 puntos</div> </b><br>
          </td>
          <td colspan="2"></td>
        </tr>        
      </table>      
      <div class="title">SEGUIMIENTO A QUEJAS Y/O SUGERENCIAS</div>
      <div class="title red">calidad@tramusacarrier.com.mx</div>
    </main>
  </body>
</html>
