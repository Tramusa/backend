<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-05-17 CONDICIONES FISICO MECANICAS TONEL</title>
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
        margin: 10px 0 10px 0;
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
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
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

      .line {
        border-left: 20px solid #F26726;
      }

      table td {
        padding: 0px 5px;
        border: 1px solid #000;
      }

      table td.columna-1 {
        width: 3%;
      }

      table td.columna-2 {
        width: 45%;
      }

      table td.columna-3 {
        width: 8%;
      }

      table td.columna-4 {
        width: 45%;
      }

      .signature {
        font-family: 'Courier New', monospace; /* Una fuente cursiva disponible en la mayoría de los sistemas */
        font-style: italic;
        font-weight: bold;
        font-size: 15px;
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
        <p class="title">REVISION DE CONDICIONES FISICO MECANICAS DE TONEL</p>
        <h2>ÁREA:MANTENIMIENTO F-05-17  PERIODICIDAD:DIARIO RESGUARDO: 3 AÑOS REVISIÓN: ABRIL DE 2021</h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1">FECHA: ____{{ $fecha }}______  VEHICULO: ____{{ $data['unit']->no_economic }}______  REMOLQUE: _____{{ $data['unit']->no_economic }}_____</div>
        <table class="column-2-2">
          <tr><th>FOLIO</th></tr>
          <tr><td class="blueTitle">{{ $data['folio'] }}</td></tr>
        </table>
      </div>     
      <div style="clear: both;"></div>
      <div class="row">        
          <table>
            <tr>
              <th>N°</th>
              <th>Revisión parte frontal remolque</th>
              <th>Cumple</th>
              <th>Obvservaciones</th>
            </tr>
            <tr>
              <td class="columna-1">1</td><td class="columna-2">Conexiones de frenos</td><td class="columna-3">{{ $data['Conexiones de frenos'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">2</td><td class="columna-2">Conexión de luces cable de 7 hilos</td><td class="columna-3">{{ $data['Conexion de luces cable de 7 hilos'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">3</td><td class="columna-2">Conexión de aire para micro switch</td><td class="columna-3">{{ $data['Conexion de aire para micro switch'] }}</td><td class="columna-4"></td>
            </tr>
          </table>
          <div class="row">        
          <table>
            <tr>
              <th>N°</th>
              <th>Revisión lado izquierdo y derecho del remolque</th>
              <th>Cumple</th>
              <th>Obvservaciones</th>
            </tr>
            <tr>
              <td class="columna-1">4</td><td class="columna-2">Patines</td><td class="columna-3">{{ $data['Luces direccionales'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">5</td><td class="columna-2">Luces de advertencia laterales</td><td class="columna-3">{{ $data['Luces de advertencia laterales'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">6</td><td class="columna-2">Razón social y numero económico</td><td class="columna-3">{{ $data['Razon social y numero economico'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">7</td><td class="columna-2">Reflejantes</td><td class="columna-3">{{ $data['Reflejantes'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">8</td><td class="columna-2">Placa metálica (porta rombos)</td><td class="columna-3">{{ $data['Placa metalica (porta rombos)'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">9</td><td class="columna-2">Número de placa identificación vehicular en chasis</td><td class="columna-3">{{ $data['Numero de placa identificacion vehicular en chasis'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">10</td><td class="columna-2">Escalerilla para remolque</td><td class="columna-3">{{ $data['Escalerilla para remolque'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">11</td><td class="columna-2">Porta Extintor</td><td class="columna-3">{{ $data['Porta Extintor'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">12</td><td class="columna-2">Caja de válvulas</td><td class="columna-3">{{ $data['Caja de valvulas'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">13</td><td class="columna-2">Sello electrónico en caja de válvulas</td><td class="columna-3">{{ $data['Sello electronico en caja de valvulas'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">14</td><td class="columna-2">Engomado pruebas espesor de laminas vigentes</td><td class="columna-3">{{ $data['Engomado pruebas espesor de laminas vigentes'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">15</td><td class="columna-2">Tuberías, Válvulas y conexiones sin fugas.(caja de válvulas)</td><td class="columna-3">{{ $data['Tuberias, Valvulas y conexiones sin fugas.(caja de valvulas)'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">16</td><td class="columna-2">Conexiones a tierra (Sin pintura)</td><td class="columna-3">{{ $data['Conexiones a tierra (Sin pintura)'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">17</td><td class="columna-2">Guardafangos (Loderas)</td><td class="columna-3">{{ $data['Guardafangos (Loderas)'] }}</td><td class="columna-4"></td>
            </tr>
          </table>
          <table>
            <tr>
              <th>N°</th>
              <th>Revisión parte posterior del remolque</th>
              <th>Cumple</th>
              <th>Obvservaciones</th>
            </tr>
            <tr>
              <td class="columna-1">18</td><td class="columna-2">Luces direccionales</td><td class="columna-3">{{ $data['Luces direccionales Posterior'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">19</td><td class="columna-2">Luces de advertencia</td><td class="columna-3">{{ $data['Luces de advertencia'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">20</td><td class="columna-2">Luces de frenado</td><td class="columna-3">{{ $data['Luces de frenado'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">21</td><td class="columna-2">Luces de altura</td><td class="columna-3">{{ $data['Luces de altura'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">22</td><td class="columna-2">Líneas eléctricas sujetas</td><td class="columna-3">{{ $data['Lineas electricas sujetas'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">23</td><td class="columna-2">Banderolas</td><td class="columna-3">{{ $data['Banderolas'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">24</td><td class="columna-2">Número económico</td><td class="columna-3">{{ $data['Numero economico'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">25</td><td class="columna-2">Texto: 'Precaución Transporta Material Peligroso'</td><td class="columna-3">{{ $data["Texto: 'Precaucion Transporta Material Peligroso'"] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">26</td><td class="columna-2">Texto 'Precaución doble semi remolque'</td><td class="columna-3">{{ $data["Texto 'Precaucion doble semi remolque'"] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">27</td><td class="columna-2">Defensa (sujeción)</td><td class="columna-3">{{ $data['Defensa (sujecion)'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">28</td><td class="columna-2">Placa metálica (porta rombos)</td><td class="columna-3">{{ $data['Placa metalica (porta rombos) Posterior'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">29</td><td class="columna-2">Placa de identificación vehicular</td><td class="columna-3">{{ $data['Placa de identificacion vehicular'] }}</td><td class="columna-4"></td>
            </tr>
          </table>
          <table>
            <tr>
              <th>N°</th>
              <th>Revisión parte superior del remolque</th>
              <th>Cumple</th>
              <th>Obvservaciones</th>
            </tr>
            <tr>
              <td class="columna-1">30</td><td class="columna-2">Domo (sin fugas)</td><td class="columna-3">{{ $data['Domo (sin fugas)'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">31</td><td class="columna-2">Sensor óptico (protegido)</td><td class="columna-3">{{ $data['Sensor optico (protegido)'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">32</td><td class="columna-2">Válvula de desfogue (presión y vacío)</td><td class="columna-3">{{ $data['Valvula de desfogue (presion y vacio)'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">33</td><td class="columna-2">Nice sin alteraciones</td><td class="columna-3">{{ $data['Nice sin alteraciones'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">34</td><td class="columna-2">Tapas protectoras del domo</td><td class="columna-3">{{ $data['Tapas protectoras del domo'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">35</td><td class="columna-2">Sello electrónico de domo</td><td class="columna-3">{{ $data['Sello electronico de domo'] }}</td><td class="columna-4"></td>
            </tr>
          </table>
          <table>
            <tr>
              <th>N°</th>
              <th>Revisión parte inferior del remolque</th>
              <th>Cumple</th>
              <th>Obvservaciones</th>
            </tr>
            <tr>
              <td class="columna-1">36</td><td class="columna-2">Conexiones a quinta rueda</td><td class="columna-3">{{ $data['Conexiones a quinta rueda'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">37</td><td class="columna-2">Patines</td><td class="columna-3">{{ $data['Patines Inferior'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">38</td><td class="columna-2">Llanta de refacción</td><td class="columna-3">{{ $data['Llanta de refaccion'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">39</td><td class="columna-2">Válvulas de desfogue</td><td class="columna-3">{{ $data['Valvulas de desfogue'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">40</td><td class="columna-2">Bolsas de aire</td><td class="columna-3">{{ $data['Bolsas de aire'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">41</td><td class="columna-2">Suspensión</td><td class="columna-3">{{ $data['Suspension'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">42</td><td class="columna-2">Sistema de frenos</td><td class="columna-3">{{ $data['Sistema de frenos'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">43</td><td class="columna-2">Líneas eléctricas sujetas</td><td class="columna-3">{{ $data['Lineas electricas sujetas Inferior'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">44</td><td class="columna-2">Líneas de aire sin fugas</td><td class="columna-3">{{ $data['Lineas de aire sin fugas'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">45</td><td class="columna-2">Llantas (Surco no menos de 3 mm.)</td><td class="columna-3">{{ $data['Llantas (Surco no menos de 3 mm.)'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">46</td><td class="columna-2">Rines sin fisuras ({{ $data['Type Rin'] }})</td><td class="columna-3">{{ $data['Rines sin fisuras'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">47</td><td class="columna-2">Birlos completos y sin fisuras</td><td class="columna-3">{{ $data['Birlos completos y sin fisuras'] }}</td><td class="columna-4"></td>
            </tr>
            <tr>
              <td class="columna-1">48</td><td class="columna-2">Baleros (Sin Fuga)</td><td class="columna-3">{{ $data['Baleros (Sin Fuga)'] }}</td><td class="columna-4"></td>
            </tr>
          </table>
      </div> 
      <div style="clear: both;"></div>
        <div><br>
          <table style="width: 100%; text-align: center;">            
            <tr>
              <td class="signature">
                {{ $data['auxiliar']->name ?? ' ' }} {{ $data['auxiliar']->a_paterno ?? ' ' }} {{ $data['auxiliar']->a_materno ?? ' ' }}
              </td>
              <td class="signature"><br>
                {{ $data['operator']->name }} {{ $data['operator']->a_paterno }} {{ $data['operator']->a_materno }}<br>
              </td>
              <td>{{ $data['observation'] ?? ' ' }}</td>>
            </tr>
            <tr>
              <th>AUXILIAR DE MANTENIMIENTO</th>
              <th>OPERADOR</th>
              <th>OBSERVACIONES</th>
            </tr>
          </table>
        </div>     
    </main>
  </body>
</html>
