<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-05-20 R1 CONDICIONES FISICO MECANICAS DE AUTOBUSES</title>
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
        margin-bottom: 3px;
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
        <p class="title">TITULO DEL ARCHIVO</p>
        <h2>ÁREA:  PERIODICIDAD: CUANDO SE REQUIERA RESGUARDO: REVISIÓN: </h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1">
          FECHA: _____{{ $fecha }}______    VEHICULO: ______{{ $data['unit']['no_economic'] }}_______
        </div>
        <table class="column-2-2">
          <tr><th>FOLIO</th></tr>
          <tr><td class="blueTitle"></td></tr>
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
              <td>25</td><td>Fijacion de asientos de la unidad</td><td>{{ $data['Fijacion de asientos'] }}</td>
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
              <td>29</td><td>Chapas de las puesrtas</td><td></td>
            </tr>
            <tr>
              <td>30</td><td>Escalón de acceso</td><td></td>
            </tr>
            <tr>
              <td>31</td><td>Tanque de combustible si fugas</td><td></td>
            </tr>
            <tr>
              <td>32</td><td>Tapón de tanque de combustible</td><td></td>
            </tr>
            <tr>
              <td>33</td><td>Tanque de urea sin fuga</td><td></td>
            </tr>
            <tr>
              <td>34</td><td>Tapa de baterias</td><td></td>
            </tr>
            <tr>
              <td>35</td><td>Guarda equipaje</td><td></td>
            </tr>
            <tr>
              <td>36</td><td>Tanque y conexiones de sistema Ansul</td><td></td>
            </tr>
            <tr>
              <td>37</td><td>Baterias (sin fugas, terminales, fijas)</td><td></td>
            </tr>
            <tr>
              <td>38</td><td>Valvula en batería de sistema Ansul</td><td></td>
            </tr>
            <tr>
              <td>39</td><td>Luces de advertencia laterales</td><td></td>
            </tr>
            <tr>
              <td>40</td><td>Reflejantes</td><td></td>
            </tr>
            <tr>
              <td>41</td><td>Razón social y número de equipo</td><td></td>
            </tr>
            <tr>
              <td>42</td><td>Telefono de emergencia</td><td></td>
            </tr>
            <tr>
              <td>43</td><td>Cristales laterales</td><td></td>
            </tr>
          </table>
          <table style="width: 96%;">
            <tr>
              <th>N°</th>
              <th>Revisión cabina frontal de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>61</td><td>Defensa (sujecion)</td><td></td>
            </tr>
            <tr>
              <td>62</td><td>Placa de identificación vehícular</td><td></td>
            </tr>
            <tr>
              <td>63</td><td>Faros principales (color y funcionamiento)</td><td></td>
            </tr>
            <tr>
              <td>64</td><td>Luces direccionales</td><td></td>
            </tr>
            <tr>
              <td>66</td><td>Luces de advertencia</td><td></td>
            </tr>
            <tr>
              <td>66</td><td>Luces de altura</td><td></td>
            </tr>
            <tr>
              <td>67</td><td>Torreta</td><td></td>
            </tr>
            <tr>
              <td>68</td><td>Llanta (No renovadas surco no menos de 3 mm)</td><td></td>
            </tr>
            <tr>
              <td>69</td><td>Rines sin fisuras</td><td></td>
            </tr>
            <tr>
              <td>70</td><td>Birlos completos y sin fisuras</td><td></td>
            </tr>
            <tr>
              <td>71</td><td>Guardafangos (Loderas)</td><td></td>
            </tr>
            <tr>
              <td>72</td><td>Espejos Cóncavos (I. D.)</td><td></td>
            </tr>
            <tr>
              <td>73</td><td>Baleros (Sin Fuga)</td><td></td>
            </tr>
            <tr>
              <td>74</td><td>Sistema de auto inflado</td><td></td>
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
              <td>44</td><td>Frenos (sin fuga de aire)</td><td></td>
            </tr>
            <tr>
              <td>45</td><td>Muelle (sin hojas sueltas, rotas o fisuras)</td><td></td>
            </tr>
            <tr>
              <td>46</td><td>Brazo viajero</td><td></td>
            </tr>
            <tr>
              <td>47</td><td>Chasis sin fisuras</td><td></td>
            </tr>
            <tr>
              <td>48</td><td>Lineas de aire (sin fugas de aire)</td><td></td>
            </tr>
            <tr>
              <td>49</td><td>Lineas electricas sujetas</td><td></td>
            </tr>
            <tr>
              <td>50</td><td>Diferenciales (sin fugas)</td><td></td>
            </tr>
            <tr>
              <td>51</td><td>Transmisión (sin fugas)</td><td></td>
            </tr>
            <tr>
              <td>52</td><td>Crucetas cardan</td><td></td>
            </tr>
            <tr>
              <td>53</td><td>Motor sin fugas</td><td></td>
            </tr>
            <tr>
              <td>54</td><td>Alarma reversa</td><td></td>
            </tr>
            <tr>
              <td>55</td><td>Radiador</td><td></td>
            </tr>
            <tr>
              <td>56</td><td>Bandas</td><td></td>
            </tr>
            <tr>
              <td>57</td><td>Llanta de refacción</td><td></td>
            </tr>
            <tr>
              <td>58</td><td>Drene de W.C.</td><td></td>
            </tr>
            <tr>
              <td>59</td><td>Bolsas de aire din fuga</td><td></td>
            </tr>
            <tr>
              <td>60</td><td>Amortiguadores sin fuga</td><td></td>
            </tr>
          </table> 
          <table style="width: 97%;" class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión cabina posterior de la unidad</th>
              <th>Cumple</th>
            </tr>
              <td>78</td><td>Luces direccionales</td><td></td>
            </tr>
            <tr>
              <td>79</td><td>Luces de advertencia</td><td></td>
            </tr>
            <tr>
              <td>80</td><td>Luces de banderola</td><td></td>
            </tr>
            <tr>
              <td>81</td><td>Luces de estacionamineto</td><td></td>
            </tr>
            <tr>
              <td>82</td><td>Luces de frenado</td><td></td>
            </tr>
            <tr>
              <td>83</td><td>Luces de marcha atras</td><td></td>
            </tr>
            <tr>
              <td>84</td><td>Reflejantes</td><td></td>
            </tr>
            <tr>
              <td>85</td><td>Guardafangos (Loderas)</td><td></td>
            </tr>
            <tr>
              <td>86</td><td>Defensa (sujeción)</td><td></td>
            </tr>
            <tr>
              <td>87</td><td>Placa de identificación vehícular</td><td></td>
            </tr>
            </tr>
              <td>88</td><td>Escape</td><td></td>
            </tr>
            <tr>
              <td>89</td><td>Llantas (surco no menos de 3 mm)</td><td></td>
            </tr>
            <tr>
              <td>90</td><td>Rines sin fisuras</td><td></td>
            </tr>
            <tr>
              <td>91</td><td>Birlos completos y sin fisuras</td><td></td>
            </tr>
            <tr>
              <td>92</td><td>Baleros (sin fuga)</td><td></td>
            </tr>
            <tr>
              <td>93</td><td></td><td></td>
            </tr>
            <tr>
              <td>94</td><td></td><td></td>
            </tr>
            <tr>
              <td>95</td><td></td><td></td>
            </tr>
          </table> 
          <table style="width: 97%;" class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión motor de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>96</td><td>Puertas de acceso a motor</td><td></td>
            </tr>
            <tr>
              <td>97</td><td>Válvulas mangueras y conexiones de sistema Ansul</td><td></td>
            </tr>
            <tr>
              <td>98</td><td>Ventilador de radiador</td><td></td>
            </tr>
            <tr>
              <td>99</td><td>Válvulas de aire sin fugas</td><td></td>
            </tr>
            <tr>
              <td>100</td><td>Bandas de motor sin daño</td><td></td>
            </tr>
            <tr>
              <td>101</td><td>Turbo sin fuga</td><td></td>
            </tr>
            <tr>
              <td>102</td><td>Mangueras de admision sin abrazaderas flojas</td><td></td>
            </tr>
            <tr>
              <td>103</td><td>Nivel de refrigerante</td><td></td>
            </tr>
            <tr>
              <td>104</td><td>Nivel de aceite de motor</td><td></td>
            </tr>
            <tr>
              <td>105</td><td>Lineas de agua sin fuga</td><td></td>
            </tr>
            <tr>
              <td>106</td><td>Lineas de clima sin fuga</td><td></td>
            </tr>
            <tr>
              <td>107</td><td>Lineas de agua W.C. sin fuga</td><td></td>
            </tr>
            <tr>
              <td>108</td><td>Tanque de agua W.C. sin fuga</td><td></td>
            </tr>
            <tr>
              <td>109</td><td>Lineas eléctricas fijas</td><td></td>
            </tr>
            <tr>
              <td>110</td><td>Parte posterior de motor sin fuga</td><td></td>
            </tr>
            <tr>
              <td>111</td><td>Nivel de aceite de direcciones</td><td></td>
            </tr>
            <tr>
              <td>112</td><td>Lineas de urea sin fugas</td><td></td>
            </tr>
            <tr>
              <td>113</td><td>Bomba de agua sin fuga</td><td></td>
            </tr>
            <tr>
              <td>114</td><td>Llave de aire auto inflado en posición</td><td></td>
            </tr>
            <tr>
              <td>115</td><td></td><td></td>
            </tr>
            <tr>
              <td>116</td><td></td><td></td>
            </tr>
            <tr>
              <td>117</td><td></td><td></td>
            </tr>
            <tr>
              <td>118</td><td></td><td></td>
            </tr>
            <tr>
              <td>119</td><td></td><td></td>
            </tr>
          </table>       
        </div>
      </div>      
    </main>
  </body>
</html>
