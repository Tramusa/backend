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
        margin-bottom: 10px;
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
        <p class="title">REVISION DE CONDICIONES FISICO-MECANICAS</p>
        <h2>ÁREA:MANTENIMIENTO F-05-18  PERIODICIDAD:DIARIO RESGUARDO: 3 AÑOS REVISIÓN: ABRIL DE 2021 </h2>
      </div>      
    </header>
    <main>
      <div class="row"><br>
        <div class="column-2-1">FECHA: ___{{ $fecha }}_______  VEHICULO: ____{{ $data['unit']['no_economic'] }}______ VOLTEO:___{{ $data['unit']['no_economic'] }}____</div>
        <table class="column-2-2">
          <tr><th>FOLIO</th></tr>
          <tr><td class="blueTitle"><br></td></tr>
        </table><br>
      </div>     
      <div style="clear: both;"></div>
      <div class="row">        
          <table>
            <tr>
              <th>N°</th>
              <th>Revisión de volteo</th>
              <th>Cumple</th>
              <th>Obvservaciones</th>
            </tr>
            <tr>
              <td>1</td><td>Funcionamiento en cilindro hidráulico</td><td>{{ $data['Funcionamiento  en cilindro hidraulico'] }}</td><td></td>
            </tr>
            <tr>
              <td>2</td><td>Conexiones de aire (sin fuga)</td><td>{{ $data['Conexiones de aire (sin fuga)'] }}</td><td></td>
            </tr>
            <tr>
              <td>3</td><td>Guardafangos (Loderas)</td><td>{{ $data['Guardafangos (Loderas)'] }}</td><td></td>
            </tr>
            <tr>
              <td>4</td><td>Patines</td><td>{{ $data['Patines'] }}</td><td></td>
            </tr>
            <tr>
              <td>5</td><td>Llanta de refacción</td><td>{{ $data['Llanta de refaccion'] }}</td><td></td>
            </tr>
            <tr>
              <td>6</td><td>Válvulas de desfogue</td><td>{{ $data['Valvulas de desfogue'] }}</td><td></td>
            </tr>
            <tr>
              <td>7</td><td>Bolsas de aire</td><td>{{ $data['Bolsas de aire'] }}</td><td></td>
            </tr>
            <tr>
              <td>8</td><td>Sistema de frenos</td><td>{{ $data['Sistema de frenos'] }}</td><td></td>
            </tr>
            <tr>
              <td>9</td><td>Baleros (sin fuga)</td><td>{{ $data['Baleros (Sin Fuga)'] }}</td><td></td>
            </tr>
            <tr>
              <td>10</td><td>Sistema de autoinflado</td><td>{{ $data['Sistema autoinflado'] }}</td><td></td>
            </tr>
            <tr>
              <td>11</td><td>Estado de neumáticos</td><td>{{ $data['Estado de neumaticos'] }}</td><td></td>
            </tr>
            <tr>
              <td>12</td><td>Luces laterales</td><td>{{ $data['Luces laterales'] }}</td><td></td>
            </tr>
            <tr>
              <td>13</td><td>Luces direccionales</td><td>{{ $data['Luces direccionales'] }}</td><td></td>
            </tr>
            <tr>
              <td>14</td><td>Luces de advertencia</td><td>{{ $data['Luces de advertencia'] }}</td><td></td>
            </tr>
            <tr>
              <td>15</td><td>Luces de frenado</td><td>{{ $data['Luces de frenado'] }}</td><td></td>
            </tr>
            <tr>
              <td>16</td><td>Luces de estacionamiento</td><td>{{ $data['Luces de estacionamiento'] }}</td><td></td>
            </tr>
            <tr>
              <td>17</td><td>Funcionamiento de manivela</td><td>{{ $data['Funcionamiento de manivela'] }}</td><td></td>
            </tr>
            <tr>
              <td>18</td><td>Pernos bisagra de volteo</td><td>{{ $data['Pernos bisagra de volteo'] }}</td><td></td>
            </tr>
            <tr>
              <td>19</td><td>Sistema de ganchos para puerta</td><td>{{ $data['Sistema de ganchos para puerta'] }}</td><td></td>
            </tr>
            <tr>
              <td>20</td><td>Seguros de puerta</td><td>{{ $data['Seguros de puerta'] }}</td><td></td>
            </tr>
            <tr>
              <td>21</td><td>Placa de identificación vehicular</td><td>{{ $data['Placa de identificacion vehicular'] }}</td><td></td>
            </tr>
            <tr>
              <td>22</td><td>Banderolas</td><td>{{ $data['Banderolas'] }}</td><td></td>
            </tr>
            <tr>
              <td>23</td><td>Numero económico</td><td>{{ $data['Numero economico'] }}</td><td></td>
            </tr>
            <tr>
              <td>24</td><td>Numero de placa</td><td>{{ $data['Numero de placa'] }}</td><td></td>
            </tr>
            <tr>
              <td>25</td><td>Logotipo de la empresa</td><td>{{ $data['Logotipo de la empresa'] }}</td><td></td>
            </tr>
            <tr>
              <td>26</td><td>Reflejantes</td><td>{{ $data['Reflejantes'] }}</td><td></td>
            </tr>
            <tr>
              <td>27</td><td>Lona en buen estado</td><td>{{ $data['Lona en buen estado'] }}</td><td></td>
            </tr>
            <tr>
              <td>28</td><td>Sistema abatible</td><td>{{ $data['Sistema abatible'] }}</td><td></td>
            </tr>
            <tr>
              <td>29</td><td>Descansos de lona</td><td>{{ $data['Descansos de lona'] }}</td><td></td>
            </tr>
            <tr>
              <td>30</td><td>Caballetes completos</td><td>{{ $data['Caballetes completos'] }}</td><td></td>
            </tr>
            <tr>
              <td>31</td><td>Escalones interior volteo</td><td>{{ $data['Escalones interior volteo'] }}</td><td></td>
            </tr>
            <tr>
              <td>32</td><td>Rines sin fisuras ({{ $data['Type Rin'] }})</td><td>{{ $data['Rines sin fisuras'] }}</td><td></td>
            </tr>
            <tr>
              <td>33</td><td>Birlos completos</td><td>{{ $data['Birlos completos'] }}</td><td></td>
            </tr>
            <tr>
              <td>34</td><td>Masas sin fuga</td><td>{{ $data['Masas sin fuga'] }}</td><td></td>
            </tr>
            <tr>
              <td>35</td><td></td><td></td><td></td>
            </tr>
            <tr>
              <td>36</td><td></td><td></td><td></td>
            </tr>
            <tr>
              <td>37</td><td></td><td></td><td></td>
            </tr>
            <tr>
              <td>38</td><td></td><td></td><td></td>
            </tr>
            <tr>
              <td>39</td><td></td><td></td><td></td>
            </tr>
            <tr>
              <td>40</td><td></td><td></td><td></td>
            </tr>
          </table>
      </div>
      <div style="clear: both;"></div>
        <div><br><br><br><br><br><br><br>
          <table style="width: 100%;">            
            <tr>
              <td><br><br><br><br><br></td><td></td><td></td>
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
