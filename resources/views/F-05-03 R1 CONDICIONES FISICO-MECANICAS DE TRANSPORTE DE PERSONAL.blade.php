<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-05-03 R1 CONDICIONES FISICO-MECANICAS DE TRANSPORTE DE PERSONAL</title>
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

      .column-50 {
        width: 50%;
        float: left;
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
        font-size: 0.9em;
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
        <p class="title">REVISIÓN DE CONDICIONES FISICO-MECÁNICAS DE TRANSPORTE DE PERSONAL</p>
        <h2>ÁREA: MANTENIMIENTO   F-05-03 R1  PERIODICIDAD: DIARIAMENTE   RESGUARDO: 3 AÑOS  REVISIÓN: OCTUBRE 2020</h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1">
          FECHA: __{{ $fecha }}__   OPERADOR: _______________________   VEHICULO: __{{ $data['unit']['no_economic'] }}__
        </div>
        <table class="column-2-2">
          <tr><th>Odometro</th></tr>
          <tr><td class="blueTitle">{{ $data['odometro'] }}</td></tr>
        </table>
      </div>     
      <div style="clear: both;"></div>
      <!-- Primera fila con dos columnas -->
      <div class="row">
        <!-- Columna 1 -->
        <div class="column-50">
          <table style="width: 96%;">
            <tr>
              <th>N°</th>
              <th>Revisión cabina interna de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>1</td><td>Luces interiortes</td><td>{{ $data['Luces interiores'] }}</td>
            </tr>
            <tr>
              <td>2</td><td>Luces indicador de advertencia</td><td>{{ $data['Luces indicador de advertencia'] }}</td>
            </tr>
            <tr>
              <td>3</td><td>Luces indicador de direccionales</td><td>{{ $data['Luces indicador de direccionales'] }}</td>
            </tr>
            <tr>
              <td>4</td><td>Luces indicador de bateria</td><td>{{ $data['Luces indicador de bateria'] }}</td>
            </tr>
            <tr>
              <td>5</td><td>Funcionamiento de aire acondicionado</td><td>{{ $data['Funcionamiento de aire acondicionado'] }}</td>
            </tr>
            <tr>
              <td>6</td><td>Indicador de presion de aceite</td><td>{{ $data['Indicador de presion de aceite'] }}</td>
            </tr>
            <tr>
              <td>7</td><td>Indicador de presion de aire vacio</td><td>{{ $data['Indicador de presion de aire vacio'] }}</td>
            </tr>
            <tr>
              <td>8</td><td>Dispositivo de advirtencia de aire vacio</td><td>{{ $data['Dispositivo de advertencia de aire vacio'] }}</td>
            </tr>
            <tr>
              <td>9</td><td>Velocimetro</td><td>{{ $data['Velocimetro'] }}</td>
            </tr>
            <tr>
              <td>10</td><td>Claxon</td><td>{{ $data['Claxon o corneta'] }}</td>
            </tr>
            <tr>
              <td>11</td><td>Cinturones de seguridad</td><td>{{ $data['Cinturon de seguridad'] }}</td>
            </tr>
            <tr>
              <td>12</td><td>Actuador de sistema Ansul</td><td>{{ $data['Actuador de sistema Ansul'] }}</td>
            </tr>
            <tr>
              <td>13</td><td>Puertas de acceso</td><td>{{ $data['Puertas de acceso'] }}</td>
            </tr>
            <tr>
              <td>14</td><td>Vidrios y/o Parabrisas</td><td>{{ $data['Vidrios y/o Parabrisas'] }}</td>
            </tr>
            <tr>
              <td>15</td><td>Limipiadores</td><td>{{ $data['Limpiadores'] }}</td>
            </tr>
            <tr>
              <td>16</td><td>Espejo retrovisores (I. D.)</td><td>{{ $data['Espejo retrovisor'] }}</td>
            </tr>
            <tr>
              <td>17</td><td>Volante (Sin juego excesivo)</td><td>{{ $data['Volante (Sin juego excesivo)'] }}</td>
            </tr>
            <tr>
              <td>18</td><td>Freno de pie</td><td>{{ $data['Freno de pie'] }}</td>
            </tr>
            <tr>
              <td>19</td><td>Freno retardador</td><td>{{ $data['Freno de retardador'] }}</td>
            </tr>
            <tr>
              <td>20</td><td>Frenado de motor</td><td>{{ $data['Frenado de motor'] }}</td>
            </tr>
            <tr>
              <td>21</td><td>Freno electromagnetico</td><td>{{ $data['Freno electromagnetico'] }}</td>
            </tr>
            <tr>
              <td>22</td><td>Switch de marcha</td><td>{{ $data['Switch de marcha'] }}</td>
            </tr>
            <tr>
              <td>23</td><td>Control de acceso</td><td>{{ $data['Control de acceso'] }}</td>
            </tr>
            <tr>
              <td>24</td><td>Funcionamiento W.C.</td><td>{{ $data['Funcionamiento de W.C'] }}</td>
            </tr>
            <tr>
              <td>25</td><td>Fijacion de asientos</td><td>{{ $data['Fijacion de asientos'] }}</td>
            </tr>
            <tr>
              <td>26</td><td>Tapiceria</td><td>{{ $data['Tapiceria'] }}</td>
            </tr>
            <tr>
              <td>27</td><td>Martillos rompe cristales completos</td><td>{{ $data['Martillos rompe cristales completos'] }}</td>
            </tr>
            <tr>
              <td>28</td><td>Accesorios sistema GUARDIAN</td><td>{{ $data['Accesorios sistema GUARDIAN'] }}</td>
            </tr>
            <tr>
              <td>29</td><td>Limpieza de unidad</td><td>{{ $data['Limpieza de unidad'] }}</td>
            </tr>
            <tr>
              <td>30</td><td>Botiquin de Primeros Auxilios</td><td>{{ $data['Botiquin de Primeros Auxilios'] }}</td>
            </tr>
            <tr>
              <td>31</td><td>Extintores</td><td>{{ $data['Extintores'] }}</td>
            </tr>
          </table>
          <table style="width: 96%;">
            <tr>
              <th>N°</th>
              <th>Revisión cabina izq y derecho de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>32</td><td>Chapas de las puertas</td><td>{{ $data['Chapas de las puertas'] }}</td>
            </tr>
            <tr>
              <td>33</td><td>Escalón de acceso</td><td>{{ $data['Escalon de acceso'] }}</td>
            </tr>
            <tr>
              <td>34</td><td>Tanque de combustible si fugas</td><td>{{ $data['Tanque de combustible sin fugas'] }}</td>
            </tr>
            <tr>
              <td>35</td><td>Tapón de tanque de combustible</td><td>{{ $data['Tapon de tanque de combustible'] }}</td>
            </tr>
            <tr>
              <td>36</td><td>Tanque de urea sin fuga</td><td>{{ $data['Tanque de urea sin fuga'] }}</td>
            </tr>
            <tr>
              <td>37</td><td>Tapa de baterias</td><td>{{ $data['Tapa de las baterias'] }}</td>
            </tr>
            <tr>
              <td>38</td><td>Guarda equipaje</td><td>{{ $data['Guarda equipaje'] }}</td>
            </tr>
            <tr>
              <td>39</td><td>Tanque y conexiones de sistema Ansul</td><td>{{ $data['Tanques y conexiones de sistema Ansul'] }}</td>
            </tr>
            <tr>
              <td>40</td><td>Baterias (sin fugas, terminales, fijas)</td><td>{{ $data['Baterias (sin fugas, terminales, fijas y con tapa)'] }}</td>
            </tr>
            <tr>
              <td>41</td><td>Valvula en batería de sistema Ansul</td><td>{{ $data['Valvulas mangueras y conexiones de sistema Ansul'] }}</td>
            </tr>
            <tr>
              <td>42</td><td>Luces de advertencia laterales</td><td>{{ $data['Luces de advertencia laterales'] }}</td>
            </tr>
            <tr>
              <td>43</td><td>Reflejantes</td><td>{{ $data['Reflejantes'] }}</td>
            </tr>
            <tr>
              <td>44</td><td>Razón social y número de equipo</td><td>{{ $data['Razon social y numero de equipo'] }}</td>
            </tr>
            <tr>
              <td>45</td><td>Telefono de emergencia</td><td>{{ $data['Telefono de emergencia'] }}</td>
            </tr>
            <tr>
              <td>46</td><td>Espejos y/o Cristales laterales</td><td>{{ $data['Espejos y/o cristales laterales'] }}</td>
            </tr>
            <tr>
              <td>47</td><td>Cuñas de bloqueo para ruedas</td><td>{{ $data['Cuñas de bloqueo para ruedas'] }}</td>
            </tr>
          </table>          
        </div>
        <!-- Columna 2 -->
        <div class="column-50">
          <table style="width: 97%;"  class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión parte inferior de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>48</td><td>Frenos (sin fuga de aire)</td><td>{{ $data['Frenos (sin fuga de aire)'] }}</td>
            </tr>
            <tr>
              <td>49</td><td>Muelle (sin hojas sueltas, rotas o fisuras)</td><td>{{ $data['Muelles (Suspension sin hojas sueltas, rotas o fisuras)'] }}</td>
            </tr>
            <tr>
              <td>50</td><td>Lineas de aire (sin fugas de aire)</td><td>{{ $data['Lineas de aire (sin fuga de aire) Inferior'] }}</td>
            </tr>
            <tr>
              <td>51</td><td>Alarma Reversa</td><td>{{ $data['Alarma Reversa'] }}</td>
            </tr>
            <tr>
              <td>52</td><td>Radiador</td><td>{{ $data['Radiador'] }}</td>
            </tr>   
            <tr>
              <td>53</td><td>Llanta de refacción</td><td>{{ $data['Llanta de refaccion'] }}</td>
            </tr>
            <tr>
              <td>54</td><td>Bolsas de aire sin fuga</td><td>{{ $data['Bolsas de aire sin fuga'] }}</td>
            </tr>
          </table> 
          <table style="width: 97%;" class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión cabina frontal de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>55</td><td>Defensa (sujecion)</td><td>{{ $data['Defensa (sujecion) Frontal'] }}</td>
            </tr>
            <tr>
              <td>56</td><td>Placa de identificación vehícular</td><td>{{ $data['Placa de identificacion vehicular Frontal'] }}</td>
            </tr>
            <tr>
              <td>57</td><td>Faros principales (color y funcionamiento)</td><td>{{ $data['Faros principales (Color y funcionamiento)'] }}</td>
            </tr>
            <tr>
              <td>58</td><td>Luces direccionales</td><td>{{ $data['Luces direccionales Frontal'] }}</td>
            </tr>
            <tr>
              <td>59</td><td>Luces de advertencia</td><td>{{ $data['Luces de advertencia Frontal'] }}</td>
            </tr>
            <tr>
              <td>60</td><td>Luces de altura</td><td>{{ $data['Luces de altura'] }}</td>
            </tr>
            <tr>
              <td>61</td><td>Torreta</td><td>{{ $data['Torreta'] }}</td>
            </tr>
            <tr>
              <td>62</td><td>Llanta (No renovadas surco no menos de 3 mm)</td><td>{{ $data['Llantas (No renovadas Surco no menos de 3 mm.)'] }}</td>
            </tr>
            <tr>
              <td>63</td><td>Rines sin fisuras</td><td>{{ $data['Rines sin fisuras Frontal'] }}</td>
            </tr>
            <tr>
              <td>64</td><td>Birlos completos y sin fisuras</td><td>{{ $data['Birlos completos y sin fisuras Frontal'] }}</td>
            </tr>
            <tr>
              <td>65</td><td>Espejos laterales (I. D.)</td><td>{{ $data['Espejos laterales (I. D.)'] }}</td>
            </tr>
            <tr>
              <td>66</td><td>Baleros (Sin Fuga)</td><td>{{ $data['Baleros (Sin fuga) Frontal'] }}</td>
            </tr>
            <tr>
              <td>67</td><td>Sistema de auto inflado</td><td>{{ $data['Sistema de auto inflado'] }}</td>
            </tr>
          </table>
          <table style="width: 97%;" class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión cabina posterior de la unidad</th>
              <th>Cumple</th>
            </tr>
              <td>68</td><td>Luces direccionales</td><td>{{ $data['Luces direccionales'] }}</td>
            </tr>
            <tr>
              <td>69</td><td>Luces de advertencia</td><td>{{ $data['Luces de advertencia'] }}</td>
            </tr>
            <tr>
              <td>70</td><td>Luces de banderola</td><td>{{ $data['Luces de banderola'] }}</td>
            </tr>
            <tr>
              <td>71</td><td>Luces de estacionamineto</td><td>{{ $data['Luces de estacionamiento'] }}</td>
            </tr>
            <tr>
              <td>72</td><td>Luces de frenado</td><td>{{ $data['Luces de frenado'] }}</td>
            </tr>
            <tr>
              <td>73</td><td>Luces de marcha atras</td><td>{{ $data['Luces de marcha atras'] }}</td>
            </tr>
            <tr>
              <td>74</td><td>Reflejantes</td><td>{{ $data['Reflejantes'] }}</td>
            </tr>
            <tr>
              <td>75</td><td>Guardafangos (Loderas)</td><td>{{ $data['Guardafangos (Loderas)'] }}</td>
            </tr>
            <tr>
              <td>76</td><td>Defensa (sujeción)</td><td>{{ $data['Defensa (sujecion)'] }}</td>
            </tr>
            <tr>
              <td>77</td><td>Placa de identificación vehícular</td><td>{{ $data['Placa de identificacion vehicular'] }}</td>
            </tr>
            </tr>
              <td>78</td><td>Escape</td><td>{{ $data['Escape'] }}</td>
            </tr>
            <tr>
              <td>79</td><td>Llantas (surco no menos de 3 mm)</td><td>{{ $data['Llantas (Surco no menos de 3 mm.)'] }}</td>
            </tr>
            <tr>
              <td>80</td><td>Rines sin fisuras</td><td>{{ $data['Rines sin fisuras'] }}</td>
            </tr>
            <tr>
              <td>81</td><td>Birlos completos y sin fisuras</td><td>{{ $data['Birlos completos y sin fisuras'] }}</td>
            </tr>
            <tr>
              <td>82</td><td>Baleros (sin fuga)</td><td>{{ $data['Baleros (Sin Fuga)'] }}</td>
            </tr>
          </table> 
          <table style="width: 97%;" class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión motor de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>83</td><td>Puertas de acceso a motor</td><td>{{ $data['Puerta de acceso a motor'] }}</td>
            </tr>
            <tr>
              <td>84</td><td>Válvulas mangueras y conexiones de sistema Ansul</td><td>{{ $data['Valvulas mangueras y conexiones de sistema Ansul'] }}</td>
            </tr>
            <tr>
              <td>85</td><td>Nivel de refrigerante</td><td>{{ $data['Nivel de refrigerante'] }}</td>
            </tr>
            <tr>
              <td>86</td><td>Nivel de aceite de motor</td><td>{{ $data['Nivel de aceite de motor'] }}</td>
            </tr>
            <tr>
              <td>87</td><td>Nivel de aceite de direcciones</td><td>{{ $data['Nivel de aceite de direccion'] }}</td>
            </tr>
            <tr>
            <tr>
              <td>88</td><td></td><td></td>
            </tr>
            <tr>
              <td>89</td><td></td><td></td>
            </tr>
            <tr>
              <td>90</td><td></td><td></td>
            </tr>
          </table>       
        </div>
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
