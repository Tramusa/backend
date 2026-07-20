<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>F-04-02 MMU ORDEN DE COMPRA</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #888;
            padding: 3px;
            vertical-align: middle;
        }

        .logo {
            width: 185px;
            max-height: 75px;
        }

        .blue {
            background: #006CC4;
            color: #FFF;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }

        .gray {
            background: #BFBFBF;
            color: #000;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .value {
            color: #1E5DBB;
        }

        .bold {
            font-weight: bold;
        }

        .no-border {
            border: none !important;
        }

        .line {
            border: none !important;
            border-bottom: 1px solid #888 !important;
        }

        .nowrap {
            white-space: nowrap;
        }

        .wrap {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .small {
            font-size: 8px;
        }

        .header-title {
            font-size: 13px;
            font-weight: bold;
        }

        .document-title {
            font-size: 14px;
            font-weight: bold;
        }

        .application-table .code-col {
            width: 32%;
        }

        .application-table .name-col {
            width: 68%;
        }

        .products-table {
            font-size: 8px;
        }

        .products-table td,
        .products-table th {
            padding: 2px;
        }

        .description-col {
            width: 22%;
        }

        .empty-row td {
            height: 18px;
        }

        .signature {
            height: 80px;
            vertical-align: bottom;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

<table>
    <tr>
        <td style="width:25%;" class="center">
            <img src="{{ $logoImage }}" class="logo">
        </td>
        <td style="width:75%; padding:0;">
            <table>
                <tr>
                    <td class="center no-border header-title">
                        MULTISERVICIOS MURILLO SA DE CV
                    </td>
                </tr>
                <tr>
                    <td class="center no-border document-title">
                        ORDEN DE COMPRA O SERVICIO
                    </td>
                </tr>
                <tr class="blue">
                    <td>
                        AREA: ADMINISTRACI&Oacute;N &nbsp;&nbsp;
                        F-04-02 &nbsp;&nbsp;
                        FRECUENCIA: CUANDO SE REQUIERA &nbsp;&nbsp;
                        RESGUARDO: 5 A&Ntilde;OS &nbsp;&nbsp;
                        REVISI&Oacute;N: JULIO 2021 R1
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

<table class="no-border">
    <tr>
        <td class="no-border" style="width:70%; vertical-align:top;">
            <table class="no-border">
                <tr>
                    <td class="no-border right nowrap" style="width:95px;">
                        <b>FECHA:</b>
                    </td>
                    <td class="line value bold">
                        {{ $fecha ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="no-border right nowrap">
                        <b>NOMBRE:</b>
                    </td>
                    <td class="line value wrap">
                        {{ $Data->supplier->business_name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="no-border right nowrap">
                        <b>CONTACTO:</b>
                    </td>
                    <td class="line wrap">
                        {{ $Data->supplier->name_contact ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="no-border right nowrap">
                        <b>E-MAIL:</b>
                    </td>
                    <td class="line wrap small">
                        {{ $Data->supplier->e_mail ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="no-border right nowrap">
                        <b>TEL:</b>
                    </td>
                    <td class="line">
                        {{ $Data->supplier->phone ?? '-' }}
                    </td>
                </tr>
            </table>
        </td>

        <td class="no-border" style="width:30%; vertical-align:top; padding-left:15px;">
            <table style="margin-bottom:12px;">
                <tr>
                    <td class="gray center">
                        NO. DE REQUISICI&Oacute;N
                    </td>
                </tr>
                <tr>
                    <td class="center value bold" style="font-size:13px;">
                        OPE-{{ $Data->requisition->id ?? '-' }}
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td class="gray center">
                        NO. DE ORDEN DE COMPRA
                    </td>
                </tr>
                <tr>
                    <td class="center value bold" style="font-size:13px;">
                        OC-{{ $Data->id ?? '-' }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table><br>

<table class="no-border">
    <tr>
        <td class="no-border" style="width:32%; vertical-align:top;">
            <table>
                <tr>
                    <td class="gray">
                        AREA SOLICITANTE:
                    </td>
                </tr>
                <tr>
                    <td class="wrap">
                        {{ $Data->requisition->work_areaInfo->name ?? '-' }}
                    </td>
                </tr>
            </table><br>
            <table>
                <tr>
                    <td class="gray">
                        NOMBRE SOLICITANTE:
                    </td>
                </tr>
                <tr>
                    <td class="wrap">
                        {{ $Data->requisition->collaboratorInfo->name ?? '-' }}
                    </td>
                </tr>
            </table><br>

            <table>
                <tr>
                    <td class="gray">
                        FECHA DE SUMINISTRO:
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ $Data->date_supply ?? '-' }}
                    </td>
                </tr>
            </table>
        </td>

        <td class="no-border" style="width:4%;"></td>

        <td class="no-border" style="width:64%; vertical-align:top;">
            <table>
                <tr>
                    <td class="gray center">AREA DE APLICACI&Oacute;N</td>
                </tr>
            </table>

            <table class="application-table">
                <colgroup>
                    <col class="code-col">
                    <col class="name-col">
                </colgroup>

                <tr>
                    <td class="gray">CODIGO CUENTA PADRE</td>
                    <td class="gray"> NOMBRE DE LA CUENTA MAYOR</td>
                </tr>
                <tr>
                    <td>
                        {{ $Data->requisition->parent_accountInfo->id ?? '-' }}
                    </td>
                    <td class="value wrap">
                        {{ $Data->requisition->parent_accountInfo->name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="gray">CODIGO CUENTA DE TITULO</td>
                    <td class="gray">NOMBRE DE LA CUENTA DE TITULO</td>
                </tr>
                <tr>
                    <td>
                        {{ $Data->requisition->title_accountInfo->id ?? '-' }}
                    </td>
                    <td class="value wrap">
                        {{ $Data->requisition->title_accountInfo->name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="gray">CODIGO CUENTA DE SUBTITULO</td>
                    <td class="gray">NOMBRE DE LA CUENTA DE SUBCUENTA</td>
                </tr>
                <tr>
                    <td>
                        {{ $Data->requisition->subtitle_accountInfo->id ?? '-' }}
                    </td>
                    <td class="value wrap">
                        {{ $Data->requisition->subtitle_accountInfo->name ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="gray">CODIGO CUENTA DE MAYOR</td>
                    <td class="gray">NOMBRE DE LA CUENTA DE SUB-SUBCUENTA</td>
                </tr>
                <tr>
                    <td>
                        {{ $Data->requisition->mayor_accountInfo->id ?? '-' }}
                    </td>
                    <td class="value wrap">
                        {{ $Data->requisition->mayor_accountInfo->name ?? '-' }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table><br>

<p style="text-align:center;font-size:11px;font-weight:bold;margin:4px 0 8px 0;">
    EN LA ENTREGA DE LA MERCANC&Iacute;A, FAVOR DE ANEXAR COPIA DE ESTA ORDEN DE PEDIDO Y COPIA DE FACTURA
</p>

<table class="products-table">
    <tr class="gray center">
        <td style="width:5%;">CANT.</td>
        <td style="width:7%;">NUM. ART.</td>
        <td style="width:6%;">U.M.</td>
        <td class="description-col">DESCRIPCI&Oacute;N</td>
        <td style="width:6%;">IEPS</td>
        <td style="width:6%;">% IVA</td>
        <td style="width:7%;">%RET IVA</td>
        <td style="width:7%;">%RET ISR</td>
        <td style="width:6%;">%ISN</td>
        <td style="width:10%;">P. UNITARIO</td>
        <td style="width:10%;">SUBTOTAL</td>
        <td style="width:11%;">TOTAL</td>
    </tr>

    @php
        $granSubtotal = 0;
        $granTotal = 0;
        $productRows = 0;
    @endphp

    @forelse($Data->requisition->products ?? [] as $product)
        @php
            $productRows++;

            $cantidad = (float) ($product->cantidad ?? 0);
            $precio = (float) ($product->price ?? 0);

            $ieps = (float) ($product->ieps ?? 0);
            $iva = (float) ($product->iva ?? 0);
            $retIva = (float) ($product->ret_iva ?? 0);
            $isr = (float) ($product->isr ?? 0);
            $isn = (float) ($product->ret_ish ?? 0);

            $subtotal = $cantidad * $precio;

            $iepsMonto = $subtotal * ($ieps / 100);
            $ivaMonto = $subtotal * ($iva / 100);
            $retIvaMonto = $subtotal * ($retIva / 100);
            $isrMonto = $subtotal * ($isr / 100);
            $isnMonto = $subtotal * ($isn / 100);

            $total = $subtotal + $iepsMonto + $ivaMonto - $retIvaMonto - $isrMonto - $isnMonto;

            $granSubtotal += $subtotal;
            $granTotal += $total;
        @endphp

        <tr>
            <td class="center">
                {{ number_format($cantidad, 2) }}
            </td>

            <td class="center wrap">
                {{ $product->id ?? '-' }}
            </td>

            <td class="center wrap">
                {{ $product->unit_measure ?? '-' }}
            </td>

            <td class="wrap">
                {{ $product->name ?? '-' }}
            </td>

            <td class="center nowrap">
                {{ number_format($ieps, 2) }}%
            </td>

            <td class="center nowrap">
                {{ number_format($iva, 2) }}%
            </td>

            <td class="center nowrap">
                {{ number_format($retIva, 2) }}%
            </td>

            <td class="center nowrap">
                {{ number_format($isr, 2) }}%
            </td>

            <td class="center nowrap">
                {{ number_format($isn, 2) }}%
            </td>

            <td class="right nowrap">
                ${{ number_format($precio, 2) }}
            </td>

            <td class="right nowrap">
                ${{ number_format($subtotal, 2) }}
            </td>

            <td class="right nowrap">
                ${{ number_format($total, 2) }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="12" class="center">
                No hay productos registrados
            </td>
        </tr>
    @endforelse

    @for ($i = $productRows; $i < 10; $i++)
        <tr class="empty-row">
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endfor

    <tr>
        <td colspan="10" class="right bold">
            TOTAL GENERAL
        </td>

        <td class="right bold nowrap">
            ${{ number_format($granSubtotal, 2) }}
        </td>

        <td class="right bold nowrap">
            ${{ number_format($Data->total, 2) }}
        </td>
    </tr>
</table><br><br>

<table>
    <tr>
        <td class="signature">
            <br><br><br>
            {{ $Data->performInfo->name ?? '-' }}
            {{ $Data->performInfo->a_paterno ?? '' }}
            {{ $Data->performInfo->a_materno ?? '' }}
            <br>
            {{ $Data->performInfo->rol ?? '-' }}
        </td>

        <td class="signature">
            <br><br><br>
            {{ $Data->authorizeInfo->name ?? '-' }}
            {{ $Data->authorizeInfo->a_paterno ?? '' }}
            {{ $Data->authorizeInfo->a_materno ?? '' }}
            <br>
            {{ $Data->authorizeInfo->rol ?? '-' }}
        </td>
    </tr>

    <tr>
        <td class="gray center" style="width:50%;">
            ELABOR&Oacute;
        </td>

        <td class="gray center" style="width:50%;">
            AUTORIZ&Oacute;
        </td>
    </tr>
</table>

</body>
</html>