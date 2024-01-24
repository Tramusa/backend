<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-05-15 CONDICIONES FISICO MECANICAS VEHICULOS LIGEROS</title>
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
        padding: 0px 5px;
        border: 1px solid #000;
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
        <p class="title">REVISIÓN DE CONDICIONES FISICO-MECÁNICAS DE VEHÍCULOS LIGEROS</p>
        <h2>ÁREA: MANTENIMIENTO F-05-15 PERIODICIDAD: DIARIO RESGUARDO: 3 AÑOS REVISIÓN: FEBRERO 2021 </h2>
      </div>      
    </header>
    <main>
      <div class="row"><br>
        <div class="column-2-1">FECHA: ____{{ $fecha }}______  VEHICULO: ___{{ $data['unit']->no_economic }}_______   </div>
      </div>    
      <table class="column-2-2">
          <tr><th>Odometro</th></tr>
          <tr><td class="blueTitle">{{ $data['odometro'] }}</td></tr>
      </table> 
      <div style="clear: both;"></div><br><br>
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
              <td>7</td><td>Indicador de combustible</td><td>{{ $data['Indicador de combustible'] }}</td>
            </tr>
            <tr>
              <td>8</td><td>Tacometro (RPM)</td><td>{{ $data['Tacometro (RPM)'] }}</td>
            </tr>
            <tr>
              <td>9</td><td>Velocimetro</td><td>{{ $data['Velocimetro'] }}</td>
            </tr>
            <tr>
              <td>10</td><td>Claxon</td><td>{{ $data['Claxon'] }}</td>
            </tr>
            <tr>
              <td>11</td><td>Cinturones de seguridad</td><td>{{ $data['Cinturon de seguridad'] }}</td>
            </tr>
            <tr>
              <td>12</td><td>Puertas de acceso</td><td>{{ $data['Puertas de acceso'] }}</td>
            </tr>
            <tr>
              <td>13</td><td>Funcionamiento de cristales electricos</td><td>{{ $data['Funcionamiento de cristales electricos'] }}</td>
            </tr>
            <tr>
              <td>14</td><td>Parabrisas</td><td>{{ $data['Parabrisas'] }}</td>
            </tr>
            <tr>
              <td>15</td><td>Limipiadores</td><td>{{ $data['Limpiadores'] }}</td>
            </tr>
            <tr>
              <td>16</td><td>Espejo retrovisores (I. D.)</td><td>{{ $data['Espejo retrovisores (I. D.)'] }}</td>
            </tr>
            <tr>
              <td>17</td><td>Volante (Sin juego excesivo)</td><td>{{ $data['Volante (Sin juego excesivo)'] }}</td>
            </tr>
            <tr>
              <td>18</td><td>Freno de pie</td><td>{{ $data['Freno de pie'] }}</td>
            </tr>
            <tr>
              <td>19</td><td>Frenado de mano</td><td>{{ $data['Frenado de mano'] }}</td>
            </tr>
            <tr>
              <td>20</td><td>Switch de marcha</td><td>{{ $data['Switch de marcha'] }}</td>
            </tr>
            <tr>
              <td>21</td><td>Tapiceria</td><td>{{ $data['Tapiceria'] }}</td>
            </tr>
            <tr>
              <td>22</td><td>Funcionamiento de accesorios</td><td>{{ $data['Funcionamiento de accesorios'] }}</td>
            </tr>
            <tr>
              <td>23</td><td></td><td></td>
            </tr>
            <tr>
              <td>24</td><td></td><td></td>
            </tr>
          </table>
          <table style="width: 96%;">
            <tr>
              <th>N°</th>
              <th>Revisión cabina izq y derecho de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>25</td><td>Chapas de las puertas</td><td>{{ $data['Chapas de las puertas'] }}</td>
            </tr>
            <tr>
              <td>26</td><td>Tapón de tanque de combustible</td><td>{{ $data['Tapon de tanque de combustible'] }}</td>
            </tr>
            <tr>
              <td>27</td><td>Tanque de combustible si fugas</td><td>{{ $data['Tanque de combustible sin fugas'] }}</td>
            </tr>
            <tr>
              <td>28</td><td>Luces de advertencia laterales</td><td>{{ $data['Luces de advertencia laterales'] }}</td>
            </tr>
            <tr>
              <td>29</td><td>Reflejantes</td><td>{{ $data['Reflejantes Lado Izq/Der'] }}</td>
            </tr>
            <tr>
              <td>30</td><td>Razón social y número de equipo</td><td>{{ $data['Razon social y numero de equipo'] }}</td>
            </tr>
            <tr>
              <td>31</td><td>Telefono de emergencia</td><td>{{ $data['Telefono de emergencia'] }}</td>
            </tr>
            <tr>
              <td>32</td><td></td><td></td>
            </tr>
          </table> 
          <table style="width: 96%;">
            <tr>
              <th>N°</th>
              <th>Revisión parte inferior de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>33</td><td>Llanta (No renovadas surco no menos de 3 mm)</td><td>{{ $data['Llantas (No renovadas Surco no menos de 3 mm.)'] }}</td>
            </tr>
            <tr>
              <td>34</td><td>Muelle (sin hojas sueltas, rotas o fisuras)</td><td>{{ $data['Muelles (Suspension sin hojas sueltas, rotas o fisuras)'] }}</td>
            </tr>
            <tr>
              <td>35</td><td>Líneas eléctricas sujetas</td><td>{{ $data['Lineas electricas sujetas'] }}</td>
            </tr>
            <tr>
              <td>36</td><td>Diferenciales (sin fugas)</td><td>{{ $data['Diferenciales (sin fugas)'] }}</td>
            </tr>
            <tr>
              <td>37</td><td>Transmisión (sin fugas)</td><td>{{ $data['Transmision (sin fugas)'] }}</td>
            </tr>
            <tr>
              <td>38</td><td>Crucetas cardan</td><td>{{ $data['Crucetas y soporte cardan'] }}</td>
            </tr>   
            <tr>
              <td>39</td><td>Amortiguadores sin fuga</td><td>{{ $data['Amortiguadores sin fuga'] }}</td>
            </tr>
            <tr>
              <td>40</td><td>Alarma reversa</td><td>{{ $data['Alarma Reversa'] }}</td>
            </tr>
            <tr>
              <td>41</td><td>Radiador</td><td>{{ $data['Radiador'] }}</td>
            </tr>
            <tr>
              <td>42</td><td>Bandas</td><td>{{ $data['Bandas'] }}</td>
            </tr>
            <tr>
              <td>43</td><td>Llanta de refacción</td><td>{{ $data['Llanta de refaccion'] }}</td>
            </tr>
            <tr>
              <td>44</td><td></td><td></td>
            </tr>
            <tr>
              <td>45</td><td></td><td></td>
            </tr>
          </table>         
        </div>
        <!-- Columna 2 -->
        <div class="column-50">           
          <table style="width: 97%;" class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión cabina frontal de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>46</td><td>Defensa (sujeción)</td><td>{{ $data['Defensa (sujecion) Frontal'] }}</td>
            </tr>
            <tr>
              <td>47</td><td>Placa de identificación vehícular</td><td>{{ $data['Placa de identificacion vehicular Frontal'] }}</td>
            </tr>
            <tr>
              <td>48</td><td>Faros principales (color y funcionamiento)</td><td>{{ $data['Faros principales (Color y funcionamiento)'] }}</td>
            </tr>
            <tr>
              <td>49</td><td>Luces direccionales</td><td>{{ $data['Luces direccionales Frontal'] }}</td>
            </tr>
            <tr>
              <td>50</td><td>Luces de advertencia</td><td>{{ $data['Luces de advertencia Frontal'] }}</td>
            </tr>
            <tr>
              <td>51</td><td>Rines sin fisuras</td><td>{{ $data['Rines sin fisuras  Frontal'] }}</td>
            </tr>
            <tr>
              <td>52</td><td>Birlos completos y sin fisuras</td><td>{{ $data['Birlos completos y sin fisuras Frontal'] }}</td>
            </tr>
            <tr>
              <td>53</td><td>Guardafangos (Loderas)</td><td>{{ $data['Guardafangos (Loderas) Frontal'] }}</td>
            </tr>
            <tr>
              <td>54</td><td></td><td></td>
            </tr>
          </table>
          <table style="width: 97%;" class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión cabina posterior de la unidad</th>
              <th>Cumple</th>
            </tr>
              <td>55</td><td>Luces direccionales</td><td>{{ $data['Luces direccionales Posterior'] }}</td>
            </tr>
            <tr>
              <td>56</td><td>Luces de advertencia</td><td>{{ $data['Luces de advertencia Posterior'] }}</td>
            </tr>
            <tr>
              <td>57</td><td>Luces de frenado</td><td>{{ $data['Luces de frenado'] }}</td>
            </tr>
            <tr>
              <td>58</td><td>Extintores</td><td>{{ $data['Extintores'] }}</td>
            </tr>
            <tr>
              <td>59</td><td>Topes para llantas</td><td>{{ $data['Topes para llantas'] }}</td>
            </tr>
            <tr>
              <td>60</td><td>Luces de marcha atras</td><td>{{ $data['Luces de marcha atras'] }}</td>
            </tr>
            <tr>
              <td>61</td><td>Reflejantes</td><td>{{ $data['Reflejantes Posterior'] }}</td>
            </tr>
            <tr>
              <td>62</td><td>Guardafangos (Loderas)</td><td>{{ $data['Guardafangos (Loderas) Posterior'] }}</td>
            </tr>
            <tr>
              <td>63</td><td>Defensa (sujeción)</td><td>{{ $data['Defensa (sujecion) Posterior'] }}</td>
            </tr>
            <tr>
              <td>64</td><td>Placa de identificación vehícular</td><td>{{ $data['Placa de identificacion vehicular'] }}</td>
            </tr>
            </tr>
              <td>65</td><td>Escape</td><td>{{ $data['Escape'] }}</td>
            </tr>
            <tr>
              <td>66</td><td>Rines sin fisuras</td><td>{{ $data['Rines sin fisuras Posterior'] }}</td>
            </tr>
            <tr>
              <td>67</td><td>Birlos completos y sin fisuras</td><td>{{ $data['Birlos completos y sin fisuras Posterior'] }}</td>
            </tr>
            <tr>
              <td>68</td><td></td><td></td>
            </tr>
            <tr>
              <td>69</td><td></td><td></td>
            </tr>
            <tr>
              <td>70</td><td></td><td></td>
            </tr>
            <tr>
              <td>71</td><td></td><td></td>
            </tr>
            <tr>
              <td>72</td><td></td><td></td>
            </tr>
          </table> 
          <table style="width: 97%;" class="espacio-derecha">
            <tr>
              <th>N°</th>
              <th>Revisión motor de la unidad</th>
              <th>Cumple</th>
            </tr>
            <tr>
              <td>73</td><td>Puertas de acceso a motor</td><td>{{ $data['Puerta de acceso a motor'] }}</td>
            </tr>
            <tr>
              <td>74</td><td>Ventilador de radiador</td><td>{{ $data['Ventilador de radiador'] }}</td>
            </tr>
            <tr>
              <td>75</td><td>Lineas de clima sin fuga</td><td>{{ $data['Lineas de clima sin fuga'] }}</td>
            </tr>
            <tr>
              <td>76</td><td>Bandas de motor sin daño</td><td>{{ $data['Bandas de motor sin daño'] }}</td>
            </tr>
            <tr>
              <td>77</td><td>Turbo sin fuga</td><td>{{ $data['Turbo sin fugas'] }}</td>
            </tr>
            <tr>
              <td>78</td><td>Mangueras de admisión sin abrazaderas flojas</td><td>{{ $data['Mangueras de admision sin  abrazaderas flojas'] }}</td>
            </tr>
            <tr>
              <td>79</td><td>Nivel de refrigerante</td><td>{{ $data['Nivel de refrigerante'] }}</td>
            </tr>
            <tr>
              <td>80</td><td>Nivel de aceite de motor</td><td>{{ $data['Nivel de aceite de motor'] }}</td>
            </tr>
            <tr>
              <td>81</td><td>Lineas de agua sin fuga</td><td>{{ $data['Lineas de agua sin fuga'] }}</td>
            </tr>
            <tr>
              <td>82</td><td>Lineas eléctricas fijas</td><td>{{ $data['Lineas electricas fijas Motor'] }}</td>
            </tr>
            <tr>
              <td>83</td><td>Parte posterior de motor sin fugas</td><td>{{ $data['Parte posterior de motor sin fugas'] }}</td>
            </tr>
            <tr>
              <td>84</td><td>Nivel de aceite de direcciones</td><td>{{ $data['Nivel de aceite de direccion'] }}</td>
            </tr>
            <tr>
              <td>85</td><td></td><td></td>
            </tr>
            <tr>
              <td>86</td><td></td><td></td>
            </tr>
            <tr>
              <td>87</td><td></td><td></td>
            </tr>
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
        <div><br><br>
          <table style="width: 100%;">            
            <tr>
              <td class="signature">
                {{ $data['auxiliar']->name ?? ' ' }} {{ $data['auxiliar']->a_paterno ?? ' ' }} {{ $data['auxiliar']->a_materno ?? ' ' }}
              </td>
              <td class="signature">
                {{ $data['operator']->name }} {{ $data['operator']->a_paterno }} {{ $data['operator']->a_materno }}
              </td>
              <td>{{ $data['observation'] ?? ' ' }}</td>
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
