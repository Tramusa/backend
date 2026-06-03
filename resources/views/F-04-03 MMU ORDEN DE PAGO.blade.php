<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>F-04-03 MMU ORDEN DE PAGO</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #888;
            padding: 4px;
            vertical-align: middle;
        }

        .logo {
            width: 190px;
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

        .small {
            font-size: 8px;
        }

        .nowrap {
            white-space: nowrap;
        }

        .wrap {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .application-table .code-col {
            width: 32%;
        }

        .application-table .name-col {
            width: 68%;
        }

        .signature {
            height: 80px;
            vertical-align: bottom;
            text-align: center;
            font-weight: bold;
        }

        .observaciones {
            height: 75px;
            vertical-align: top;
        }
    </style>
</head>

<body>

@php
    $billings = collect($Data->billings ?? []);

    $firstOrder = null;

    foreach ($billings as $billingItem) {
        foreach (($billingItem->purchaseOrders ?? []) as $orderItem) {
            $firstOrder = $orderItem;
            break 2;
        }
    }

    $req = $firstOrder->requisition ?? null;

    $orderNumbers = [];

    foreach ($billings as $billingItem) {
        foreach (($billingItem->purchaseOrders ?? []) as $orderItem) {
            if (!empty($orderItem->id)) {
                $orderNumbers[] = $orderItem->id;
            }
        }
    }

    $orderNumbers = array_unique($orderNumbers);

    $purchaseOrderText = count($orderNumbers)
        ? collect($orderNumbers)->map(function ($id) {
            return 'OC-' . number_format((float) $id, 0, '.', ',');
        })->implode(', ')
        : '-';

    $requestNumber = $req->folio
        ?? $req->request_number
        ?? $req->number
        ?? (!empty($req->id) ? 'OPE-' . $req->id : '-');

    $paymentNumber = $Data->folio ?? $Data->id ?? '-';

    $areaSolicitante = $req->work_areaInfo->name
        ?? $req->area_solicitante
        ?? $req->area
        ?? '-';

    $nombreSolicitante = $req->collaboratorInfo->name
        ?? $req->nombre_solicitante
        ?? $req->requester
        ?? '-';

    $fechaSuministro = $req->date_supply
        ?? $req->fecha_suministro
        ?? '-';

    $parentCode = $req->parent_accountInfo->id
        ?? $req->id_parent_account
        ?? '-';

    $parentName = $req->parent_accountInfo->name
        ?? $req->name_parent_account
        ?? '-';

    $titleCode = $req->title_accountInfo->id
        ?? $req->id_title_account
        ?? '-';

    $titleName = $req->title_accountInfo->name
        ?? $req->name_title_account
        ?? '-';

    $subtitleCode = $req->subtitle_accountInfo->id
        ?? $req->id_subtitle_account
        ?? '-';

    $subtitleName = $req->subtitle_accountInfo->name
        ?? $req->name_subtitle_account
        ?? '-';

    $mayorCode = $req->mayor_accountInfo->id
        ?? $req->id_mayor_account
        ?? '-';

    $mayorName = $req->mayor_accountInfo->name
        ?? $req->name_mayor_account
        ?? 'NA';

    $totalPayment = (float) ($Data->payment ?? 0);

    if ($totalPayment <= 0) {
        foreach ($billings as $billingItem) {
            $billingTotal = $billingItem->total ?? $billingItem->amount ?? 0;

            if ($billingTotal <= 0) {
                foreach (($billingItem->purchaseOrders ?? []) as $orderItem) {
                    $billingTotal += (float) ($orderItem->total ?? 0);
                }
            }

            $totalPayment += (float) $billingTotal;
        }
    }

    $totalEntero = floor($totalPayment);
    $totalDecimal = (int) round(($totalPayment - $totalEntero) * 100);

    if (class_exists('NumberFormatter')) {
        $formatter = new NumberFormatter('es_MX', NumberFormatter::SPELLOUT);
        $importeLetra = mb_strtoupper($formatter->format($totalEntero), 'UTF-8') . ' PESOS ' . str_pad($totalDecimal, 2, '0', STR_PAD_LEFT) . '/100 M.N.';
    } else {
        $importeLetra = 'IMPORTE EN LETRA PESOS ' . str_pad($totalDecimal, 2, '0', STR_PAD_LEFT) . '/100 M.N.';
    }
@endphp

<table>
    <tr>
        <td style="width:25%;" class="center">
            <img src="{{ $logoImage }}" class="logo">
        </td>

        <td style="width:75%; padding:0;">
            <table>
                <tr>
                    <td class="center no-border" style="font-size:13px;">
                        MULTISERVICIOS MURILLO SA DE CV
                    </td>
                </tr>

                <tr>
                    <td class="center no-border" style="font-size:14px;font-weight:bold;">
                        ORDEN DE PAGO
                    </td>
                </tr>

                <tr class="blue">
                    <td>
                        AREA: ADMINISTRACI&Oacute;N &nbsp;&nbsp;
                        F-04-03 &nbsp;&nbsp;
                        FRECUENCIA: CUANDO SE REQUIERA &nbsp;&nbsp;
                        RESGUARDO: 5 A&Ntilde;OS &nbsp;&nbsp;
                        REVISI&Oacute;N: JULIO 2020
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
                    <td class="no-border right nowrap" style="width:120px;"><b>FECHA:</b></td>
                    <td class="line value bold">{{ $fecha ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="no-border right nowrap"><b>NOMBRE:</b></td>
                    <td class="line wrap">{{ $Data->supplierInfo->business_name ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="no-border right nowrap"><b>CONTACTO:</b></td>
                    <td class="line wrap">{{ $Data->supplierInfo->name_contact ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="no-border right nowrap"><b>E-MAIL:</b></td>
                    <td class="line wrap small">{{ $Data->supplierInfo->e_mail ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="no-border right nowrap"><b>TEL:</b></td>
                    <td class="line">{{ $Data->supplierInfo->phone ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="no-border right nowrap"><b>BANCO:</b></td>
                    <td class="line">{{ $Data->supplierInfo->firstBankDetail->banck ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="no-border right nowrap"><b>NUMERO DE CUENTA:</b></td>
                    <td class="line">{{ $Data->supplierInfo->firstBankDetail->account ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="no-border right nowrap"><b>CLABE:</b></td>
                    <td class="line">{{ $Data->supplierInfo->firstBankDetail->clabe ?? '-' }}</td>
                </tr>
            </table>
        </td>

        <td class="no-border" style="width:30%; vertical-align:top; padding-left:15px;">
            <table style="margin-bottom:12px;">
                <tr>
                    <td class="gray center">NO. DE REQUISICI&Oacute;N</td>
                </tr>
                <tr>
                    <td class="center">{{ $requestNumber }}</td>
                </tr>
            </table>

            <table style="margin-bottom:12px;">
                <tr>
                    <td class="gray center">NO. ORDEN DE COMPRA</td>
                </tr>
                <tr>
                    <td class="center">{{ $purchaseOrderText }}</td>
                </tr>
            </table>

            <table>
                <tr>
                    <td class="gray center">NO. ORDEN DE PAGO</td>
                </tr>
                <tr>
                    <td class="center value bold">{{ $paymentNumber }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

<table class="no-border">
    <tr>
        <td class="no-border" style="width:32%; vertical-align:top;">
            <table>
                <tr>
                    <td class="gray">AREA SOLICITANTE:</td>
                </tr>
                <tr>
                    <td class="wrap">{{ $areaSolicitante }}</td>
                </tr>
            </table>

            <br>

            <table>
                <tr>
                    <td class="gray">NOMBRE SOLICITANTE:</td>
                </tr>
                <tr>
                    <td class="wrap">{{ $nombreSolicitante }}</td>
                </tr>
            </table>

            <br>

            <table>
                <tr>
                    <td class="gray">FECHA DE SUMINISTRO:</td>
                </tr>
                <tr>
                    <td>{{ $fechaSuministro }}</td>
                </tr>
            </table>
        </td>

        <td class="no-border" style="width:4%;"></td>

        <td class="no-border" style="width:64%; vertical-align:top;">
            <table>
                <tr>
                    <td class="gray center">AREA DE APLICACI&Oacute;N:</td>
                </tr>
            </table>

            <table class="application-table">
                <colgroup>
                    <col class="code-col">
                    <col class="name-col">
                </colgroup>

                <tr>
                    <td class="gray">CODIGO CUENTA PADRE</td>
                    <td class="gray">NOMBRE DE LA CUENTA MAYOR</td>
                </tr>
                <tr>
                    <td>{{ $parentCode }}</td>
                    <td class="wrap">{{ $parentName }}</td>
                </tr>

                <tr>
                    <td class="gray">CODIGO CUENTA DE TITULO</td>
                    <td class="gray">NOMBRE DE LA CUENTA DE TITULO</td>
                </tr>
                <tr>
                    <td>{{ $titleCode }}</td>
                    <td class="wrap">{{ $titleName }}</td>
                </tr>

                <tr>
                    <td class="gray">CODIGO CUENTA DE SUBTITULO</td>
                    <td class="gray">NOMBRE DE LA CUENTA DE SUBCUENTA</td>
                </tr>
                <tr>
                    <td>{{ $subtitleCode }}</td>
                    <td class="wrap">{{ $subtitleName }}</td>
                </tr>

                <tr>
                    <td class="gray">CODIGO CUENTA DE MAYOR</td>
                    <td class="gray">NOMBRE DE LA CUENTA DE SUB-SUBCUENTA</td>
                </tr>
                <tr>
                    <td>{{ $mayorCode }}</td>
                    <td class="wrap">{{ $mayorName }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

<table>
    <tr class="gray center">
        <td rowspan="2" style="width:15%;">FOLIO INTERNO<br>FACTURA</td>
        <td colspan="2">FACTURA</td>
        <td colspan="2">NOTA DE CREDITO</td>
        <td rowspan="2" style="width:18%;">TOTAL A PAGAR</td>
    </tr>

    <tr class="gray center">
        <td style="width:18%;">FECHA</td>
        <td style="width:15%;">IMPORTE TOTAL</td>
        <td style="width:20%;">FOLIO INTERNO</td>
        <td style="width:12%;">IMPORTE</td>
    </tr>

    @php $rows = 0; @endphp

    @foreach ($billings as $billing)
        @php
            $billingOrders = collect($billing->purchaseOrders ?? []);
            $rowTotal = (float) ($billing->total ?? $billing->amount ?? 0);

            if ($rowTotal <= 0) {
                foreach ($billingOrders as $orderItem) {
                    $rowTotal += (float) ($orderItem->total ?? 0);
                }
            }

            $rows++;
        @endphp

        <tr>
            <td class="center value wrap">{{ $billing->folio ?? '-' }}</td>
            <td class="center value">{{ $billing->date ?? '-' }}</td>
            <td class="right value nowrap">$ {{ number_format($rowTotal, 2) }}</td>
            <td class="center wrap">{{ $billing->credit_note_folio ?? $billing->folio_nota_credito ?? '' }}</td>
            <td class="right nowrap">
                @if (!empty($billing->credit_note_amount) || !empty($billing->importe_nota_credito))
                    $ {{ number_format((float) ($billing->credit_note_amount ?? $billing->importe_nota_credito), 2) }}
                @endif
            </td>
            <td class="right nowrap">$ {{ number_format($rowTotal, 2) }}</td>
        </tr>
    @endforeach

    @for ($i = $rows; $i < 10; $i++)
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="right">$ -</td>
        </tr>
    @endfor
</table>

<br>

<table>
    <tr>
        <td class="center bold" style="width:82%;">
            {{ $importeLetra }}
        </td>
        <td class="right bold nowrap" style="width:18%;">
            ${{ number_format($totalPayment, 2) }}
        </td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td class="gray">OBSERVACIONES:</td>
    </tr>
    <tr>
        <td class="observaciones wrap">
            {{ $Data->reference ?? '' }}
        </td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td class="signature">
            <br><br><br>
            {{ $Data->elaborateInfo->name ?? '-' }}
            {{ $Data->elaborateInfo->a_paterno ?? '' }}
            {{ $Data->elaborateInfo->a_materno ?? '' }}
            <br>
            {{ $Data->elaborateInfo->rol ?? '' }}
        </td>

        <td class="signature">
            <br><br><br>
            {{ $Data->authorizeInfo->name ?? '-' }}
            {{ $Data->authorizeInfo->a_paterno ?? '' }}
            {{ $Data->authorizeInfo->a_materno ?? '' }}
            <br>
            {{ $Data->authorizeInfo->rol ?? '' }}
        </td>
    </tr>

    <tr>
        <td class="gray center" style="width:50%;">ELABOR&Oacute;</td>
        <td class="gray center" style="width:50%;">AUTORIZACI&Oacute;N</td>
    </tr>
</table>

</body>
</html>