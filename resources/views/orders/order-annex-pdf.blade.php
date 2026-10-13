<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ANEXO ORDEN DE SERVICIO</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #001028;
            font-size: 10px;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .header td {
            border: 2px solid #000;
            vertical-align: middle;
        }

        .logo-cell {
            width: 25%;
            text-align: center;
            padding: 8px;
        }

        .logo {
            max-width: 145px;
            max-height: 95px;
        }

        .company-cell {
            width: 77%;
            text-align: center;
            padding: 0;
        }

        .company {
            color: #0073B5;
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
        }

        .document-code {
            background: #F4B083;
            color: #FFFFFF;
            font-size: 9px;
            font-weight: bold;
            padding: 7px 4px;
            margin: 0;
        }

        .evidence-title {
            background: #F4B083;
            border: 1px solid #000;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            padding: 9px;
            margin: 0;
        }

        .photo-grid {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #000;
        }

        .photo-grid td {
            width: 33.333%;
            height: 108mm;
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        .photo {
            max-width: 100%;
            max-height: 100mm;
        }
    </style>
</head>

<body>

    {{-- ENCABEZADO INSTITUCIONAL --}}
    <table class="header">
        <tr>
            <td class="logo-cell">
                @if(!empty($logoImage))
                    <img class="logo" src="{{ $logoImage }}">
                @endif
            </td>

            <td class="company-cell">
                <p class="company">TRAMUSA CARRIER S.A. DE C.V.</p>
                <p class="title">ANEXO ORDEN DE SERVICIO</p>
                <p class="document-code">
                    ÁREA: MANTENIMIENTO &nbsp;&nbsp;
                    F-05-01/R1 &nbsp;&nbsp;
                    REVISIÓN: SEPTIEMBRE 2018
                </p>
            </td>
        </tr>
    </table>

    {{-- RECUADRO DE EVIDENCIAS --}}
    <div class="evidence-title">EVIDENCIA FOTOGRÁFICA</div>

    <table class="photo-grid">
        @for($row = 0; $row < 2; $row++)
            <tr>
                @for($col = 0; $col < 3; $col++)
                    @php
                        $index = ($row * 3) + $col;
                    @endphp

                    <td>
                        @if(isset($images[$index]))
                            <img
                                class="photo"
                                src="{{ $images[$index]['src'] }}"
                            >
                        @endif
                    </td>
                @endfor
            </tr>
        @endfor
    </table>

</body>
</html>
