<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PDF Viajes</title>
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

      #perfil img {
        width: 80px; /* Ajusta el tamaño de la imagen según lo que desees */
        vertical-align: top;
      }

      /* Estilos para la imagen de logo */
      #logoID {
        text-align: right;
        display: inline-block;
        margin-top: 20px;
        padding-left: 5px;
        vertical-align: top;
      }

      #logoID img {
        width: 75px; /* Ajusta el tamaño de la imagen según lo que desees */        
      }


      #logo {
        flex: 3;
        text-align: left;
      }     

      #logo img {
        width: 100%;
      }

      h6 {
        border-top: 2.5px solid  #5D6975;
        border-bottom: 2.5px solid  #5D6975;
        color: #5D6975;
        font-size: 2.4em;
        line-height: 1.4em;
        font-weight: normal;
        text-align: center;
        margin: 0 0 20px 0;
        background: url(dimension.png);
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

      .title {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #000000;
        font-size: 1.2em;
        line-height: 1.4em;
        font-weight: bold;
        text-align: center;
        margin: 0 0 0 0;
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
        padding: 10px;
        color: #F26726;
      }

      #project span {
        color: #000;
        text-align: right;
        width: 220px;
        margin-right: 10px;
        display: inline-block;
        font-size: 0.9em;
      }

      #involved {
        float: left;
        padding: 5px;
        color: #000;
        font-size: 0.9em;
      }

      .izq {
        text-align: right;
        width: 140px;
        margin-right: 10px;
        display: inline-block;
      }

      #kardex {
        float: left;
        padding: 5px;
        color: #F26726;
      }

      .izqKadrex {
        border-radius: 15%; /* Añade un borde redondeado */
        color: #FFFFFF;
        overflow: hidden; /* Oculta las esquinas sobrantes de la imagen */
        text-align: center;
        width: 45px;
        margin-right: 10px;
        display: inline-block;
        background-color: #F26726;
      }

      #company {
        float: right;
        text-align: right;
      }

      #project div,
      #company div {
        white-space: nowrap;      
      }

      table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 10px;
      }

      .columnTable-1 {
        width: 45%; /* 5/12 */
      }

      .columnTable-2 {
        width: 32%; /* 4/12 */
      }

      .columnTable-3 {
        width: 23%; /* 3/12 */
      }

      .line {
        border-left: 20px solid #F26726;
      }


      .columnTable-2-1 {
        width: 23%; 
      }

      .columnTable-2-2 {
        width: 23%; 
      }

      .columnTable-2-3 {
        width: 31%; 
      }

      .columnTable-2-4 {
        width: 23%; 
      }

      .columnTable-3-1 {
        width: 50%; 
      }

      .columnTable-3-2 {
        width: 25%; 
      }

      .columnTable-3-3 {
        width: 25%; 
      }

      table th {
        font-family: "Arial Narrow", Arial, sans-serif;
        text-align: center;
      }

      table th {
        padding: 0px 5px;
        color: #FFFFFF;
        font-size: 1.1em;
        border: 1px solid #000;
        white-space: nowrap;        
        font-weight: bold;
        background: #1E4E79;
      }

      table .desc {
        font-family: Arial, sans-serif;
        text-align: left;
        font-size: 1em;
        padding-left: 10px;
        line-height: 0.9;
      }

      table .info {
        font-family: Arial, sans-serif;
        text-align: left;
        font-size: 1em;
        line-height: 0.9;
        padding-left: 30px;
        color: #F26726;
        font-weight: bold; /* Negrita */
        text-decoration: underline; /* Subrayado */
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
        <p class="title">ORDEN DE VIAJE / Service confirmation</p>
        <h2>ÁREA: LOGÍSTICA F-03-33/R2 PERIODICIDAD: CUANDO SE REQUIERA RESGUARDO: 3 AÑOS/ELECTRÓNICO REVISIÓN: AGOSTO 2021</h2>
      </div>      
    </header>
    <main>
      <div class="row2">
        <div class="column-2-1"><br></div>
        <table class="column-2-2">
          <tr><th>Orden de Viaje / Trip number</th></tr>
          <tr><td class="blueTitle">P 5983</td></tr>
        </table>
      </div>
      <table>
        <tr>
          <th class="columnTable-1" colspan="1">INFORMACIÓN GENERAL DEL SERVICIO / Trip Information</th>
          <th class="columnTable-2" colspan="1">MAPA DE TRAYECTO / Route map</th>
          <th class="columnTable-3" colspan="1">OPERADOR / KARDEX / Operator ID</th>
        </tr>
        <tr>
          <td class="columnTable-1 " colspan="1"><br>
            <div class="desc">Fecha de servicio / Date of service:</div>
            <div class="info">lunes, 3 de julio de 2023</div><br>
            <div class="desc">Hora de incio de viaje / Time of tridiv starts:</div>
            <div class="info">06:00:00</div><br>
            <div class="desc">Tiempo estimado de trayecto / Estimated time of trip:</div>
            <div class="info">06:00:00</div><br>
            <div class="desc">Hora esperada de llegada a destino / Arriving time: </div>
            <div class="info">06:00:00</div><br>
            <div class="desc">Hora recomendada de inicio de sueño </div>
            <div class="info">06:00:00</div><br>
            <div class="desc">Nombre de los pasajeros a transportar / Passangers Name: </div>
            <div class="blueTitle">SE ANEXA LISTA DE PASAJEROS</div>
          </td>
          <td class="columnTable-2" colspan="1"></td>
          <td class="columnTable-3 line" colspan="1"><br>
            <div id="perfil">
              <img src="{{ asset('storage/avatars/m6fiLe0AyMbRngF6dxzzDsqV799RiMEHZIGnFBY2.png') }}">
            </div>
            <div id="logoID">
              <img src="{{ asset('imgPDF/logo.png') }}">
            </div>
            <div class="names">Luis Antonio Enriquez F. </div><br>
            <div id="kardex">
              <div><span class="izqKadrex">NSS </span> Supervisor</div>
              <div><span class="izqKadrex">RFC</span> 433-103-8319</div>
              <div><span class="izqKadrex">CURP</span> </div>
              <div><span class="izqKadrex">LIC</span> 465465468</div>
              <div><span class="izqKadrex">PHONE</span> 465465468</div><br>
            </div>
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <td class="columnTable-2-1 " colspan="1"><br>
            <div class="desc">Lugar de origen / Place of origen: </div>
            <div class="info">Saltillo (Plaza Sendero Sur)</div><br><br>
            <div class="desc">Dirección de punto de reunión en origen / Adress of meeting point:</div>
            <div class="info">Blvd. Antonio Cárdenas 4159, Parques de la Cañada, 25080 Saltillo, Coah.</div><br>
          </td>
          <td class="columnTable-2-1" colspan="1"><br>
            <div class="desc">Paradas intermedias / Intermediate stops:  </div>
            <div class="info">N/A</div><br><br>
            <div class="desc">Dirección de punto de reunión intermedio/ Adress of intermediate meeting point:</div>
            <div class="info">N/A</div><br>
          </td>
          <td class="columnTable-2-3" colspan="1"><br>
            <div class="desc">Paradores autorizados / Authorized stops:</div>
            <div class="info">N/A</div><br><br>
            <div class="desc">Dirección de paradores autorizados / Adress of Authorized stops: </div>
            <div class="info">N/A</div><br>
          </td>
          <td class="columnTable-2-4" colspan="1"><br>
            <div class="desc">Lugar de destino / Place of destiny:   </div>
            <div class="info">Minera Peñasquito</div><br><br>
            <div class="desc">Dirección de destino / Destiny adress: </div>
            <div class="info">Domicilio Conocido, Mazapil, Zac. C.P. 98230</div><br>
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <th colspan="3">INFORMACIÓN DE LA UNIDAD DE TRANSPORTE / Transport unit information</th>
        </tr>
        <tr>
          <td class="columnTable-3-1 " colspan="1">
            <div id="project">
              <div><span>No. Economico / Vehicle ID:</span> FZN 4401</div>
              <div><span>Tipo de unidad / Vehicle type:</span> Autobús</div>
              <div><span>Matrícula / License plate:</span>17-RC-1A</div>
              <div><span>Marca / Vehicle brand: </span> Mercedes Benz</div>
              <div><span>Modelo / Year:</span> 2015</div>
              <div><span>Color / Color:</span> Blanco</div>
              <div><span>Capacidad de pasajeros / Passenger capacity: </span> 39 Pasajeros</div>
            </div>
          </td>
          <td class="columnTable-3-2" colspan="1"></td>
          <td class="columnTable-3-3" colspan="1"></td>
        </tr>
      </table>
      <table>
        <tr>
          <th colspan="1">NOMBRE DE QUIÉN SOLICITA EL SERVICIO / Applicant's services name</th>
          <th colspan="1">COORDINADOR DE LOGÍSTICA / Logistic cordinator</th>
        </tr>
        <tr>
          <td colspan="1">
            <div class="names">Luis Antonio Enriquez F. </div>
            <div id="involved">
              <div><span class="izq">Área / Area:</span> Supervisor Transporte Personal</div>
              <div><span class="izq">Logística / Monitoreo</span> 433-103-8319</div>
              <div><span class="izq">Correo electrónico / E-mail:</span> <a href="mailto:luis.enriquez@Newmont.com">luis.enriquez@Newmont.com</a></div>
              <div><span class="izq">Orden de Compra / CECO:</span> 465465468</div>
            </div>
          </td>
          <td colspan="1">
            <div class="names">Jose Yair Ceceñas Grijalva </div>
            <div id="involved">
              <div><span class="izq">Área / Area:</span> Supervisor Transporte Personal</div>
              <div><span class="izq">Logística / Monitoreo</span> 433-103-8319</div>
              <div><span class="izq">Correo electrónico / E-mail:</span> <a href="mailto:monitoreo.personal@tramusacarrier.com.mx">monitoreo.personal@tramusacarrier.com.mx</a></div>
            </div>
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <th colspan="1">MONITOR / Travel monitor</th>
          <th colspan="1">SUPERVISOR DE SEGURIDAD / Security supervisor</th>
        </tr>
        <tr>
          <td colspan="1">
            <div class="names">Juan Manuel Mireles Ríos</div>
            <div id="involved">
              <div><span class="izq">Área / Area:</span> Logística / Monitoreo</div>
              <div><span class="izq">Logística / Monitoreo</span> 433-103-8319</div>
              <div><span class="izq">Correo electrónico / E-mail:</span> <a href="mailto:monitoreo.personal@tramusacarrier.com.mx">monitoreo.personal@tramusacarrier.com.mx</a></div>
            </div>
          </td>
          <td colspan="1">
            <div class="names">Vanessa Solís Amador</div>
            <div id="involved">
              <div><span class="izq">Área / Area:</span> Logística / Monitoreo</div>
              <div><span class="izq">Logística / Monitoreo</span> 433-103-8319</div>
              <div><span class="izq">Correo electrónico / E-mail:</span> <a href="mailto:monitoreo.personal@tramusacarrier.com.mx">monitoreo.personal@tramusacarrier.com.mx</a></div>
            </div>
          </td>
        </tr>
      </table>
      <table>
        <tr><th colspan="1">OBSERVACIONES / Observations:</th></tr>
        <tr><td colspan="1">
          <div class="observ">
            Velocidad Máxima Permitida.<br>
            95 km/h en tramos carreteros permitidos.
          </div>
        </td></tr>
      </table>
    </main>
  </body>
</html>