<!DOCTYPE html>
<html>
<head>
    <title>Bitacora de Mantenimiento</title>
    <style>
      table {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 10px;
      }

      table th {
        font-family: "Arial Narrow", Arial, sans-serif;
        text-align: center;
        padding: 5px 5px;
        color: #FFFFFF;
        font-size: 1em;
        border: 1px solid #000;
        white-space: nowrap;        
        font-weight: bold;
        background: #1E4E79;
      }

      table td {
        text-align: center;
        border: 1px solid #000;
      }
    </style>
</head>
<body>
    <h2>Bitacora de Mantenimiento</h2>
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Fecha <br>Finalizado</th>
                <th>Unidad</th>
                <th>Odometro</th>
                <th>Operador</th>
                <th>Tipo</th>
                <th>Falla</th>
                <th>Descripcion</th>
                <th>Tiempo Mtto</th>
                <th>Costo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->date_attended }}</td>
                    <td>{{ $order->no_economic}}</td>
                    <td>{{ $order->odometro ?? ' ' }}</td>
                    <td>{{ $order->name }} {{ $order->a_paterno }} {{ $order->a_materno }}</td>
                    <td>{{ $order->type_mtto }}</td>
                    <td>{{ $order->fallas[0] }}</td>
                    <td>{{ $order->repair }}</td>
                    <td>{{ $order->time }} min.</td>
                    <td>${{ number_format($order->total_mano + $order->total_parts, 2) }}</td>                    
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
