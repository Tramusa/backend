<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>VALORACIÓN DE FATIGA DEL PERSONAL</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 3px;
        }

        .center {
            text-align: center;
        }

        .small {
            font-size: 10px;
        }

        .green {
            background: #C6EFCE;
        }

        .yellow {
            background: #FFEB9C;
        }

        .red {
            background: #ff8593;
        }

        .header td {
            border: none;
        }

        .sectionTitle {
            background: #4F81BD;
            color: white;
            font-weight: bold;
        }

        .totalBox {
            font-weight: bold;
            text-align: center;
            font-size: 15px;
        }

        #logo {
            text-align: center;
        }

        .logoImg {
            width: 120px;
            margin: 2px 0 1px 0;
        }

        .column-1 {
            width: 20%;
            float: left;
            border: 2px solid #D1D1D1;
        }

        .column-2 {
            width: 79%;
            float: left;
            border: 2px solid #D1D1D1;
        }

        .blueTitle {
            text-align: center;
            font-weight: bold;
            color: #0073B5;
            margin: 0 0 0 0;
        }

        .title {
            text-align: center;
            font-weight: bold;
            margin: 0 0 0 0;
        }

        .orangeBar {
            font-family: "Arial Narrow", Arial, sans-serif;
            color: #FFFFFF;
            font-size: 0.8em;
            line-height: 1.4em;
            font-weight: bold;
            text-align: center;
            margin: 0 0 0 0;
            background: #F4B083;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .markX{
            font-weight: bold;
            font-size: 13px;
        }

        .riskBox{
            border: 1px solid #000;
            text-align: center;
            white-space: pre-line;
            font-size: 11px;
        }

        .riskLow{
            background: #C6EFCE;
        }

        .riskMedium{
            background: #FFEB9C;
        }

        .riskHigh{
            background: #ff8593;
        }
        
        .riskBox{
            white-space: pre-line;
            line-height: 1.4;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header class="clearfix">

        <div class="column-1">
            <div id="logo">
                <img class="logoImg" src="{{ $logo }}">
            </div>
        </div>

        <div class="column-2">
            <p class="blueTitle">TRAMUSA CARRIER S.A. DE C.V.</p>
            <p class="title">VALORACIÓN DE FATIGA DEL PERSONAL</p>
            <p class="orangeBar"> ÁREA: SEGURIDAD E HIGIENE EX07-32 PERIODO: CUANDO SE PRESENTE RESGUARDO: 1 AÑO REVISIÓN: JUNIO 2022</p>
        </div>

    </header><br>

    <!-- DATOS OPERADOR -->
    <table>
        <tr>
            <td width="50%">
                <b>Nombre del operador:</b>
                {{ $operator->name }}
                {{ $operator->a_paterno }}
                {{ $operator->a_materno }}
            </td>
            <td width="30%">
                <b>Fecha:</b>
                {{ $date }}
            </td>
            <td width="20%">
                <b>Hora:</b>
                {{ $time }}
            </td>
        </tr>
    </table>

    <!-- INDICACIONES -->
    <table>
        <tr>
            <td class="sectionTitle">
                Esta herramienta debe utilizarse cuando:
            </td>
        </tr>
        <tr>
            <td class="small">
                * El colaborador informa que está fatigado.<br>
                * El supervisor o compañeros observan signos de fatiga.<br>
                * En otras situaciones en las que pueda haber riesgo de fatiga, por ejemplo:

                <ul style="margin-top:5px;">
                    <li>Primer turno de noche.</li>
                    <li>Primer día después de viaje prolongado.</li>
                    <li>Rotación extendida o no planeada.</li>
                    <li>Readiscore menor a 70.</li>
                </ul>
            </td>
        </tr>
    </table>

    <!-- INSTRUCCIONES -->
    <table>
        <tr>
            <td class="sectionTitle">
                Instrucciones
            </td>
        </tr>
        <tr>
            <td class="small">
                El supervisor completará la tabla de observaciones (Sección 1) para identificar signos relevantes de fatiga.<br>
                El supervisor conversará con el individuo y completarán juntos la valoración de riesgos y controles (Sección 2).<br>
                El supervisor decidirá conjuntamente con el empleado el nivel de riesgo general y completará el plan de acción.
            </td>
        </tr>
    </table><br>

    <!-- OBSERVACIONES -->
    <table>
        <tr>
            <td colspan="4" class="sectionTitle">
                1. El supervisor completará la tabla de observaciones para identificar signos relevantes de fatiga
            </td>
        </tr>
        <tr  class="center">
            <th class="sectionTitle">Observaciones</th>
            <th class="sectionTitle">Físico</th>
            <th class="sectionTitle">Mental</th>
            <th class="sectionTitle">Emocional</th>
        </tr>
        <!-- COMPORTAMIENTOS GENERICOS -->
        <tr  class="center">
            <td rowspan="4">
                <b>Comportamientos genéricos</b>
            </td>
            <td>Frotarse los ojos</td>
            <td>Olvidos</td>
            <td>Irritabilidad</td>
        </tr>
        <tr  class="center">
            <td>Bostezar</td>
            <td rowspan="3">Se distrae fácilmente</td>
            <td>Poca comunicación</td>
        </tr>
        <tr  class="center">
            <td>Postura caída</td>
            <td rowspan="2">Hiperreactividad</td>
        </tr>
        <tr  class="center">
            <td>Parpadeo lento</td>
        </tr>
        <!-- COMPORTAMIENTOS ESPECIFICOS -->
        <tr class="center">
            <td rowspan="3">
                <b>Comportamientos específicos de la tarea</b>
            </td>
            <td>Reacción retardada</td>
            <td>Poca anticipación</td>
            <td>Retraído</td>
        </tr>
        <tr class="center">
            <td>Variabilidad de velocidad</td>
            <td>Lapsos de poca concentración</td>
            <td>Callado</td>
        </tr>
        <tr class="center">
            <td>Mala operación del equipo</td>
            <td>Desorientación espacial</td>
            <td>Respuestas retrasadas</td>
        </tr>
    </table><br>

    <!-- VALORACIÓN DE RIESGO -->
    <table>
        <tr>
            <td colspan="4" class="sectionTitle">
                2. El supervisor conversará con el individuo y completarán juntos la valoración de riesgos y controles
            </td>
        </tr>
        <tr class="center">
            <th width="50%">Coloca una X en la categoría de riesgo más adecuada para cada pregunta que se enumera a continuación</th>
            <th class="green">RIESGO BAJO</th>
            <th class="yellow">RIESGO MEDIO</th>
            <th class="red">RIESGO ALTO</th>
        </tr>
        <tr>
            <td>
                1. ¿Cuántas horas has dormido en las últimas 24 horas?
            </td>
            <td class="green center">
                7 o más<br>
                <span class="markX">
                    {{ $q1 == 0 ? 'X' : '' }}
                </span>
            </td>
            <td class="yellow center">
                5 a 7<br>
                <span class="markX">
                    {{ $q1 == 1 ? 'X' : '' }}
                </span>
            </td>
            <td class="red center">
                Menos de 5<br>
                <span class="markX">
                    {{ $q1 == 2 ? 'X' : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td>
                2. ¿Cuántas horas has dormido en las últimas 48 horas?
            </td>
            <td class="green center">
                14 o más<br>
                <span class="markX">
                    {{ $q2 == 0 ? 'X' : '' }}
                </span>
            </td>
            <td class="yellow center">
                12 a 14<br>
                <span class="markX">
                    {{ $q2 == 1 ? 'X' : '' }}
                </span>
            </td>
            <td class="red center">
                Menos de 12<br>
                <span class="markX">
                    {{ $q2 == 2 ? 'X' : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td>
                3. ¿Cuántas horas habrás estado despierto cuando termine tu turno?
            </td>
            <td class="green center">
                Menos de 14<br>
                <span class="markX">
                    {{ $q3 == 0 ? 'X' : '' }}
                </span>
            </td>
            <td class="yellow center">
                14 a 16<br>
                <span class="markX">
                    {{ $q3 == 1 ? 'X' : '' }}
                </span>
            </td>
            <td class="red center">
                Más de 16<br>
                <span class="markX">
                    {{ $q3 == 2 ? 'X' : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td>
                4. Escala de estado de alerta<br>
                <span class="small">
                    1 Te sientes activo, alerta o completamente despierto.<br>
                    2 Estás funcionando a un buen nivel, pero no al máximo.<br>
                    3 Sin energía ni totalmente alerta.<br>
                    4 Un poco aturdido, dificultad para concentrarte.<br>
                    5 Problemas para mantenerte despierto.
                </span>
            </td>
            <td class="green center">
                1 - 2<br>                
                 <span class="markX">
                    {{ $q4 == 0 ? 'X' : '' }}
                 </span>
            </td>
            <td class="yellow center">
                3<br>
                 <span class="markX">
                    {{ $q4 == 1 ? 'X' : '' }}
                 </span>
            </td>
            <td class="red center">
                4 - 5<br>
                 <span class="markX">
                    {{ $q4 == 2 ? 'X' : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td rowspan="2">
                5. ¿Cuántas bebidas alcohólicas consumiste antes de dormir?
            </td>
            <td class="green center">
                Hombre<br>
                0 - 4<br>
                <span class="markX">
                    {{ $q5 == 0 ? 'X' : '' }}
                </span>
            </td>
            <td class="yellow center">
                5 - 6<br>
                <span class="markX">
                    {{ $q5 == 1 ? 'X' : '' }}
                </span>
            </td>
            <td class="red center">
                7 o más<br>
                <span class="markX">
                    {{ $q5 == 2 ? 'X' : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="green center">
                Mujer<br>
                0 - 2
            </td>
            <td class="yellow center">
                3 - 4
            </td>
            <td class="red center">
                5 o más
            </td>
        </tr>
        <tr>
            <td>
                6. ¿Estás tomando algún medicamento u otras sustancias que puedan causar somnolencia o impedir que estés apto para el trabajo?
            </td>
            <td class="green center">
                NO<br>
                <span class="markX">
                    {{ $q6 == 0 ? 'X' : '' }}
                </span>
            </td>
            <td class="yellow center">
            </td>
            <td class="red center">
                SI
                <br>
                <span class="markX">
                    {{ $q6 == 2 ? 'X' : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td>
                7. ¿Tienes estrés, problemas de salud u otros problemas personales que estén afectando significativamente tu concentración y/o sueño?
            </td>
            <td class="green center">
                NO<br>
                <span class="markX">
                    {{ $q7 == 0 ? 'X' : '' }}
                </span>
            </td>
            <td class="yellow center">
            </td>
            <td class="red center">
                SI<br>
                <span class="markX">
                    {{ $q7 == 2 ? 'X' : '' }}
                </span>
            </td>
        </tr>
    </table><br>

    <!-- RESULTADO -->
    <table>
        <tr>
            <td width="30%" class="center">
                <b>Total</b>
            </td>
            @php
                $riskColor = '#4CAF50'; // verde por defecto

                if ($risk == 'medio') {
                    $riskColor = '#FFC107';
                }

                if ($risk == 'alto') {
                    $riskColor = '#F44336';
                }
            @endphp
            <td class="center totalBox"
                style="background: {{ $riskColor }}">
                {{ $total }}
            </td>
        </tr>
        <tr>
            <td class="center">
                <b>Nivel de riesgo</b>
            </td>
            <td class="center">
                {{ strtoupper($risk) }}
            </td>
        </tr>
    </table><br>

    <!-- aqui poner segun el nivel un recuadro con indicaciones o consejos -->
    @php

        $riskMessages = [

            'bajo' => "Nivel de riesgo bajo:

        El colaborador está:
        • Alerta, tolerante con los demás
        • Parpadeo normal (menos de 1 segundo)
        • Movimiento corporal coordinado

        Controles sugeridos:
        • Continuar monitoreo
        • Recordar estrategias de fatiga (interacción, ejercicio, aire frío, etc.)
        ",

            'medio' => "Nivel de riesgo medio:

        El colaborador presenta:
        • Irritable / impaciente
        • Cierre de párpados prolongado (1-2 seg)
        • Pensamientos errantes
        • Frotamiento de ojos o cara
        • Bostezos / movimientos inquietos

        Controles sugeridos:
        • Analizar causas de fatiga
        • Rotar tareas
        • Estrategias de alerta
        • Descansos cortos (20 min)
        • Trabajo en equipo
        • Retirar de tareas críticas
        ",

            'alto' => "Nivel de riesgo alto:

        El colaborador presenta signos graves:
        • Retraído / callado
        • Cierre de párpado prolongado (2+ seg)
        • Mirada fija / micro sueños

        Controles sugeridos:
        • Retirar inmediatamente del trabajo
        • Evaluar causas
        • Asignar tareas alternativas
        • Enviar a servicio médico si es necesario
        "

        ];

    @endphp
    @php
        $riskClass = 'riskLow';

        if($risk == 'medio'){
            $riskClass = 'riskMedium';
        }

        if($risk == 'alto'){
            $riskClass = 'riskHigh';
        }
    @endphp

    <table>
        <tr>
            <td class="sectionTitle center">
                3. Nivel de riesgo y controles sugeridos
            </td>
        </tr>
        <tr>
            <td class="riskBox {{ $riskClass }}">
                {{ $riskMessages[$risk] }}
            </td>
        </tr>
    </table>

    <br>

    <!-- ACCIONES -->
    <table>
        <tr>
            <td class="sectionTitle center">
                4. Acciones tomadas
            </td>
        </tr>
        <tr>
            <td class="center">
                {{ $actions }}
            </td>
        </tr>
    </table>

    <!-- FIRMA -->                    
    <br><br><br><br><br><br>
    <table style="width:100%; border:none;">
        <tr>
            <td class="center" style="border:none;">

                <!-- IMAGEN DE FIRMA (SI EXISTE) -->
                @if($realizoFirma)
                    <img src="{{ $realizoFirma }}" style="height:80px;"><br>
                @else
                    <br><br> <br><br>
                @endif

                <!-- LINEA -->
                <div style="width:300px; margin:0 auto; border-top:1px solid #000;"></div>

                <!-- NOMBRE -->
                <div style="margin-top:5px;">
                    <b>REALIZÓ:</b>
                    {{ strtoupper($evaluator->name ?? '') }}
                    {{ strtoupper($evaluator->a_paterno ?? '') }}
                    {{ strtoupper($evaluator->a_materno ?? '') }}
                </div>

            </td>
        </tr>
    </table>
</body>
</html>
