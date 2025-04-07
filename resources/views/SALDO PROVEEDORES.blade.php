<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte Saldo a Proveedores</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .container { width: 100%; padding: 10px; }
        .header { font-size: 22px; font-weight: bold; color: #103675; }
        .info-table, .details-table { clear: both; width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info-table td, .details-table th, .details-table td , .info-table th{ border: 1px solid #000; padding: 5px; text-align: left; }
        .details-table th, .info-table th { background-color: #103675; color: #ffffff; }
        .total { font-size: 24px; font-weight: bold; text-align: right; color: #e74c3c;}
        .column-1 { width: 25%; float: left; text-align: center; }
        .column-2 { width: 75%; float: left; }      
        #logo { flex: 3; text-align: left;   }   
        .logoImg{ width: 100%; margin: 0px 0 0px 0;  }
        .summary-table { margin-top: 15px; width: 100%; border-collapse: collapse; }
        .summary-table th, .summary-table td { border: 1px solid #000; padding: 8px; text-align: center; }
        .summary-table th { background-color: #103675; color: #ffffff; font-size: 14px; }
        .summary-table td { font-size: 16px; font-weight: bold; color: #103675; }
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
                REPORTE DE SALDO A PROVEEDORES
            </div>
        </div>
        <p><strong>Fecha:</strong> {{ $fecha }}</p>

        <!-- Sección de Concentrado de Saldos Totales -->
        <table class="summary-table">
            <thead>
                <tr>
                    <th style="color: #0ecb05;">Capital</th>
                    <th style="color: #fc4444;">Pago Autorizado</th>
                    <th style="color:rgb(252, 148, 51);">Saldo Disponible</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="color:rgb(13, 180, 4);">${{ number_format($capital ?? 0, 2) }}</td>
                    <td style="color:rgb(252, 51, 51);">${{ number_format($totalApagar ?? 0, 2) }}</td>
                    <td style="color:rgb(245, 129, 20);">${{ number_format($saldoDisponible ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="details-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>PROVEEDOR</th>
                    <th>CRÉDITO</th>
                    <th>BANCO</th>
                    <th>N° CUENTA</th>
                    <th>CLABE</th>
                    <th>
                        TOTAL ADEUDADO <br>
                        <span style="font-size: 18px; color: #fc4444; font-weight: bold;">${{ number_format($totalAdeudado ?? 0, 2) }}</span>
                    </th>
                    <th>
                        TOTAL A PAGAR <br>
                        <span style="font-size: 18px; color: #fc4444; font-weight: bold;">${{ number_format($totalApagar ?? 0, 2) }}</span>
                    </th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($supplierInfo as $supplier)
                    <tr>
                        <td>{{ $supplier['id'] ?? '' }}</td>
                        <td>{{ $supplier['supplier'] ?? '' }}</td>
                        <td>{{ $supplier['credit_sale'] ?? '' }}</td>
                        <td>{{ $supplier['banck'] ?? '' }}</td>
                        <td>{{ $supplier['account'] ?? '' }}</td>
                        <td>{{ $supplier['clabe'] ?? '' }}</td>
                        <td>${{ number_format($supplier['total_payments'] ?? 0, 2) }}</td>
                        <td>${{ number_format($supplier['payment'] ?? 0, 2) }}</td>
                        <td>{{ $supplier['comment'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>       
    </div>
</body>
</html>
