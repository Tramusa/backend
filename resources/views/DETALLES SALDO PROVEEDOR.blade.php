<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Saldo Adeudado</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; padding: 10px; }
        .header { font-size: 22px; font-weight: bold; color: #103675; }
        .info-table, .details-table { clear: both; width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info-table td, .details-table th, .details-table td , .info-table th{ border: 1px solid #000; padding: 3px; text-align: left; }
        .details-table th, .info-table th { background-color: #103675; color: #ffffff; }
        .total { font-size: 24px; font-weight: bold; text-align: right; color: #e74c3c;}
        .column-1 { width: 25%; float: left; text-align: center; }
        .column-2 { width: 75%; float: left; }      
        #logo { flex: 3; text-align: left;   }   
        .logoImg{ width: 100%; margin: 0px 0 0px 0;  }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="column-1">
            <div id="logo">
                <img class="logoImg" src="{{ $logoImage }}">
            </div>
        </div>
        <div class="column-2"><br><br>
            <div class="header">
                REPORTE DE SALDO ADEUDADO A PROVEEDOR
            </div>
        </div>
        <p><strong>Fecha:</strong> {{ $fecha }}</p>
        <!-- Total -->
        <p class="total"><strong>Total: ${{ number_format($totalAdeudado, 2) }}</strong></p>
        <!-- Supplier Info -->
        <table class="info-table">
            <tr>
                <th><strong>Proveedor:</strong></td>
                <td colspan="2">{{ $supplierInfo['supplier'] }}</td>
                <th><strong>Área:</strong></td>
                <td colspan="2">{{ $supplierInfo['area'] }}</td>
            </tr>
            <tr>
                <th><strong>Crédito:</strong></td>
                <td colspan="2">{{ $supplierInfo['credit'] }}</td>
                <th><strong>Vigencia del Crédito:</strong></td>
                <td colspan="2">{{ $supplierInfo['credit_days'] }} días</td>
            </tr>
            <tr>
                <th><strong>Banco:</strong></td>
                <td>{{ $supplierInfo['bank'] }}</td>
                <th><strong>Número de Cuenta:</strong></td>
                <td>{{ $supplierInfo['account'] }}</td>
                <th><strong>Clabe:</strong></td>
                <td>{{ $supplierInfo['clabe'] }}</td>
            </tr>
        </table>

        <!-- Orders Details -->
        <h3>Detalles de Órdenes y Facturas</h3>
        <table class="details-table">
            <thead>
                <tr>
                    <th># Orden Compra</th>
                    <th>Cuenta</th>
                    <th>Factura</th>
                    <th>Fecha Factura</th>
                    <th>Método de Pago</th>
                    <th>Forma de Pago</th>
                    <th>Importe</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order['order_id'] }}</td>
                        <td>{{ $order['account'] }}</td>
                        <td>{{ $order['invoice'] }}</td>
                        <td>{{ $order['invoice_date'] }}</td>
                        <td>{{ $order['payment_method'] }}</td>
                        <td>{{ $order['payment_form'] }}</td>
                        <td>${{ number_format($order['total'], 2) }}</td>
                        <td>{{ $order['additional'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        
    </div>
</body>
</html>
