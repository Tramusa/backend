<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-05-10 REVISION DE CONSUMO DE COMBUSTIBLE</title>
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
        border: 2.5px solid #000;
      }

      .column-2 {
        width: 80%;
        float: left;
        border: 2.5px solid #000;
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
        width: 78%;
        float: left; 
      }

      .column-2-2 {
        width: 20%;
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

      .blueTitleDos {
        color: #000080;
        font-size: 0.9em;
      }

      .redTitle
      {
        color: red;
        font-size: 1.4em;
        font-weight: bold;
      }

      .der {
        text-align: right;
      }

      .center {
        text-align: center;
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
        width: 100%;
      }

      table th {
        font-family: "Arial Narrow", Arial, sans-serif;
        text-align: center;
        padding: 4px 4px;
        color: #FFFFFF;
        font-size: 1em;
        border: 1px solid #000;
        white-space: nowrap;        
        font-weight: bold;
        background: #1E4E79;
      }

      table td {
        font-size: 0.9em;
        padding: 4px 4px;
        border: 1px solid #000;/* Borde negro grosor 1 */
      }

      .signature {
        font-family: 'Courier New', monospace; /* Una fuente cursiva disponible en la mayoría de los sistemas */
        font-style: italic;
        font-weight: bold;
        font-size: 13px;
        color: #000080; /* Azul marino */
        letter-spacing: 0.4px; /* Espaciado ligero para emular la caligrafía */
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
        <p class="title">REVISIÓN DE CONSUMO DE COMBUSTIBLE</p>
        <h2>	AREA: 	MANTENIMIENTO	       	F-05-10     	REVISION:	 AGOSTO 2018</h2>
      </div>      
    </header>
    <main>
      <div class="row">
        <div class="column-2-1"></div>
        <table class="column-2-2">
          <tr><th>PERIODICIDAD DE REVISIÓN</th></tr>
          <tr><td class="blueTitleDos center">MENSUALMENTE</td></tr>
        </table>
      </div>     
      <div style="clear: both;"></div>
      <div class="row">
        <!-- Columna 1 -->
        <div class="column-50">
            <table>
                <tr>
                    <td class="der">PRUEBA N°</td>
                    <td class="blueTitleDos">{{ $data['revision']['id'] }}</td>
                    <td class="der">FECHA</td>
                    <td colspan="3" class="blueTitleDos">{{ $data['revision']['end_date'] }}</td>
                </tr>
                <tr>
                    <td class="der">N°ECONOMICO</td>
                    <td class="blueTitleDos">{{ $data['unit']['no_economic'] }}</td>
                    <td class="der">OPERADOR</td>
                    <td colspan="3" class="blueTitleDos">{{ $operator->name }} {{ $operator->a_paterno }}</td>
                </tr>
                <tr>
                    <td class="blueTitleDos der">{{ $data['unit']['brand'] ?? ' ' }}</td>
                    <td colspan="3" class="blueTitleDos"></td>
                    <td class="der">VELOCIDADES</td>
                    <td class="blueTitleDos">{{ $data['unit']['speeds'] ?? ' ' }}</td>
                </tr>
                <tr>
                    <td class="der">PASO</td>
                    <td class="blueTitleDos">{{ $data['unit']['differential_pitch'] ?? ' ' }}</td>
                    <td class="der">Rev/km.Llanta</td>
                    <td class="blueTitleDos">{{ $data['unit']['tire'] ?? ' ' }}</td>
                    <td class="der">Rel de Trans.</td>
                    <td class="blueTitleDos">{{ $data['unit']['transmission'] ?? ' ' }}</td>
                </tr>
                <tr>
                    <td class="der">ECM Code</td>
                    <td colspan="5" class="blueTitleDos">{{ $data['unit']['ecm'] ?? ' ' }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="der">Reg. ajuste de combustible antes de prueba</td>
                    <td colspan="3" class="blueTitleDos"></td>
                </tr>
                <tr>
                    <td colspan="3" class="der">Ajuste despues de prueba</td>
                    <td colspan="2" class="blueTitleDos"></td>
                    <td class="blueTitleDos"></td>
                </tr>
                <tr>
                    <td class="der">ESN</td>
                    <td class="blueTitleDos">{{ $data['unit']['esn'] ?? ' ' }}</td>
                    <td class="der">CPL</td>
                    <td colspan="3" class="blueTitleDos">{{ $data['unit']['cpl'] ?? ' ' }}</td>
                </tr>
            </table> 
        </div>
        <!-- Columna 2 -->
        <div class="column-50">
            <table>
                <tr>
                    <td><br>    
                        <div class="signature">{{ $coordinador->name }} {{ $coordinador->a_paterno }} {{ $coordinador->a_materno }}</div>
                        _______________________________<br>
                        ING. {{ $coordinador->name }} {{ $coordinador->a_paterno }} {{ $coordinador->a_materno }}
                    </td>
                    <td> <br>     
                        <div class="signature">{{ $operator->name }} {{ $operator->a_paterno }} {{ $operator->a_materno }}</div>
                        _____________________________<br>
                        {{ $operator->name }} {{ $operator->a_paterno }} {{ $operator->a_materno }}
                    </td>
                </tr>
                <tr>
                    <th>COORDINADOR MANTENIMIENTO</th>
                    <th>OPERADOR</th>
                </tr>
                <tr>
                    <td colspan="2"><br><br><br></td>
                </tr>
                <tr>
                    <th colspan="2">OBSERVACIONES</th>
                </tr>
            </table>  
        </div>
      </div>
      <div style="clear: both;"></div>
      <div class="row">
        <table>
            <tr>
                <th style="font-size: 1.6em;" colspan="6">RUTA DE PRUEBA</th>
            </tr>
            <tr>
                <td class="center" colspan="2"><div class="redTitle">INFORMACIÓN FíSICA</div>(LOS KMS PUEDEN VARIAR CUANDO SON TABLEROS ANALOGOS)</td>
                <td class="blueTitleDos center">{{ $data['A']['name'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['name'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['name'] : '' }}</td>
                <td class="center">TOTAL</td>
            </tr>
            <tr>
                <td style="width:40%" class="der">Distancia Recorrida Odómetro Tablero: </td>
                <td style="width:3%">KM.</td>
                <td class="blueTitleDos center" style="width:16%">{{ $data['A']['distancia_tablero'] }}</td>
                <td class="blueTitleDos center" style="width:16%">{{ isset($data['B']) ? $data['B']['distancia_tablero'] : '' }}</td>
                <td class="blueTitleDos center" style="width:16%">{{ isset($data['C']) ? $data['C']['distancia_tablero'] : '' }}</td>
                <?php
                    $sumABC_Distancia = $data['A']['distancia_tablero'];
                    if (isset($data['B'])) {
                        $sumABC_Distancia += $data['B']['distancia_tablero'];
                    }
                    if (isset($data['C'])) {
                        $sumABC_Distancia += $data['C']['distancia_tablero'];
                    }
                ?>
                <td class="center" style="width:10%">{{ $sumABC_Distancia }}</td>
            </tr>
            <tr>
                <td class="der">Combustible Cargado en Gasolineras: </td>
                <td>LT.</td>
                <td class="blueTitleDos center">{{ $data['A']['combustible_cargado'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['combustible_cargado'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['combustible_cargado'] : '' }}</td>
                <?php
                    $sumABC_cargado = $data['A']['combustible_cargado'];
                    if (isset($data['B'])) {
                        $sumABC_cargado += $data['B']['combustible_cargado'];
                    }
                    if (isset($data['C'])) {
                        $sumABC_cargado += $data['C']['combustible_cargado'];
                    }
                ?>
                <td class="center">{{ $sumABC_cargado }}</td>
            </tr>
            <tr>
                <td class="der">Factor de Corrección de Bomba de Combustible: </td>
                <td>LT.</td>
                <td class="blueTitleDos center">{{ $data['A']['factor_correcion'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['factor_correcion'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['factor_correcion'] : '' }}</td>
                <?php
                    $sumABC_factor = $data['A']['combustible_cargado']*($data['A']['factor_correcion'] / 100);
                    if (isset($data['B']['combustible_cargado']) && isset($data['B']['factor_correcion'])) {
                      $sumABC_factor += $data['B']['combustible_cargado'] * ($data['B']['factor_correcion'] / 100);
                    }                    
                    if (isset($data['C']['combustible_cargado']) && isset($data['C']['factor_correcion'])) {
                      $sumABC_factor += $data['C']['combustible_cargado'] * ($data['C']['factor_correcion'] / 100);
                    }
                ?>
                <td class="center">{{ $sumABC_factor }}</td>
            </tr>
            <tr>
                <td class="der">Combustible Usado Neto: </td>
                <td>LT.</td>
                <td colspan="3"></td>
                <?php
                  $sum_Usado = $sumABC_factor + $sumABC_cargado;
                ?>
                <td class="center">{{ $sum_Usado }}</td>
            </tr>
            <tr>
                <td class="der">Rendimiento REAL de combustible con factor de Corrección: </td>
                <td>KM/LT</td>
                <td colspan="3"></td>
                <?php
                  $sum_REAL = $sumABC_Distancia / $sum_Usado;
                ?>
                <td class="center">{{ $sum_REAL }}</td>
            </tr>
            <tr>
                <td class="der">REND. REAL SIN USO DE PTO: </td>
                <td>KM/LT</td>
                <td colspan="3"></td>
                <?php
                  $sumABC_consumo_pto = $data['A']['consumo_pto'];
                  if (isset($data['B']['consumo_pto'])) {
                    $sumABC_consumo_pto += $data['B']['consumo_pto'];
                  }
                  if (isset($data['C']['consumo_pto'])) {
                    $sumABC_consumo_pto += $data['C']['consumo_pto'];
                  }
                ?>
                <td class="center">{{ $sumABC_Distancia / ($sum_Usado - $sumABC_consumo_pto) }}</td>
            </tr>
        </table>
        <div style="clear: both;"></div>
        <table>
            <tr>
                <td class="redTitle center" colspan="2">INFORMACIÓN DE ECM</td>
                <td class="blueTitleDos center">{{ $data['A']['name'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['name'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['name'] : '' }}</td>
                <td class="center">TOTAL</td>
            </tr>
            <tr>
                <td style="width:40%" class="der">Distancia Recorrida ECM: </td>
                <td style="width:3%">KM.</td>
                <td class="blueTitleDos center" style="width:16%">{{ $data['A']['distancia_ecm'] }}</td>
                <td class="blueTitleDos center" style="width:16%">{{ isset($data['B']) ? $data['B']['distancia_ecm'] : '' }}</td>
                <td class="blueTitleDos center" style="width:16%">{{ isset($data['C']) ? $data['C']['distancia_ecm'] : '' }}</td>
                <?php
                    $sumABC_DistanciaECM = $data['A']['distancia_ecm'];
                    if (isset($data['B']['distancia_ecm'])) {
                      $sumABC_DistanciaECM += $data['B']['distancia_ecm'];
                    }                    
                    if (isset($data['C']['distancia_ecm'])) {
                      $sumABC_DistanciaECM += $data['C']['distancia_ecm'];
                    }
                ?>
                <td class="center" style="width:10%">{{ $sumABC_DistanciaECM }}</td>
            </tr>
            <tr>
                <td class="der">Combustible Usado en el viaje: </td>
                <td>LT.</td>
                <td class="blueTitleDos center">{{ $data['A']['combustible_usado'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['combustible_usado'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['combustible_usado'] : '' }}</td>
                <?php
                    $sumABC_usado2 = $data['A']['combustible_usado'];
                    if (isset($data['B']['combustible_usado'])) {
                      $sumABC_usado2 += $data['B']['combustible_usado'];
                    }                    
                    if (isset($data['C']['combustible_usado'])) {
                      $sumABC_usado2 += $data['C']['combustible_usado'];
                    }
                ?>
                <td class="center">{{ $sumABC_usado2 }}</td>
            </tr>
            <tr>
                <td class="der">Rendimiento de Combustible: </td>
                <td>KM/LT</td>
                <td class="blueTitleDos center">{{ $data['A']['rendimiento_combustible'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['rendimiento_combustible'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['rendimiento_combustible'] : '' }}</td>
                <?php
                  $sum_rendimiento = $sumABC_DistanciaECM / $sumABC_usado2;
                ?>
                <td class="center">{{ $sum_rendimiento }}</td>
            </tr>
            <tr>
                <td class="der">Diferencial de Combustible ECM vs REAL: </td>
                <td>%</td>
                <td colspan="3"></td>
                <td class="center">{{ (($sumABC_usado2 - $sumABC_cargado) / $sumABC_cargado) * 100 }}%</td>
            </tr>
            <tr>
                <td class="der">Peso Bruto Combinado Promedio: </td>
                <td>KG.</td>
                <td class="blueTitleDos center">{{ $data['A']['peso_bruto'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['peso_bruto'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['peso_bruto'] : '' }}</td>
                <td class="center"> - </td>
            </tr>
            <tr>
                <td class="der">Tiempo de Viaje: </td>
                <td>HRS.</td>
                <td class="blueTitleDos center">{{ $data['A']['tiempo'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['tiempo'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['tiempo'] : '' }}</td>
                 <?php
                    $sumABC_tiempo = $data['A']['tiempo'];
                    if (isset($data['B']['tiempo'])) {
                      $sumABC_tiempo += $data['B']['tiempo'];
                    }                    
                    if (isset($data['C']['tiempo'])) {
                      $sumABC_tiempo += $data['C']['tiempo'];
                    }
                ?>
                <td class="center">{{ $sumABC_tiempo }}</td>
            </tr>
            <tr>
                <td class="der">Combustible Consumido en Ralentí: </td>
                <td>LT.</td>
                <td class="blueTitleDos center">{{ $data['A']['consumo_ralenti'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['consumo_ralenti'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['consumo_ralenti'] : '' }}</td>
                 <?php
                    $sumABC_consumo_ralenti = $data['A']['consumo_ralenti'];
                    if (isset($data['B']['consumo_ralenti'])) {
                      $sumABC_consumo_ralenti += $data['B']['consumo_ralenti'];
                    }                    
                    if (isset($data['C']['consumo_ralenti'])) {
                      $sumABC_consumo_ralenti += $data['C']['consumo_ralenti'];
                    }
                ?>
                <td class="center">{{ $sumABC_consumo_ralenti }}</td>
            </tr>
            <tr>
                <td class="der">Tiempo de Motor en Ralentí: </td>
                <td>HRS.</td>
                <td class="blueTitleDos center">{{ $data['A']['tiempo_ralenti'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['tiempo_ralenti'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['tiempo_ralenti'] : '' }}</td>
                 <?php
                    $sumABC_tiempo_ralenti = $data['A']['tiempo_ralenti'];
                    if (isset($data['B']['tiempo_ralenti'])) {
                      $sumABC_tiempo_ralenti += $data['B']['tiempo_ralenti'];
                    }                    
                    if (isset($data['C']['tiempo_ralenti'])) {
                      $sumABC_tiempo_ralenti += $data['C']['tiempo_ralenti'];
                    }
                ?>
                <td class="center">{{ $sumABC_tiempo_ralenti }}</td>
            </tr>
            <tr>
                <td class="der">Combustible Consumido en PTO: </td>
                <td>LT</td>
                <td class="blueTitleDos center">{{ $data['A']['consumo_pto'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['consumo_pto'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['consumo_pto'] : '' }}</td>
                <td class="center">{{ $sumABC_consumo_pto }}</td>
            </tr>
            <tr>
                <td class="der">Tiempo de Motor en PTO: </td>
                <td>HRS.</td>
                <td class="blueTitleDos center">{{ $data['A']['tiempo_pto'] }}</td>
                <td class="blueTitleDos center">{{ isset($data['B']) ? $data['B']['tiempo_pto'] : '' }}</td>
                <td class="blueTitleDos center">{{ isset($data['C']) ? $data['C']['tiempo_pto'] : '' }}</td>
                 <?php
                    $sumABC_tiempo_pto = $data['A']['tiempo_pto'];
                    if (isset($data['B']['tiempo_pto'])) {
                      $sumABC_tiempo_pto += $data['B']['tiempo_pto'];
                    }                    
                    if (isset($data['C']['tiempo_pto'])) {
                      $sumABC_tiempo_pto += $data['C']['tiempo_pto'];
                    }
                ?>
                <td class="center">{{ $sumABC_tiempo_pto }}</td>
            </tr>
        </table>
      </div>      
    </main>
  </body>
</html>
