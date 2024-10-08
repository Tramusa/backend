<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>F-04-01 R1 ORDEN DE PAGO</title>
    <style>
      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }

      header {
        padding: 0px 0px;
      }

      .column-1 {
        width: 25%;
        float: left;
        text-align: center;
      }

      .column-2 {
        width: 75%;
        float: left;
      }

      .column-50 {
        width: 50%;
        float: left;
      }

      .row{
        display: table;
        width: 100%;
        clear: both;
      }

      .column-2-1 {
        width: 56%;
        float: left; 
      }

      .column-2-2 {
        width: 44%;
        float: left;
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
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
      }
      
      #logo {
        flex: 3;
        text-align: left;       
      }     

      .logoImg{
        width: 100%;
        margin: 5px 0 5px 0;
      }

      h2 {
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
        color: #FFFFFF;
        font-size: 1.2em;
        font-weight: bold;
        text-align: center;
        margin: 0px 0 0px 0;
        padding: 5px 5px;
        background: #f79118;
      }

      .title {
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
        color: #000000;
        font-size: 1.5em;
        font-weight: bold;
        text-align: center;
        margin: 0px 0 0px 0;
      }
      .title-blue {
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
        color: #080784;;
        font-size: 1.2em;
        font-weight: bold;
        margin: 0px 0 0px 0;
      }
      
      table {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 10px;
      }

      table th {
        font-family: "Helvetica", "Arial Narrow", Arial, sans-serif;
        text-align: center;
        padding: 0px 5px;
        color: #FFFFFF;
        font-size: 1.1em;
        border: 1px solid #000;      
        font-weight: bold;
        background: #1E4E79;
      }

      table td {
        font-size: 1.1em;
        padding: 5px 5px;
      }   

      .yellow-bg {
        background-color: #ffa121; /* Yellow background */
        color: #FFF; /* White text */
        font-weight: bold;
      }

      .blue-bg {
        background-color: #080784; /* Yellow background */
        color: #FFF; /* White text */
        font-weight: bold;
      }

      .bottom-border-only {
        border-bottom: 1px solid #000; /* Only bottom border */
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
      <div class="column-2"><br>
        <p class="title">ORDEN DE PAGO</p>
        <h2>ÁREA: ADMINISTRACIÓN     .     F-04-01 R1 </h2>
      </div>      
    </header>
    <main> 
      <div class="row">
        <table style="width: 100%;"><br>
            <tr>
                <td class="yellow-bg" style="width: 16%;">FOLIO</td>
                <td class="bottom-border-only" style="width: 24%; font-weight: bold;">F-04-01-{{ $Data->requisition->id ?? '-' }}</td>
                <td class="yellow-bg" style="width: 14%;">FECHA </td>
                <td class="bottom-border-only" style="width: 36%; font-weight: bold;">{{ $fecha ?? '-' }}</td>
            </tr>
        </table>
      </div>  
      <div class="row">
        <table style="width: 100%;">
            <tr>
                <td class="yellow-bg" style="width: 26%;">NOMBRE PROVEEDOR</td>
                <td class="yellow-bg" style="width: 26%;">CONTACTO PROVEEDOR</td>
                <td class="yellow-bg" style="width: 26%;">TELEFONO PROVEEDOR</td>
                <td class="yellow-bg" style="width: 22%;">EMAIL PROVEEDOR</td>
            </tr>
            <tr>
                <td class="bottom-border-only">{{ $Data->requisition->work_areaInfo->name ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->requisition->collaboratorInfo->name ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->requisition->collaboratorInfo->name ?? '-' }}</td>
                <td class="bottom-border-only">{{ $Data->requisition->parent_accountInfo->name ?? '-' }} - {{ $Data->requisition->title_accountInfo->name ?? '' }} - {{ $Data->requisition->subtitle_accountInfo->name ?? '' }} - {{ $Data->requisition->mayor_accountInfo->name ?? '' }}</td>
            </tr>
        </table>
      </div>    
      <div class="row">
        <table style="width: 100%;"><br>
          <tr>
            <td class="blue-bg">O. COMPRA </td>
            <td class="blue-bg">CTA </td>
            <td class="blue-bg">F. FACTURA </td>
            <td class="blue-bg">FEC. FACTURA </td>
            <td class="blue-bg">IMPORTE</td>
            <td class="blue-bg">M.PAGO</td>
            <td class="blue-bg">SU PAGO</td>
          </tr>
          @php
            // Inicializar variables
            $subtotal = 0;
            $totalIva = 0;
            $totalRetIva = 0;
            $totalRetIsh = 0;
            $totalIsr = 0;
          @endphp
          @forelse ($Data->requisition->products as $product)
            @php 
            // Obtener los valores de los atributos del producto
            $price = $product->price ?? 0;
            $cantidad = $product->cantidad ?? 0;
            $iva = $product->iva ?? 0;
            $isr = $product->isr ?? 0;
            $ret_iva = $product->ret_iva ?? 0;
            $ret_ish = $product->ret_ish ?? 0;

            // Convertir porcentajes a decimales
            $ivaDecimal = $iva / 100;
            $isrDecimal = $isr / 100;
            $retIvaDecimal = $ret_iva / 100;
            $retIshDecimal = $ret_ish / 100;

            // Calcular los diferentes montos
            $priceWithIva = $price * (1 + $ivaDecimal);
            $ivaAmount = $price * $ivaDecimal;
            $retIvaAmount = $price * $retIvaDecimal;
            $retIshAmount = $price * $retIshDecimal;
            $isrAmount = $price * $isrDecimal;

            // Calcular el precio neto y el total para el producto
            $netPrice = $priceWithIva - $retIvaAmount + $retIshAmount - $isrAmount;
            $totalAmountForProduct = $cantidad * $netPrice;

            // Acumular los subtotales y totales
            $subtotal += $cantidad * $price;
            $totalIva += $cantidad * $ivaAmount;
            $totalRetIva += $cantidad * $retIvaAmount;
            $totalRetIsh += $cantidad * $retIshAmount;
            $totalIsr += $cantidad * $isrAmount;
          @endphp
            <tr>
                <td class="bottom-border-only">{{ $cantidad }}</td>
                <td class="bottom-border-only">{{ $product->id ?? '-' }}</td>
                <td class="bottom-border-only">{{ $product->unit_measure ?? '-' }}</td>
                <td class="bottom-border-only">{{ $product->name ?? '-' }}</td>
                <td class="bottom-border-only">${{ number_format($price, 2) }}</td>
                <td class="bottom-border-only">{{ number_format($iva, 2) }}%</td>
                <td class="bottom-border-only">${{ number_format($totalAmountForProduct, 2) }}</td>
            </tr>
          @empty
            <tr>
                <td colspan="7" class="bottom-border-only">No hay detalles disponibles</td>
            </tr>
          @endforelse
        </table>
      </div>   
      <div class="row">
        <div class="column-2-1">
          <p class="title-blue">OBSERVACIONES</p>
          <div style="border: 1px solid #000; min-height: 100px; padding: 10px;">
              {{ $Data->additional ?? 'No hay información adicional' }}
          </div>
        </div>
        <!-- Totals Table Section -->
        <div class="column-2-2">
          <table style="width: 100%; border: 0;">
            <tr>
                <td class="title-blue" style="width: 48%; text-align: right;">SUBTOTAL</td>
                <td class="bottom-border-only" style="width: 52%; text-align: right; font-weight: bold;">${{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="title-blue" style="width: 48%; text-align: right;">I.V.A.</td>
                <td class="bottom-border-only" style="width: 52%; text-align: right; font-weight: bold;">${{ number_format($totalIva, 2) }}</td>
            </tr>
            <tr>
                <td class="title-blue" style="width: 48%; text-align: right;">SUBTOTAL + I.V.A.</td>
                <td class="bottom-border-only" style="width: 52%; text-align: right; font-weight: bold;">${{ number_format($subtotal+$totalIva, 2) }}</td>
            </tr>    
            <tr>
                <td class="title-blue" style="width: 48%; text-align: right;">- RET. I.V.A.</td>
                <td class="bottom-border-only" style="width: 52%; text-align: right; font-weight: bold;">${{ number_format($totalRetIva, 2) }}</td>
            </tr>
            <tr>
                <td class="title-blue" style="width: 48%; text-align: right;">- RET I.S.R.</td>
                <td class="bottom-border-only" style="width: 52%; text-align: right; font-weight: bold;">${{ number_format($totalIsr, 2) }}</td>
            </tr>
            <tr>
                <td class="title-blue" style="width: 48%; text-align: right;">I.S.H.</td>
                <td class="bottom-border-only" style="width: 52%; text-align: right; font-weight: bold;">${{ number_format($totalRetIsh, 2) }}</td>
            </tr>
            <tr>
                <td class="title-blue" style="width: 48%; text-align: right;">TOTAL</td>
                <td class="bottom-border-only" style="width: 52%; text-align: right; font-weight: bold;">${{ number_format($Data->total, 2) }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="row">
        <table style="width: 100%; border: 1px solid #000;">
            <tr>
                <td class="bottom-border-only" style="border: 1px solid #000; font-weight: bold; text-align: center;">
                    <br><br><br><br><br>
                    {{ $Data->performInfo->name ?? '-' }} {{ $Data->performInfo->a_paterno ?? '-' }} {{ $Data->performInfo->a_materno ?? '-' }}<br>
                    {{ $Data->performInfo->rol ?? '-' }}
                </td>
                <td class="bottom-border-only" style="border: 1px solid #000; font-weight: bold; text-align: center;">
                    <br><br><br><br><br>
                    {{ $Data->authorizeInfo->name ?? '-' }} {{ $Data->authorizeInfo->a_paterno ?? '-' }} {{ $Data->authorizeInfo->a_materno ?? '-' }}<br>
                    {{ $Data->authorizeInfo->rol ?? '-' }}
                </td>
            </tr>
            <tr>
                <td class="blue-bg" style="width: 33%; text-align: center;">ELABORÓ </td>
                <td class="blue-bg" style="width: 34%; text-align: center;">AUTORIZACIÓN </td>
            </tr>
        </table>
      </div>             
    </main>
  </body>
</html>