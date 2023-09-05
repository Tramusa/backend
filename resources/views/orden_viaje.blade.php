<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PDF Viajes</title>
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

      .row{
        display: table;
        width: 100%;
      }

      .column-2-1 {
        width: 77%;
        float: left; 
      }

      .column-2-2 {
        width: 15%;
        float: left;
        border: 1px solid #000;
      }

      .table-2 {
        clear: both; /* Añadir clear: both para que esta tabla se coloque debajo */
        width: 100%;
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

      #perfil {
        margin: 0;
        text-align: left;
        border: 2px solid #F26726;
        border-radius: 15%; /* Añade un borde redondeado */
        overflow: hidden; /* Oculta las esquinas sobrantes de la imagen */
        padding: 3px; /* Agrega un pequeño espacio alrededor de la imagen de perfil */
        float: left; /* Agregamos un float para que el perfil esté alineado a la izquierda */
      }

      #perfil img {
        width: 80px; /* Ajusta el tamaño de la imagen según lo que desees */
        vertical-align: top;
        margin: 0;
      }

      #logoID {
        text-align: right;
        display: inline-block;
        margin: 30px 0 30px 0;
        padding-left: 5px;
        vertical-align: top;
      }

      #logoID img {
        width: 75px; /* Ajusta el tamaño de la imagen según lo que desees */        
      }

      #kardex {
        color: #F26726;
        font-weight: bold;
        padding-bottom: 20px;
        width: 100%; /* Agregamos un ancho del 100% para que ocupe todo el espacio disponible */
      }

      .izqKadrex {
        border-radius: 15%; /* Añade un borde redondeado */
        color: #FFFFFF;
        overflow: hidden; /* Oculta las esquinas sobrantes de la imagen */
        text-align: center;
        width: 40px;
        margin-right: 6px;
        display: inline-block;
        background-color: #F26726;
      }

      #logo {
        flex: 3;
        text-align: left;       
      }     

      .logoImg{
        width: 100%;
        margin: 10px 0 10px 0;
      }

      #ruta {
        flex: 3;   
        text-align: center;      
      }     

      .rutaImg{
        width: 100%;
        height: 25%;
        margin: 0;
      }

      #unit {
        flex: 3;   
        text-align: center;      
      }     

      .unitImg{
        width: 100%;
        height: 12%;
        margin: 0;
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

      .names {
        font-family: "Arial Narrow", Arial, sans-serif;
        color: #9B9B9B;
        font-size: 1.2em;
        font-weight: bold;
        text-align: center;
        margin: 10px 0 10px 0;
        width: 100%;
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
        color: #000;
        font-size: 0.9em;
      }

      .izq {
        text-align: right;
        width: 130px;
        margin-right: 8px;
        display: inline-block;
      }      

      #project div {
        white-space: nowrap;      
      }

      table {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 10px;
      }

      .columnTable-1 {
        width: 40%; /* 5/12 */
      }

      .columnTable-2 {
        width: 30%; /* 4/12 */
      }

      .line {
        border-left: 20px solid #F26726;
      }

      .columnTable-3{
        width: 23%; 
      }

      .columnTable-2-1 {
        width: 23%; 
      }
      .columnTable-2-2 {
        width: 23%; 
      }
      .columnTable-2-4 {
        width: 23%; 
      }

      .columnTable-2-3 {
        width: 31%; 
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
        padding: 0px 5px;
        color: #FFFFFF;
        font-size: 1em;
        border: 1px solid #000;
        white-space: nowrap;        
        font-weight: bold;
        background: #1E4E79;
      }

      table .desc {
        font-family: Arial, sans-serif;
        text-align: left;
        font-size: 1em;
        margin-left: 5px;
        line-height: 0.9;
      }

      table .info {
        font-family: Arial, sans-serif;
        text-align: left;
        font-size: 1em;
        line-height: 0.9;
        margin-left: 20px;
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
          <img class="logoImg" src="{{ $logoImage }}">
        </div>
      </div>
      <div class="column-2">
        <p class="blueTitle">TRAMUSA CARRIER S.A. DE C.V.</p>
        <p class="title">ORDEN DE VIAJE / Service confirmation</p>
        <h2>ÁREA: LOGÍSTICA F-03-33/R2 PERIODICIDAD: CUANDO SE REQUIERA RESGUARDO: 3 AÑOS/ELECTRÓNICO REVISIÓN: AGOSTO 2021</h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1"></div>
        <table class="column-2-2">
          <tr><th>Orden de Viaje / Trip number</th></tr>
          <tr><td class="blueTitle">P {{$trip->id}}</td></tr>
        </table>
      </div>
      <table class="table-2">
        <tr>
          <th class="columnTable-1" colspan="1">INFORMACIÓN GENERAL DEL SERVICIO / Trip Information</th>
          <th class="columnTable-2" colspan="1">MAPA DE TRAYECTO / Route map</th>
          <th class="columnTable-3" colspan="1">OPERADOR KARDEX / Operator ID</th>
        </tr>
        <tr>
          <td colspan="1"><br>
            <div class="desc">Fecha de servicio / Date of service:</div>
            <div class="info">{{$trip->date}}</div><br>
            <div class="desc">Hora de incio de viaje / Time of tridiv starts:</div>
            <div class="info">{{$trip->hour}}</div><br>
            <div class="desc">Tiempo estimado de trayecto / Estimated time of trip:</div>
            <div class="info">{{$ruta->time}}</div><br>
            <div class="desc">Hora esperada de llegada a destino / Arriving time: </div>
            <div class="info">{{$trip->end_date}}</div><br>
            <b><div class="desc">Hora recomendada de inicio de sueño </div>
            <div class="info">{{$trip->recommended_hour}}</div></b><br>
            <div class="desc">Nombre de los pasajeros a transportar / Passangers Name: </div>
            <div class="blueTitle">SE ANEXA LISTA DE PASAJEROS</div>
          </td>
          <td colspan="1">
            <div id="ruta">
              <img class="rutaImg" src="{{ $ruta->image }}">
            </div>          
          </td>
          <td class="line" colspan="1">
            <div id="perfil">
              <img src="{{ $perfilImage }}">
            </div>
            <div id="logoID">
              <img src="{{ $logoImage }}">
            </div>
            <div class="names">{{$operator->name.' '.$operator->a_paterno.' '.$operator->a_materno}}</div>
            <div id="kardex">
              <div><span class="izqKadrex">NSS </span>{{$operator->socia_health}}</div>
              <div><span class="izqKadrex">RFC</span>{{$operator->rfc}}</div>
              <div><span class="izqKadrex">CURP</span>{{$operator->curp}}</div>
              <div><span class="izqKadrex">LIC</span>{{$operator->license}}</div>
              <div><span class="izqKadrex">PHONE</span>{{$operator->cell}}</div>
            </div>
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <td class="columnTable-2-1 " colspan="1">
            <div class="desc">Lugar de origen / Place of origen:</div>
            <div class="info">{{$trip->origin->name}}</div><br>
            <div class="desc">Dirección de punto de reunión en origen / Adress of meeting point:</div>
            <div class="info">{{$trip->origin->street.' '.$trip->origin->suburb.', '.$trip->origin->city.', '.$trip->origin->state.', '.$trip->origin->cp}}</div><br>
          </td>
          <td class="columnTable-2-1" colspan="1">
            <div class="desc">Paradas intermedias / Intermediate stops:  </div>
            <div class="info">{{($trip->p_intermediate == 'N/A')? $trip->p_intermediate :$trip->p_intermediate->name}}</div><br>
            <div class="desc">Dirección de punto de reunión intermedio/ Adress of intermediate meeting point:</div>
            <div class="info">{{($trip->p_intermediate == 'N/A')? $trip->p_intermediate :$trip->p_intermediate->street.' '.$trip->p_intermediate->suburb.', '.$trip->p_intermediate->city.', '.$trip->p_intermediate->state.', '.$trip->p_intermediate->cp}}</div><br>
          </td>
          <td class="columnTable-2-3" colspan="1">
            <div class="desc">Paradores autorizados / Authorized stops:</div>
            <div class="info">{{($trip->p_authorized == 'N/A')? $trip->p_authorized : $trip->p_authorized->name}}</div><br>
            <div class="desc">Dirección de paradores autorizados / Adress of Authorized stops: </div>
            <div class="info">{{($trip->p_authorized == 'N/A')? $trip->p_authorized :$trip->p_authorized->street.' '.$trip->p_authorized->suburb.', '.$trip->p_authorized->city.', '.$trip->p_authorized->state.', '.$trip->p_authorized->cp}}</div><br>
          </td>
          <td class="columnTable-2-4" colspan="1">
            <div class="desc">Lugar de destino / Place of destiny:</div>
            <div class="info">{{$trip->destination->name}}</div><br>
            <div class="desc">Dirección de destino / Destiny adress:</div>
            <div class="info">{{$trip->destination->street.' '.$trip->destination->suburb.', '.$trip->destination->city.', '.$trip->destination->state.', '.$trip->destination->cp}}</div><br>
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <tr>
          <th colspan="3">INFORMACIÓN DE LA UNIDAD DE TRANSPORTE / Transport unit information</th>
        </tr>
        <tr>
          <td class="columnTable-3-1 " colspan="1">
            <div id="project">
              <div><span>No. Economico / Vehicle ID:</span>{{ $unit->unitInfo->no_economic }}</div>
              <div><span>Tipo de unidad / Vehicle type:</span>Autobús</div>
              <div><span>Matrícula / License plate:</span>{{ $unit->unitInfo->no_placas }}</div>
              <div><span>Marca / Vehicle brand: </span>{{ $unit->unitInfo->brand }}</div>
              <div><span>Modelo / Year:</span>{{ $unit->unitInfo->model }}</div>
              <div><span>Color / Color:</span>Blanco</div>
              <div><span>Capacidad de pasajeros / Passenger capacity: </span>{{ $unit->unitInfo->no_passengers }}</div>
            </div>
            <div style="clear: both;"></div>            
          </td>
          <td class="columnTable-3-2" colspan="1">
            <div id="unit">
              <img class="unitImg" src="{{ $unit->unitInfo->left }}">
            </div>
          </td>
          <td class="columnTable-3-3" colspan="1">
            <div id="unit">
              <img class="unitImg" src="{{ $unit->unitInfo->front }}">
            </div>
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <tr>
          <th colspan="1">QUIÉN SOLICITA EL SERVICIO / Applicant's services name</th>
          <th colspan="1">COORDINADOR DE LOGÍSTICA / Logistic cordinator</th>
        </tr>
        <tr>
          <td colspan="1">
            <div class="names">{{$trip->name}}</div>
            <div id="involved">
              <div><span class="izq">Área / Area:</span>{{$trip->position}}</div>
              <div><span class="izq">Tel / Mobile phone:</span>{{$trip->phone}}</div>
              <div><span class="izq">Correo electrónico / E-mail:</span><a href="mailto:{{$trip->mail}}">{{$trip->mail}}</a></div>
              <div><span class="izq">Orden de Compra / CECO:</span>{{$ceco?->description}}</div>
            </div>
            <div style="clear: both;"></div>
          </td>
          <td colspan="1">
            <div class="names">{{$coodinador?->name.' '.$coodinador?->a_paterno.' '.$coodinador?->a_materno}}</div>
            <div id="involved">
              <div><span class="izq">Área / Area:</span>{{$coodinador?->rol}}</div>
              <div><span class="izq">Tel / Mobile phone:</span>433-109-9996</div>
              <div><span class="izq">Correo electrónico / E-mail:</span><a href="mailto:{{$coodinador?->email}}">{{$coodinador?->email}}</a></div>
            </div>
            <div style="clear: both;"></div>
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <tr>
          <th colspan="1">MONITOR / Travel monitor</th>
          <th colspan="1">SUPERVISOR DE SEGURIDAD / Security supervisor</th>
        </tr>
        <tr>
          <td colspan="1">
            <div class="names">{{$monitor?->name.' '.$monitor?->a_paterno.' '.$monitor?->a_materno}}</div>
            <div id="involved">
              <div><span class="izq">Área / Area:</span>{{$monitor?->rol}}</div>
              <div><span class="izq">Tel / Mobile phone:</span>433-103-8319</div>
              <div><span class="izq">Correo electrónico / E-mail:</span><a href="mailto:{{$monitor?->email}}">{{$monitor?->email}}</a></div>
            </div>
            <div style="clear: both;"></div>
          </td>
          <td colspan="1">
            <div class="names">{{$seguridad?->name.' '.$seguridad?->a_paterno.' '.$seguridad?->a_materno}}</div>
            <div id="involved">
              <div><span class="izq">Área / Area:</span>{{$seguridad?->rol}}</div>
              <div><span class="izq">Tel / Mobile phone:</span>433-108-1732</div>
              <div><span class="izq">Correo electrónico / E-mail:</span><a href="mailto:{{$seguridad?->email}}">{{$seguridad?->email}}</a></div>
            </div>
            <div style="clear: both;"></div>
          </td>
        </tr>
      </table>
      <table style="width: 100%;">
        <tr><th colspan="1">OBSERVACIONES / Observations:</th></tr>
        <tr><td colspan="1">
          <div class="observ">
            {{$trip->detaills}}
          </div>
        </td></tr>
      </table>
    </main>
  </body>
</html>