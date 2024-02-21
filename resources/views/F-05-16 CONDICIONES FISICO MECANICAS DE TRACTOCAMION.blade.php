<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-05-16 CONDICIONES FISICO MECANICAS DE TRACTOCAMION</title>
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
        margin-bottom: 6px;
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
      .espacio-derecha {
        margin-left: 10px; /* Puedes ajustar este valor según sea necesario */
      }

      .signature {
        font-family: 'Courier New', monospace; /* Una fuente cursiva disponible en la mayoría de los sistemas */
        font-style: italic;
        font-weight: bold;
        font-size: 13px;
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
        <p class="title">REVISION DE CONDICIONES FISICO MECANICAS DE TRACTOCAMION</p>
        <h2>AREA: MANTENIMIENTO F-05-16 PERIODICIDAD: DIARIO  RESGUARDO: 3 AÑOS REVISION: MARZO 2021 </h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1">FECHA: ____{{ $fecha }}______  VEHICULO: ___{{ $data['unit']->no_economic }}_______   </div>
        <table class="column-2-2">        
          <tr><th>Odometro</th></tr>
          <tr><td class="blueTitle">{{ $data['odometro'] }}</td></tr>
        </table>
      </div>     
      <div style="clear: both;"></div>
      <div class="row">
        <!-- Columna 1 -->
        <div class="column-50">
          <table>
            <tr>
              <th>N°</th>
              <th>Revision cabina interna de tractocamion</th>
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
              <td>10</td><td>Claxon o corneta</td><td>{{ $data['Claxon o corneta'] }}</td>
            </tr>
            <tr>
              <td>11</td><td>Cinturon de seguridad</td><td>{{ $data['Cinturon de seguridad'] }}</td>
            </tr>
            <tr>
              <td>12</td><td>Seguros y vidios de puertas (I. D.)</td><td>{{ $data['Seguros y vidrios de puertas (I. D.)'] }}</td>
            </tr>
            <tr>
              <td>13</td><td>Parabrisas</td><td>{{ $data['Seguros y vidrios de puertas (I. D.)'] }}</td>
            </tr>
            <tr>
              <td>14</td><td>Limipiadores</td><td>{{ $data['Limpiadores'] }}</td>
            </tr>
            <tr>
              <td>15</td><td>Espejo retrovisores (I. D.)</td><td>{{ $data['Espejo retrovisores (I. D.)'] }}</td>
            </tr>
            <tr>
              <td>16</td><td>Volante (Sin juego excesivo)</td><td>{{ $data['Volante (Sin juego excesivo)'] }}</td>
            </tr>
            <tr>
              <td>17</td><td>Freno de pie</td><td>{{ $data['Freno de pie'] }}</td>
            </tr>
            <tr>
              <td>18</td><td>Freno de emergencia</td><td>{{ $data['Freno de emergencia'] }}</td>
            </tr>
            <tr>
              <td>19</td><td>Frenado de motor</td><td>{{ $data['Frenado de motor'] }}</td>
            </tr>
            <tr>
              <td>20</td><td>Interruptor de corta corriente</td><td>{{ $data['Interruptor de corta corriente'] }}</td>
            </tr>
            <tr>
              <td>21</td><td>Switch de marcha</td><td>{{ $data['Switch de marcha'] }}</td>
            </tr>
            <tr>
              <td>22</td><td>Funcionamiento de convertidor y selector de velocidades</td><td>{{ $data['Funcionamiento de Convertidor y selector de velocidades'] }}</td>
            </tr>
            <tr>
              <td>23</td><td>Auto estereo</td><td>{{ $data['Auto estereo'] }}</td>
            </tr>
            <tr>
              <td>24</td><td>Tapiceria</td><td>{{ $data['Tapiceria'] }}</td>
            </tr>
          </table>
          <table>
            <tr>
              <th>N°</th>
              <th>Revision cabina izq y derecho de tractocamion</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>25</td><td>Escalon</td><td>{{ $data['Escalon'] }}</td>
            </tr>
            <tr>
              <td>26</td><td>Tanque de combustible si fugas</td><td>{{ $data['Tanque de combustible sin fugas'] }}</td>
            </tr>
            <tr>
              <td>27</td><td>Tapon de tanque de combustible</td><td>{{ $data['Tapon de tanque de combustible'] }}</td>
            </tr>
            <tr>
              <td>28</td><td>Tapa de baterias</td><td>{{ $data['Tapa de las baterias'] }}</td>
            </tr>
            <tr>
              <td>29</td><td>Baterias (sin fugas, terminales, fijas y con tapa)</td><td>{{ $data['Baterias (sin fugas, terminales, fijas y con tapa)'] }}</td>
            </tr>
            <tr>
              <td>30</td><td>Luces de advertencia laterales</td><td>{{ $data['Luces de advertencia laterales'] }}</td>
            </tr>
            <tr>
              <td>31</td><td>Reflejantes</td><td>{{ $data['Reflejantes'] }}</td>
            </tr>
            <tr>
              <td>32</td><td>Razon social y número de equipo</td><td>{{ $data['Razon social y numero de equipo'] }}</td>
            </tr>
            <tr>
              <td>33</td><td>Telefono de emergencia</td><td>{{ $data['Telefono de emergencia'] }}</td>
            </tr>
            <tr>
              <td>34</td><td>Antenas de CV ({{ $data['No Antenas'] }} pz)</td><td>{{ $data['Antenas de CV'] }}</td>
            </tr>
            <tr>
              <td>35</td><td>Imagen</td><td>{{ $data['Imagen'] }}</td>
            </tr>
          </table>
          <table>
            <tr>
              <th>N°</th>
              <th>Revision parte inferior de tractocamion</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>36</td><td>Frenos (sin fuga de aire)</td><td>{{ $data['Frenos (sin fuga de aire)'] }}</td>
            </tr>
            <tr>
              <td>37</td><td>Muelle (suspension sin hojas sueltas, rotas o fisuras)</td><td>{{ $data['Muelles (Suspension sin hojas sueltas, rotas o fisuras)'] }}</td>
            </tr>
            <tr>
              <td>38</td><td>Chasis sin fisuras</td><td>{{ $data['Chasis sin fisuras'] }}</td>
            </tr>
            <tr>
              <td>39</td><td>Lineas de aire</td><td>{{ $data['Lineas de aire Inferior'] }}</td>
            </tr>
            <tr>
              <td>40</td><td>Lineas electricas sujetas</td><td>{{ $data['Lineas electricas sujetas'] }}</td>
            </tr>
            <tr>
              <td>41</td><td>Diferenciales (sin fugas)</td><td>{{ $data['Diferenciales (sin fugas)'] }}</td>
            </tr>
            <tr>
              <td>42</td><td>Transmision (sin fugas)</td><td>{{ $data['Transmision (sin fugas)'] }}</td>
            </tr>
            <tr>
              <td>43</td><td>Motor sin fugas</td><td>{{ $data['Motor sin fugas'] }}</td>
            </tr>
            <tr>
              <td>44</td><td>Alarma reversa</td><td>{{ $data['Alarma Reversa'] }}</td>
            </tr>
            <tr>
              <td>45</td><td>Radiador</td><td>{{ $data['Radiador'] }}</td>
            </tr>
            <tr>
              <td>46</td><td>Bandas</td><td>{{ $data['Bandas'] }}</td>
            </tr>
            <tr>
              <td>47</td><td>Bolsas de aire sin fuga</td><td>{{ $data['Bolsas de aire sin fuga'] }}</td>
            </tr>
            <tr>
              <td>48</td><td>Amortiguadores sin fuga</td><td>{{ $data['Amortiguadores sin fuga'] }}</td>
            </tr>
            <tr>
              <td>49</td><td>Crucetas y soporte cardan</td><td>{{ $data['Crucetas y soporte cardan'] }}</td>
            </tr>
            <tr>
              <td>50</td><td>Tirantes, soportes y bolsas de aire cabina</td><td>{{ $data['Trirantes,soportes y bolsas de aire cabina'] }}</td>
            </tr>
          </table>
        </div>
        <!-- Columna 2 -->
        <div class="column-50">
          <table class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revision cabina frontal de tractocamion</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>51</td><td>Defensa (sujecion)</td><td>{{ $data['Defensa (sujecion) Frontal'] }}</td>
            </tr>
            <tr>
              <td>52</td><td>Placa de identificacion vehicular</td><td>{{ $data['Placa de identificacion vehicular Frontal'] }}</td>
            </tr>
            <tr>
              <td>53</td><td>Faros principales (Color y funcionamiento)</td><td>{{ $data['Faros principales (Color y funcionamiento)'] }}</td>
            </tr>
            <tr>
              <td>54</td><td>Luces direccionales</td><td>{{ $data['Luces direccionales Frontal'] }}</td>
            </tr>
            <tr>
              <td>55</td><td>Luces de advertencia</td><td>{{ $data['Luces de advertencia Frontal'] }}</td>
            </tr>
            <tr>
              <td>56</td><td>Luces de altura</td><td>{{ $data['Luces de altura'] }}</td>
            </tr>
            <tr>
              <td>57</td><td>Llanta (No renovadas surco no menos de 3 mm)</td><td>{{ $data['Llantas (No renovadas Surco no menos de 3 mm.) Frontal'] }}</td>
            </tr>
            <tr>
              <td>58</td><td>Rines sin fisuras ({{ $data['Type Rin Frontal'] }})</td><td>{{ $data['Rines sin fisuras  Frontal'] }}</td>
            </tr>
            <tr>
              <td>59</td><td>Birlos completos y sin fisuras</td><td>{{ $data['Birlos completos y sin fisuras Frontal'] }}</td>
            </tr>
            <tr>
              <td>60</td><td>Guardafangos (Loderas)</td><td>{{ $data['Guardafangos (Loderas) Frontal'] }}</td>
            </tr>
            <tr>
              <td>61</td><td>Placa metalica (porta rombos)</td><td>{{ $data['Placa metalica (porta rombos)'] }}</td>
            </tr>
            <tr>
              <td>62</td><td>Banderolas</td><td>{{ $data['Banderolas'] }}</td>
            </tr>
            <tr>
              <td>63</td><td>Espejos Concavos (I. D.)</td><td>{{ $data['Espejos Concavos (I. D.)'] }}</td>
            </tr>
            <tr>
              <td>64</td><td></td><td></td>
            </tr>
          </table>  
          <table class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revision cabina posterior de tractocamion</th>
              <th>Cumple</th>
            </tr>
              <td>65</td><td>Luces direccionales</td><td>{{ $data['Luces direccionales Posterior'] }}</td>
            </tr>
            <tr>
              <td>66</td><td>Luces de advertencia</td><td>{{ $data['Luces de advertencia Posterior'] }}</td>
            </tr>
            <tr>
              <td>67</td><td>Luces de carga</td><td>{{ $data['Luces de carga'] }}</td>
            </tr>
            <tr>
              <td>68</td><td>Luces de estacionamiento</td><td>{{ $data['Luces de estacionamiento'] }}</td>
            </tr>
            <tr>
              <td>69</td><td>Luces de frenado</td><td>{{ $data['Luces de frenado'] }}</td>
            </tr>
            <tr>
              <td>70</td><td>Luces de marcha atras</td><td>{{ $data['Luces de marcha atras'] }}</td>
            </tr>
            <tr>
              <td>71</td><td>Reflejantes</td><td>{{ $data['Reflejantes  Posterior'] }}</td>
            </tr>
            <tr>
              <td>72</td><td>Guardafangos (Loderas)</td><td>{{ $data['Guardafangos (Loderas) Posterior'] }}</td>
            </tr>
            <tr>
              <td>73</td><td>Defensa (sujecion)</td><td>{{ $data['Defensa (sujecion) Posterior'] }}</td>
            </tr>
            <tr>
              <td>74</td><td>Placa de identificacion vehicular</td><td>{{ $data['Placa de identificacion vehicular Posterior'] }}</td>
            </tr>
            </tr>
              <td>75</td><td>Escape</td><td>{{ $data['Escape'] }}</td>
            </tr>
            <tr>
              <td>76</td><td>Llantas (surco no menos de 3 mm)</td><td>{{ $data['Llantas (Surco no menos de 3 mm.) Posterior'] }}</td>
            </tr>
            <tr>
              <td>77</td><td>Rines sin fisuras ({{ $data['Type Rin Posterior'] }})</td><td>{{ $data['Rines sin fisuras Posterior'] }}</td>
            </tr>
            <tr>
              <td>78</td><td>Birlos completos y sin fisuras</td><td>{{ $data['Birlos completos y sin fisuras Posterior'] }}</td>
            </tr>
            <tr>
              <td>79</td><td>Baleros (sin fuga)</td><td>{{ $data['Baleros (Sin Fuga)'] }}</td>
            </tr>
            <tr>
              <td>80</td><td>Quinta rueda</td><td>{{ $data['Quinta rueda'] }}</td>
            </tr>
            <tr>
              <td>81</td><td>Matachispas y tubo de proteccion de escape</td><td>{{ $data['Matachispas y tubo de proteccion de escape'] }}</td>
            </tr>
            <tr>
              <td>82</td><td>Sistema hidraulico sin fugas de aceite</td><td>{{ $data['Sistema hidraulico sin fugas de aceite'] }}</td>
            </tr>
            <tr>
              <td>83</td><td>Mangueras de aire</td><td>{{ $data['Mangueras de aire'] }}</td>
            </tr>
            <tr>
              <td>84</td><td>Cable electrico de 7 hilos</td><td>{{ $data['Cable electrico de 7 hilos'] }}</td>
            </tr>
            </tr>
              <td>85</td><td></td><td></td>
            </tr>
            <tr>
              <td>86</td><td></td><td></td>
            </tr>
          </table> 
          <table class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revision motor de tractocamion</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>87</td><td>Bandas de motor sin daño</td><td>{{ $data['Bandas de motor sin daño'] }}</td>
            </tr>
            <tr>
              <td>88</td><td>Nivel de aceite</td><td>{{ $data['Nivel de aceite'] }}</td>
            </tr>
            <tr>
              <td>89</td><td>Nivel de refrijerante</td><td>{{ $data['Nivel de refrigerante'] }}</td>
            </tr>
            <tr>
              <td>90</td><td>Fugas de aceite</td><td>{{ $data['Fugas de aceite'] }}</td>
            </tr>
            <tr>
              <td>91</td><td>Lineas de clima</td><td>{{ $data['Lineas de clima'] }}</td>
            </tr>
            <tr>
              <td>92</td><td>Lineas electricas</td><td>{{ $data['Lineas electricas'] }}</td>
            </tr>
            <tr>
              <td>93</td><td>Lineas de aire</td><td>{{ $data['Lineas de aire Motor'] }}</td>
            </tr>
            <tr>
              <td>94</td><td>Ventilador de radiador</td><td>{{ $data['Ventilador de radiador'] }}</td>
            </tr>
            <tr>
              <td>95</td><td>Turbo sin fugas</td><td>{{ $data['Turbo sin fugas'] }}</td>
            </tr>
            <tr>
              <td>96</td><td>Mangueras de admision sin abrazaderas flojas</td><td>{{ $data['Mangueras de admision sin  abrazaderas flojas'] }}</td>
            </tr>
            <tr>
              <td>97</td><td>Brazo viajero</td><td>{{ $data['Brazo viajero'] }}</td>
            </tr>
            <tr>
              <td>98</td><td>Soportes de motor</td><td>{{ $data['Soportes de motor'] }}</td>
            </tr>
            <tr>
              <td>99</td><td></td><td></td>
            </tr>
            <tr>
              <td>100</td><td></td><td></td>
            </tr>
            <tr>
              <td>101</td><td></td><td></td>
            </tr>
          </table>       
        </div>
        <div style="clear: both;"></div>
        <div>
          <table style="width: 97%; text-align: center;">            
            <tr>
              <td class="signature">
                {{ $data['auxiliar']->name ?? ' ' }} {{ $data['auxiliar']->a_paterno ?? ' ' }} {{ $data['auxiliar']->a_materno ?? ' ' }}
              </td>
              <td class="signature">
                {{ $data['operator']->name }} {{ $data['operator']->a_paterno }} {{ $data['operator']->a_materno }}
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
      </div>      
    </main>
  </body>
</html>
